<?php

namespace OptMedia\Api;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;
use WP_Error;

class Api extends WP_Rest_Controller
{
    protected $options;
    
    public function __construct()
    {
        $this->options = (array) json_decode(get_option(OPTMEDIA_OPTIONS_NAME));
    }

    public function registerRoutes()
    {
        register_rest_route(
            OPTMEDIA_API_NAMESPACE,
            "/options",
            [
                [
                    "methods"             => WP_REST_Server::READABLE,
                    "callback"            => [
                        $this,
                        "getOptions",
                    ],
                    "permission_callback" => [
                        $this,
                        "getOptionsPermission",
                    ],
                ],
            ]
        );
    }

    public function getOptions(WP_REST_Request $request)
    {
        $result = (object) [
            "data" => [
                "asset_path"      => plugin_dir_url(OPTMEDIA_PLUGIN_FILE) . "static",
                "language"        => get_bloginfo("language"),
                "name"            => OPTMEDIA_NAME,
                "options"         => $this->options,
                "translationSlug" => OPTMEDIA_DOMAIN,
                "version"         => OPTMEDIA_VERSION,
            ],
        ];

        return $result;
    }

    public function getOptionsPermission()
    {
        $permissions = $this->options["settings_userAccessLevel"];

        if (!current_user_can($permissions)) {
            return new WP_Error(
                "Rest Forbidden",
                esc_html__("You do not have permissions to access this endpoint.", OPTMEDIA_DOMAIN),
                ["status" => 401]
            );
        }

        return true;
    }
}
