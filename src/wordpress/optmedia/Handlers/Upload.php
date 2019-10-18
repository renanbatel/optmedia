<?php

/**
 * Class Upload
 *
 * Handles the media uploads
 */

namespace OptMedia\Handlers;

use OptMedia\Helpers\Conditions;
use OptMedia\Utils\MediaSettings;
use OptMedia\Providers\Server\ServerImage;
use OptMedia\Providers\Server\ServerImageInfo;
use OptMedia\Providers\Server\ServerImageOptimizer;

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
    private $convertedAttachmentsIds;
    private $uploadWasAllowed;
    private $uploadedMimeType;
    private $uploadedType;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->uploadDir = wp_upload_dir(null, false);
        $this->mediaSettings = new MediaSettings();
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
                $this->convertedAttachmentsIds = $this->handleImage($upload);
            } else if (Conditions::isVideoMimeType($upload["type"])) {
                $this->uploadedType = "video";
                $this->convertedAttachmentsIds = $this->handleVideo($upload);
            }
        }

        return $upload;
    }

    /**
     * Handles the image upload
     *
     * @param array $upload The uploaded file information
     * @return array Array of the converted images attachment id
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function handleImage($upload): array
    {
        $convertedAttachmentsIds = [];

        foreach (self::$imageFormats as $format) {
            // Skip if it's same format as uploaded
            if ($this->isSameFormat($upload["type"], $format)) {
                continue;
            }

            $serverImage = new ServerImage($upload["file"]);
            $convertedFile = $serverImage->manipulator->convert($format);
            $convertedAttachmentsIds[$format] = $this->createConvertedAttachment(
                $convertedFile,
                $format
            );
        }

        return $convertedAttachmentsIds;
    }

    /**
     * Creates the converted file attachment
     *
     * @param string $convertedFile The converted file path
     * @param string $format The converted file format
     * @return integer The created attachment id
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function createConvertedAttachment($convertedFile, $format): int
    {
        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it
        require_once(ABSPATH . "wp-admin/includes/image.php");

        $basename = basename($convertedFile);
        $title = preg_replace("/\.[^.]+$/", "", $basename);
        $attachment = [
            "guid" => "{$this->uploadDir["url"]}/{$basename}",
            "post_mime_type" => "image/{$format}",
            "post_title" => $title,
            "post_content" => "",
            "post_status" => "inherit",
        ];
        $attachmentId = wp_insert_attachment($attachment, $convertedFile);
        $attachmentData = wp_generate_attachment_metadata($attachmentId, $convertedFile);

        wp_update_attachment_metadata($attachmentId, $attachmentData);

        return $attachmentId;
    }

    /**
     * Prevent Wordpress from handling the plugins supported types files
     *
     * @param bool $result The result from file_is_displayable_image()
     * @param string $path The file path
     * @return boolean
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
            
            if (Conditions::isImageMimeType($mimeType)) {
                $optimized = null;
                $serverImage = new ServerImage($filePath);
                $imageSizes = $serverImage->info->getSizes();
                $metadata["width"] = $imageSizes["w"];
                $metadata["height"] = $imageSizes["h"];
                $metadata["file"] = $this->getRelativeUploadPath($filePath);
                $sizes = apply_filters(
                    "intermediate_image_sizes_advanced",
                    $this->mediaSettings->getSizes(),
                    $metadata
                );

                foreach ($actions as $action) {
                    if ($action === "optimize") {
                        // Optimize uploaded image
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
                                $resizedServerImageInfo = new ServerImageInfo($resizedImage);
                                $resizedImageSizes = $resizedServerImageInfo->getSizes();
                                $sizesMetadata[$size["name"]] = [
                                    "file" => basename($resizedImage),
                                    "width" => $resizedImageSizes["w"],
                                    "height" => $resizedImageSizes["h"],
                                    "mime-type" => $mimeType,
                                ];

                                // Optimize image if source is not already optimized
                                if (!$optimized) {
                                    $resizedServerImageOptimizer = new ServerImageOptimizer($resizedImage);

                                    $resizedServerImageOptimizer->optimize();
                                }
                            }
                        }

                        $metadata["sizes"] = $sizesMetadata;
                    }
                }
                
                $imageMeta = wp_read_image_metadata($filePath);

                if ($imageMeta) {
                    $metadata["image_meta"] = $imageMeta;
                }
            }

            // Set attachments relationship
            if ($mimeType === $this->uploadedMimeType) {
                $metaKey = "optmedia_other_formats";

                foreach ($this->convertedAttachmentsIds as $format => $convertedAttachmentId) {
                    $allowedFormats = $this->uploadedType === "image"
                        ? self::$imageFormats
                        : self::$videoFormats;
                    $otherFormats = [];

                    foreach ($allowedFormats as $allowedFormat) {
                        if ($format === $allowedFormat) {
                            continue;
                        }

                        $otherFormats[$allowedFormat] = isset($this->convertedAttachmentsIds[$allowedFormat])
                            ? $this->convertedAttachmentsIds[$allowedFormat]
                            : $attachmentId;
                    }

                    update_post_meta($convertedAttachmentId, $metaKey, $otherFormats);
                }

                update_post_meta($attachmentId, $metaKey, $this->convertedAttachmentsIds);
            }
        }

        return $metadata;
    }
}
