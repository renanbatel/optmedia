<?php

namespace OptMedia\Api\Endpoints;

use WP_REST_Request;
use WP_REST_Server;
use WP_REST_Response;

use OptMedia\Constants;
use OptMedia\Api\Resources\Endpoint;
use OptMedia\Settings\Option;

class SetUpUpdate extends Endpoint
{

    private $option;

    /**
     * Class Constructor
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct()
    {
        $this->option = new Option();
    }

    /**
     * Sets the option object
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
     * Validates the post request body
     *
     * @param array $body The request body
     * @return boolean
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function postIsValid($body): bool
    {
        return isset($body["isSetUp"]);
    }

    /**
     * Handles endpoint GET method
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response The response
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function post(WP_REST_Request $request): WP_REST_Response
    {
        $body = $request->get_json_params();

        if ($this->postIsValid($body)) {
            $isSetUp = !!$body["isSetUp"];
            $success = $this->option->updateOption(Constants::PLUGIN_IS_SETUP, $isSetUp);

            if ($success) {
                return $this->response([
                    "success" => true,
                    Constants::PLUGIN_IS_SETUP => $isSetUp,
                ]);
            } else {
                return $this->internalError();
            }
        }
        
        return $this->error();
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
            "/setUpUpdate",
            WP_REST_Server::CREATABLE,
            "defaultPermission"
        );
    }
}
