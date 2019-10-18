<?php

/**
 * Class Settings
 *
 * This class handles the plugin settings load
 */

namespace OptMedia\Settings;

use OptMedia\Settings\CustomMediaSizes;

class Settings
{

    /**
     * Loads the custom media sizes
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    private function loadCustomMediaSizes()
    {
        $customMediaSizes = new CustomMediaSizes();

        $customMediaSizes->load();
    }

    /**
     * Loads the settings
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function load()
    {
        $this->loadCustomMediaSizes();
    }
}
