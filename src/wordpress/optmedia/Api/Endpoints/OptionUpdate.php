<?php

namespace OptMedia\Api\Endpoints;

use WP_REST_Request;
use WP_REST_Server;
use WP_REST_Response;

use OptMedia\Constants;
use OptMedia\Api\Resources\Endpoint;
use OptMedia\Api\Resources\Validator;
use OptMedia\Settings\Option;

class OptionUpdate extends Endpoint
{
    private $option;

    /**
     * Class Constructor
     *
     * @since 0.2.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct()
    {
        $this->option = new Option();
    }

    /**
     * Creates the OptionUpdate Object with given dependencies
     *
     * @param Option $option
     * @return OptionUpdate
     *
     * @since 0.2.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function factory(Option $option): OptionUpdate
    {
        $setUpUpdate = new OptionUpdate();

        $setUpUpdate->setOption($option);

        return $setUpUpdate;
    }

    /**
     * Sets the option object
     *
     * @param Option $option The option object
     * @return void
     *
     * @since 0.2.0
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
     * @since 0.2.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function postIsValid($body): bool
    {
        $possibleKeys = [
            Constants::PLUGIN_IMAGE_FORMATS,
        ];

        return !Validator::isEmpty($body, "key")
            && in_array($body["key"], $possibleKeys)
            && !Validator::isEmpty($body, "value");
    }

    /**
     * Handles endpoint POST method
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response The response
     *
     * @since 0.2.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function post(WP_REST_Request $request): WP_REST_Response
    {
        $body = $request->get_json_params();

        if ($this->postIsValid($body)) {
            $success = $this->option->updateOption($body["key"], $body["value"]);

            if ($success) {
                return $this->response([
                    "success" => true,
                    "option" => [
                        "key" => $body["key"],
                        "value" => $body["value"],
                    ],
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
     * @since 0.2.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function load()
    {
        $this->registerRoute(
            "/optionUpdate",
            WP_REST_Server::CREATABLE,
            "defaultPermission"
        );
    }
}
