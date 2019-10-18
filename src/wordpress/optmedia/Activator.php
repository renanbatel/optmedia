<?php
/**
 * Class Activator
 *
 * This class handles all logic necessary during the installation and activation
 * of the plugin.
 */

namespace OptMedia;

class Activator
{
    // Store default options in this array
    protected static $defaultOptions;

    /**
     * activate contains the logic for the activation/installation hook that will
     * trigger this method.
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function activate()
    {
        self::$defaultOptions = [];
        
        // NOTE
        // usually everything is in snake_case in php, but not with the options
        // the prefix indicates the usage of this setting (eg. plugin_ is for app-wide
        // settings, settings_ only for the admin-settings pages)
        self::$defaultOptions = [
            "plugin_currentVersion"    => OPTMEDIA_VERSION,
            "plugin_lastVersion"       => OPTMEDIA_VERSION,
            "settings_userAccessLevel" => "manage_options",
        ];

        // get current options
        $options = (array) json_decode(get_option(OPTMEDIA_OPTIONS_NAME));

        if (!$options) {
            // Set up $defaultOptions, if it does not exist yet
            $options = [];
            $options = self::$defaultOptions;
        } else {
            // otherwise we get all the current values and - if necessary - extend it
            // TODO
            // - handle upgrades (eg. plugin_currentVersion > plugin_lastVersion)
            //   see https://gist.github.com/zaus/c08288c68b7f487193d1
            $options = self::updateOptions($options);
        }
        // and add option
        update_option(OPTMEDIA_OPTIONS_NAME, json_encode($options));
    }

    /**
     * Prepares the new plugin's options for an update
     *
     * @param array $options The new plugin's options
     * @return mixed The prepared options
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    private static function updateOptions($options)
    {
        // return whatever was passed to the function, if it is not an array
        if (!is_array($options)) {
            return $options;
        }

        // add new properties/fields when not available yet (with the default value)
        foreach (self::$defaultOptions as $key => $value) {
            if (!array_key_exists($key, $options)) {
                $options[$key] = $value;
            }
        }

        return $options;
    }
}
