<?php

namespace OptMedia\Api\Endpoints;

use WP_REST_Server;

use OptMedia\Api\Resources\Endpoint;
use OptMedia\Settings\Option;

class Options extends Endpoint
{
    /**
     * Handles endpoint GET method
     *
     * @param WP_REST_Request $request
     * @return void
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function get($request)
    {
        $option = new Option();

        return $this->response([
            "success" => true,
            "options" => $option->getOptions(),
        ]);
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
