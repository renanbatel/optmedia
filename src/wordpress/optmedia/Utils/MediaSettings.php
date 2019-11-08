<?php

/**
 * Class MediaSettings
 *
 * This class is used for getting the configured media sizes
 */

namespace OptMedia\Utils;

class MediaSettings
{
    /**
     * Get image sizes names
     *
     * @return array Image sizes names
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImageSizesNames(): array
    {
        return get_intermediate_image_sizes();
    }

    /**
     * Gets the configured media sizes
     *
     * @return array The media sizes with its data
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getImageSizes(): array
    {
        $additionalSizes = wp_get_additional_image_sizes();
        $names = $this->getImageSizesNames();

        return array_map(function (string $name) use ($additionalSizes) {
            if (isset($additionalSizes[$name])) {
                return [
                    "name"   => $name,
                    "width"  => $additionalSizes[$name]["width"],
                    "height" => $additionalSizes[$name]["height"],
                    "crop"   => $additionalSizes[$name]["crop"],
                ];
            }
    
            return [
                "name"   => $name,
                "width"  => get_option("{$name}_size_w"),
                "height" => get_option("{$name}_size_h"),
                "crop"   => !!get_option("{$name}_crop"),
            ];
        }, $names);
    }

    /**
     * Get image size
     *
     * @param string $descriptor w (width) or h (height)
     * @param mixed $value The dimension value
     * @return string The image size name
     *
     * @since 0.1.2
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function getImageSizeNameByDimension(string $descriptor, $value): string
    {
        $sizes = $this->getImageSizes();

        foreach ($sizes as $size) {
            $key = $descriptor === "w" ? "width" : "height";

            if (intval($size[$key]) === intval($value)) {
                return $size["name"];
            }
        }

        return "original";
    }
}
