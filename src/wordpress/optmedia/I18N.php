<?php
/**
 * Class I18N
 *
 * Initializes the internationalization functionality
 *
 * Docs:
 * - https://developer.wordpress.org/themes/functionality/localization/
 * - https://codex.wordpress.org/I18n_for_WordPress_Developers
 *
 * How to translate and generate *.pot file
 * - https://blog.templatetoaster.com/translate-wordpress-themes-plugins/
 * - https://wordpress.stackexchange.com/questions/149212/how-to-create-pot-files-with-poedit
 *   - use wpdev to extract and create *.pot file: https://wordpress.stackexchange.com/a/258562
 *
 * Apps
 * - Windows: http://www.eazypo.ca/download.html
 * - Windows/Mac: https://poedit.net/download
 */

namespace OptMedia;

class I18N
{

    /**
     * Load translation file
     *
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel <renanbatel@gmail.com>
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            "optmedia",
            false,
            dirname(dirname(plugin_basename(__FILE__))) . "/languages/"
        );
    }
}
