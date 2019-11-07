<?php

/**
 * Class Upload
 *
 * Handles the media uploads
 */

namespace OptMedia\Handlers;

use OptMedia\Constants;
use OptMedia\Helpers\Conditions;
use OptMedia\Helpers\Values;
use OptMedia\Utils\MediaSettings;
use OptMedia\Providers\Resources\ImageFactory;

// TODO: split logic in different classes
// TODO: disable all video handling for now
// TODO: (Plugin Setting) chose only one format to use for medias
// TODO: don't convert images with alpha to jpg
class Upload
{
    public static $allowedMimeTypes = [
        "image/png",
        "image/jpeg",
        "image/webp",
        "video/mp4",
        "video/webm",
    ];
    public static $imageFormats = [
        "jpeg",
        "png",
        "webp",
    ];
    public static $videoFormats = [
        "mp4",
        "webm",
    ];
    
    private $uploadDir;
    private $mediaSettings;
    private $imageFactory;
    private $convertedAttachments;
    private $uploadedAttachment;
    private $uploadWasAllowed;
    private $uploadedMimeType;
    private $uploadedType;

    /**
     * Class constructor
     *
     * @param ImageFactory $imageFactory An ImageFactory instance
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function __construct(ImageFactory $imageFactory)
    {
        $this->uploadDir = wp_upload_dir(null, false);
        $this->mediaSettings = new MediaSettings();
        $this->imageFactory = $imageFactory;
        $this->convertedAttachments = [];
        $this->uploadedAttachment = [];
    }

    /**
     * Set the generated attachments relationship
     *
     * @param integer $attachmentId
     * @return void
     *
     * @author Renan Batel <renanbatel@gmail.com>
     * @since 0.1.1
     */
    protected function setAttachmentsRelationship(int $attachmentId): void
    {
        $metaKey = Constants::ATTACHMENT_META_FILES;

        foreach ($this->convertedAttachments as $format => $convertedAttachment) {
            $allowedFormats = $this->uploadedType === "image"
                ? self::$imageFormats
                : self::$videoFormats;
            $files = [];

            foreach ($allowedFormats as $allowedFormat) {
                if ($format === $allowedFormat) {
                    $files["self"] = $convertedAttachment;

                    continue;
                }

                $files[$allowedFormat] = isset($this->convertedAttachments[$allowedFormat])
                    ? $this->convertedAttachments[$allowedFormat]
                    : $this->uploadedAttachment;
            }

            update_post_meta($convertedAttachment["id"], $metaKey, $files);
        }

        update_post_meta($attachmentId, $metaKey, $this->convertedAttachments + [
            "self" => $this->uploadedAttachment,
        ]);
    }

