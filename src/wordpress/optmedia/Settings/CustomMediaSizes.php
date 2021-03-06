<?php

/**
 * Class CustomMediaSizes
 *
 * This class handles the custom media sizes creation and loading
 */

namespace OptMedia\Settings;

use OptMedia\Constants;
use OptMedia\Settings\Option;

class CustomMediaSizes extends Option
{
    /**
     * Get the custom media sizes option value
     *
     * @return array The option value
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function get()
    {
        return (array) $this->getOption(Constants::SETTINGS_CUSTOM_MEDIA_SIZES);
    }

    /**
     * Updates the custom media sizes option value
     *
     * @param array $customSizes The new value for the option
     * @return boolean True if option value has changed, false if not or if update failed.
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function update($customSizes)
    {
        return $this->updateOption(Constants::SETTINGS_CUSTOM_MEDIA_SIZES, $customSizes);
    }

    /**
     * Handles the custom sizes loading
     *
     * @param array $customSizes The custom media sizess
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function set($customSizes)
    {
        foreach ($customSizes as $key => $customSize) {
            add_image_size(
                $customSize["name"],
                $customSize["width"],
                $customSize["height"],
                $customSize["crop"]
            );
        }
    }

    /**
     * Loads the custom media sizes
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function load()
    {
        $this->set($this->get());
    }
}
