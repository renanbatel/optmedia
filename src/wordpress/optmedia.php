<?php

/**
 * Plugin Name:       OptMedia
 * Plugin URI:        https://github.com/renanbatel/optmedia
 * Description:       Speed up your site by converting images and videos for light formats and using them with responsive resizing and lazy load.
 * Version:           0.1.1
 * Author:            Renan Batel
 * Author URI:        https://github.com/renanbatel
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       optmedia
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined("WPINC")) {
    die;
}

// Composer autoload
include __DIR__ . "/vendor/autoload.php";
// OptMedia autoload
include __DIR__ . "/autoload.php";

// Constants
define("OPTMEDIA_NAME", "OptMedia");
define("OPTMEDIA_VERSION", "0.1.1");
define("OPTMEDIA_ADMIN_PAGE_SLUG", "optmedia");
define("OPTMEDIA_OPTIONS_NAME", "optmedia_options");
define("OPTMEDIA_API_NAMESPACE", "optmedia/v1");
define("OPTMEDIA_PLUGIN_FILE", __FILE__);

\OptMedia\Startup::init();

// TODO: use dependency injection
// TODO: save user log for debugging
// TODO: send e-mail to admin with server configuration instructions
