<?php

/**
 * Class MediaSettings
 *
 * This class is used for getting the configured media sizes
 */

namespace OptMedia\Utils;

class MediaSettings
{
    protected $additionalSizes;

    /**
     * Gets the media size data
     *
     * @param string $name The media size name
     * @return array The media size data
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function getSizeValues($name)
    {
        if (isset($this->additionalSizes[$name])) {
            return [
                "name"   => $name,
                "width"  => $this->additionalSizes[$name]["width"],
                "height" => $this->additionalSizes[$name]["height"],
                "crop"   => $this->additionalSizes[$name]["crop"],
            ];
        }

        return [
            "name"   => $name,
            "width"  => get_option("{$name}_size_w"),
            "height" => get_option("{$name}_size_h"),
            "crop"   => !!get_option("{$name}_crop"),
        ];
    }

    /**
     * Gets the configured media sizes
     *
     * @return array The media sizes with its data
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getSizes()
    {
        $this->additionalSizes = wp_get_additional_image_sizes();
        
        $sizeNames = get_intermediate_image_sizes();
        $sizes = array_map([ $this, "getSizeValues" ], $sizeNames);

        return $sizes;
    }
}