    /**
     * Get if the upload handling was allowed
     *
     * @return boolean
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getUploadWasAllowed(): bool
    {
        return $this->uploadWasAllowed;
    }

    /**
     * Get the uploaded file mime type
     *
     * @return string The uploaded file mime type
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getUploadedMimeType(): string
    {
        return $this->uploadedMimeType;
    }

    /**
     * Gets the media file  path relative to uploads folder, same as Wordpress
     * function _wp_relative_upload_path(), this was implemented because
     * the specified function has private access
     *
     * Ref: https://developer.wordpress.org/reference/functions/_wp_relative_upload_path/
     *
     * @param string $path The file path
     * @return string
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getRelativeUploadPath($path): string
    {
        $newPath = $path;

        if (0 === strpos($newPath, $this->uploadDir["basedir"])) {
            $newPath = str_replace($this->uploadDir["basedir"], "", $newPath);
            $newPath = ltrim($newPath, "/");
        }

        return apply_filters("_wp_relative_upload_path", $newPath, $path);
    }

    /**
     * Check if the upload is allowed
     *
     * @param string $type The uploaded file type
     * @return boolean
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function isAllowed($type): bool
    {
        return in_array($type, self::$allowedMimeTypes);
    }

    /**
     * Check if the type is the same as the format
     *
     * @param string $type A file type
     * @param string $format A file type
     * @return boolean
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function isSameFormat($type, $format): bool
    {
        return $type === "image/{$format}";
    }
    
    /**
     * Handles the media upload
     *
     * @param array $upload The uploaded file information
     * @param string $context The upload context
     *
     * @return array The uploaded file information unchanged
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function handleUpload($upload, $context): array
    {
        if ($this->isAllowed($upload[ "type" ])) {
            $this->uploadWasAllowed = true;
            $this->uploadedMimeType = $upload["type"];
            
            if (Conditions::isImageMimeType($upload["type"])) {
                $this->uploadedType = "image";
                $this->handleImage($upload);
            } else if (Conditions::isVideoMimeType($upload["type"])) {
                $this->uploadedType = "video";
                $this->handleVideo($upload);
            }
        }

        return $upload;
    }

    /**
     * Handles the image upload
     *
     * @param array $upload The uploaded file information
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function handleImage($upload): void
    {
        foreach (self::$imageFormats as $format) {
            // Skip if it's same format as uploaded
            if ($this->isSameFormat($upload["type"], $format)) {
                continue;
            }

            $serverImage = $this->imageFactory->getImage($upload["file"]);
            $convertedFile = $serverImage->manipulator->convert($format);
        
            $this->createFileAttachment($convertedFile, $format);
        }
    }

    /**
     * Creates the file attachment
     *
     * @param string $file The attachment file path
     * @param string $format The attachment file format
     * @return integer The created attachment id
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function createFileAttachment($file, $format): int
    {
        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it
        require_once(ABSPATH . "wp-admin/includes/image.php");

        $basename = basename($file);
        $title = preg_replace("/\.[^.]+$/", "", $basename);
        $mimeType = "image/{$format}";
        $attachment = [
            "guid" => "{$this->uploadDir["url"]}/{$basename}",
            "post_mime_type" => $mimeType,
            "post_title" => $title,
            "post_content" => "",
            "post_status" => "inherit",
        ];
        $attachmentId = wp_insert_attachment($attachment, $file);
        
        // Save converted attachment information
        if ($this->uploadedMimeType !== $mimeType) {
            $this->convertedAttachments[$format] = [ "id" => $attachmentId ];
        }

        $attachmentData = wp_generate_attachment_metadata($attachmentId, $file);

        wp_update_attachment_metadata($attachmentId, $attachmentData);

        return $attachmentId;
    }

    /**
     * Prevent Wordpress from handling the plugins supported types files
     *
     * @param bool $result The result from file_is_displayable_image()
     * @param string $path The file path
     * @return bool
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function handleDisplayableImage($result, $path): bool
    {
        // check if it's supported for the plugin to disable it
        if ($result) {
            $type = mime_content_type($path);

            return !in_array($type, self::$allowedMimeTypes);
        }

        return $result;
    }

    /**
     * Creates the attachment optimized and resized files
     *
     * @param array $metadata Empty array
     * @param int $attachmentId The attachment id
     * @return array The generated files metadata
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function handleMetadataGeneration($metadata, $attachmentId): array
    {
        if ($this->uploadWasAllowed) {
            $attachment = get_post($attachmentId);
            // TODO: make action order settable by user
            $actions = explode(" ", "optimize resize");
            $filePath = get_attached_file($attachmentId);
            $mimeType = get_post_mime_type($attachment);
            $isUploadedFile = $mimeType === $this->uploadedMimeType;
            
            if (Conditions::isImageMimeType($mimeType)) {
                $optimized = null;
                $serverImage = $this->imageFactory->getImage($filePath);
                $imageSizes = $serverImage->info->getSizes();
                $metadata["width"] = $imageSizes["w"];
                $metadata["height"] = $imageSizes["h"];
                $metadata["file"] = $this->getRelativeUploadPath($filePath);
                $sizes = apply_filters(
                    "intermediate_image_sizes_advanced",
                    $this->mediaSettings->getSizes(),
                    $metadata
                );
                $extension = Values::getExtensionFromMimeType($mimeType);

                if ($isUploadedFile) {
                    $this->uploadedAttachment["id"] = $attachmentId;
                    $this->uploadedAttachment["sizes"] = [];
                } else {
                    $this->convertedAttachments[$extension]["sizes"] = [];
                }

                foreach ($actions as $action) {
                    if ($action === "optimize") {
                        // Optimize source image
                        $optimized = $serverImage->optimizer->optimize();
                    } else if ($action === "resize") {
                        $sizesMetadata = [];

                        foreach ($sizes as $size) {
                            $resizedImage = $serverImage->manipulator->resize(
                                $size["width"],
                                $size["height"],
                                $size["crop"]
                            );
            
                            if ($resizedImage) {
                                $resizedServerImageInfo = $this->imageFactory->getImageInfo($resizedImage);
                                $resizedImageSizes = $resizedServerImageInfo->getSizes();
                                $sizesMetadata[$size["name"]] = [
                                    "file" => basename($resizedImage),
                                    "width" => $resizedImageSizes["w"],
                                    "height" => $resizedImageSizes["h"],
                                    "mime-type" => $mimeType,
                                ];

                                // Optimize image if source is not already optimized
                                if (!$optimized) {
                                    $resizedServerImageOptimizer = $this->imageFactory->getImageOptimizer($resizedImage);

                                    $resizedServerImageOptimizer->optimize();
                                }

                                $sizeInformation = Values::buildFileInformation(
                                    $resizedImage,
                                    $resizedImageSizes["w"],
                                    $resizedImageSizes["h"]
                                );

                                if ($isUploadedFile) {
                                    $this->uploadedAttachment["sizes"][$size["name"]] = $sizeInformation;
                                } else {
                                    $this->convertedAttachments[$extension]["sizes"][$size["name"]] = $sizeInformation;
                                }
                            }
                        }

                        $metadata["sizes"] = $sizesMetadata;
                    }
                }

                $originalImageInformation = Values::buildFileInformation(
                    $filePath,
                    $imageSizes["w"],
                    $imageSizes["h"]
                );

                if ($isUploadedFile) {
                    $this->uploadedAttachment["sizes"]["original"] = $originalImageInformation;
                } else {
                    $this->convertedAttachments[$extension]["sizes"]["original"] = $originalImageInformation;
                }
                
                $imageMeta = wp_read_image_metadata($filePath);

                if ($imageMeta) {
                    $metadata["image_meta"] = $imageMeta;
                }
            }

            // If it's the file that was uploaded set attachments relationship
            if ($isUploadedFile) {
                $this->setAttachmentsRelationship($attachmentId);
            }
        }

        return $metadata;
    }
}
