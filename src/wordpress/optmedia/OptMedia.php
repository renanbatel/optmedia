<?php
/**
 * Class OptMedia
 *
 * A class that initializes both the admin and public part of the plugin. It
 * should contain hooks, actions and other activation methods the plugin requires.
 */

namespace OptMedia;

use OptMedia\Constants;
use OptMedia\I18N;
use OptMedia\Settings\Settings;
use OptMedia\Admin\Admin;
use OptMedia\Api\Api;
use OptMedia\Handlers;
use OptMedia\Theme\Theme;
use OptMedia\Settings\Option;

// Image providers
// TODO: move this to a better place
use OptMedia\Providers\Resources\ImageFactory;
use OptMedia\Providers\Server\ServerImage;
use OptMedia\Providers\Server\ServerImageInfo;
use OptMedia\Providers\Server\ServerImageManipulator;
use OptMedia\Providers\Server\ServerImageOptimizer;

class OptMedia
{
    // Maintains and registers all hooks for the plugin.
    protected $hooks;

    /**
     * Load OptMedia\I18N, responsible for the internationalization
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    private function loadLocalization()
    {
        $i18n = new I18N();

        add_action("plugins_loaded", [$i18n, "loadPluginTextdomain"]);
    }

    /**
     * Loads the plugin settings
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    private function loadSettings()
    {
        $settings = new Settings();

        $settings->load();
    }

    /**
     * Load admin features
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    private function loadAdmin()
    {
        // initialize admin classes here and add them accordingly below
        $admin = new Admin();

        // load scripts and stylesheets
        add_action("admin_enqueue_scripts", [$admin, "enqueueStyles"]);
        add_action("admin_enqueue_scripts", [$admin, "enqueueScripts"]);

        // Admin Page
        add_action("admin_menu", [$admin, "addMenuPage"]);
    }

    /**
     * Load plugin api
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    private function loadApi()
    {
        $api = new Api();

        add_action("rest_api_init", [$api, "registerRoutes"]);
    }

    /**
     * Load media handlers
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    private function loadHandlers()
    {
        $upload = new Handlers\Upload(new ImageFactory(
            ServerImage::class,
            ServerImageInfo::class,
            ServerImageManipulator::class,
            ServerImageOptimizer::class
        ));

        add_filter("wp_handle_upload", [$upload, "handleUpload"], 10, 2);
        add_filter("file_is_displayable_image", [$upload, "handleDisplayableImage"], 10, 2);
        add_filter("wp_generate_attachment_metadata", [$upload, "handleMetadataGeneration"], 10, 2);

        $attachment = new Handlers\Attachment();
        
        add_filter("wp_calculate_image_srcset", [$attachment, "handleImageSrcsetCalculation"], 10, 5);
    }

    /**
     * Load site theme features
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    private function loadTheme()
    {
        // initialize theme classes here and add them accordingly below
        $theme = new Theme();

        // load scripts and stylesheets
        add_action("wp_enqueue_scripts", [$theme, "enqueueStyles"]);
        add_action("wp_enqueue_scripts", [$theme, "enqueueScripts"]);

        // NOTE:
        // - adding eg. shortcodes or other public features/hooks would happen here
    }

    /**
     * Load all dependencies which will eventually start the plugin
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function run()
    {
        $option = new Option();

        $this->loadLocalization();
        $this->loadSettings();
        $this->loadAdmin();
        $this->loadApi();

        // Only load handlers if plugin is set up
        if ($option->getOption(Constants::PLUGIN_IS_SETUP)) {
            $this->loadHandlers();
        }

        $this->loadTheme();
    }
}
