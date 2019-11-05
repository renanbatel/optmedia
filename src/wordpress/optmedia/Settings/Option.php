<?php

/**
 * Class Option
 *
 * This class is used to manipulate the plugin options
 */

namespace OptMedia\Settings;

class Option
{
    /**
     * Gets the plugin options object
     *
     * @return array The plugin options object
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getOptions()
    {
        return json_decode(get_option(OPTMEDIA_OPTIONS_NAME), true);
    }

    /**
     * Gets the option key value
     *
     * @param string $key The option key
     * @return mixed The option value
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function getOption($key)
    {
        $options = $this->getOptions();

        return isset($options[$key]) ? $options[$key] : null;
    }

    /**
     * Updates an option key value
     *
     * @param string $key The option key
     * @param mixed $value The new option value
     * @return boolean True if option value has changed, false if not or if update failed.
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function updateOption($key, $value)
    {
        $options = $this->getOptions();
        
        if ($options[$key] === $value) {
            return true;
        }

        $options[$key] = $value;

        return update_option(OPTMEDIA_OPTIONS_NAME, json_encode($options));
    }
}
