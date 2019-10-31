<?php

namespace OptMedia\Api\Endpoints;

use WP_REST_Server;

use OptMedia\Api\Resources\Endpoint;
use OptMedia\Utils\ServerDiagnostic as ServerDiagnosticUtil;

class ServerDiagnostic extends Endpoint
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
        return $this->response([
            "success" => true,
            "data" => ServerDiagnosticUtil::checkPluginRequirements(),
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
            "/serverDiagnostic",
            WP_REST_Server::READABLE,
            "defaultPermission"
        );
    }
}