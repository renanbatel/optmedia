<?php

/**
 * Class Theme
 *
 * Everything that should be executed on site theme should be part of this file.
 *
 */

namespace OptMedia\Theme;

class Theme
{
    /**
     * Public Stylesheets
     *
     * Docs: https://developer.wordpress.org/reference/functions/wp_enqueue_style/
     *
     * Note: this function is loaded in class-plugin.php (core file of the plugin)
     * and new public hooks should be initialised/added there and _not_ in this file.
     * This ensures a consistent way of adding new features and maintainability.
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function enqueueStyles()
    {
        wp_enqueue_style(
            OPTMEDIA_NAME,
            plugin_dir_url(__FILE__) . "css/theme.styles.css",
            [],
            OPTMEDIA_VERSION,
            "all"
        );
    }

    /**
     * Public Javascript
     *
     * Docs: https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function enqueueScripts()
    {
        wp_enqueue_script(
            OPTMEDIA_NAME,
            plugin_dir_url(__FILE__) . "js/theme.bundle.js",
            [],
            OPTMEDIA_VERSION,
            false
        );

        // inject some wp specific variables into the public script, they are
        // available in the __OPTMEDIA__ object afterwards
        wp_localize_script(
            OPTMEDIA_NAME,
            "__OPTMEDIA__",
            [
                "namespace" => OPTMEDIA_API_NAMESPACE,
                "nonce"     => wp_create_nonce("wp_rest"),
                "root"      => esc_url_raw(rest_url()),
            ]
        );
    }
}
