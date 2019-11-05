<?php
/**
 * Class Deactivator
 *
 * This class handles all logic necessary during the deactivation of the plugin.
 */

namespace OptMedia;

use OptMedia\Constants;

class Deactivator
{
    /**
     * Executes the logic for the plugin deactivation
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function deactivate()
    {
        // do something here, but consider using uninstall.php if user removes plugin
        // eg. update the last installed plugin version
        $options = (array) json_decode(get_option(OPTMEDIA_OPTIONS_NAME));
        $options[Constants::PLUGIN_LAST_VERSION] = OPTMEDIA_VERSION;

        update_option(OPTMEDIA_OPTIONS_NAME, json_encode($options));
    }
}
