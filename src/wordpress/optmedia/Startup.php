<?php

/**
 * Class Startup
 *
 * This class handles all logic necessary for the Plugin startup
 */

namespace OptMedia;

use OptMedia\OptMedia;
use OptMedia\Activator;
use OptMedia\Deactivator;

class Startup
{
    /**
     * The Activator Class code runs when the plugin is activated in the wp-admin.
     * Keep in mind, that it will run with every activation (even during updates)
     * Docs: https://codex.wordpress.org/Function_Reference/register_activation_hook
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function activate()
    {
        Activator::activate();
    }

    /**
     * The Deactivator Class code runs when the plugin is deactivated in the wp-admin.
     * Docs: https://codex.wordpress.org/Function_Reference/register_deactivation_hook
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function deactivate()
    {
        Deactivator::deactivate();
    }

    /**
     * Initializes the plugin
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function init()
    {
        $self = new self;
        
        // register main hooks (eg. activation, deactivation or uninstall) here
        register_activation_hook(OPTMEDIA_PLUGIN_FILE, [$self, "activate"]);
        register_deactivation_hook(OPTMEDIA_PLUGIN_FILE, [$self, "deactivate"]);

        /**
         * OptMedia contains the core logic and registers public and admin-specific
         * hooks. It also initializes i18n and can be extended to add shortcodes and
         * other actions and hooks.
         */
        $optmedia = new OptMedia();
        $optmedia->run();
    }
}
