<?php

namespace OptMedia\Api\Endpoints;

use WP_REST_Server;
use WP_REST_Response;

use OptMedia\Api\Resources\Endpoint;
use OptMedia\Settings\Option;

class Options extends Endpoint
{
    protected $option;

    /**
     * Class constructor
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct()
    {
        $this->option = new Option();
    }

    /**
     * Sets the class option object
     *
     * @param Option $option The option object
     * @return void
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function setOption(Option $option): void
    {
        $this->option = $option;
    }

    /**
     * Handles endpoint GET method
     *
     * @return WP_REST_Response The response
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function get(): WP_REST_Response
    {
        $options = $this->option->getOptions();

        if (!empty($options)) {
            return $this->response([
                "success" => true,
                "options" => $options,
            ]);
        }

        return $this->internalError();
    }

    /**
     * Loads the endpoint
     *
     * @return void
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function load()
    {
        $this->registerRoute(
            "/options",
            WP_REST_Server::READABLE,
            "defaultPermission"
        );
    }
}
