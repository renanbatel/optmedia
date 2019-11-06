<?php

namespace OptMedia\Api\Endpoints;

use WP_REST_Server;
use WP_REST_Response;

use OptMedia\Api\Resources\Endpoint;

class ServerDiagnostic extends Endpoint
{
    protected $serverDiagnostic;

    /**
     * Class Constructor
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function __construct()
    {
        $this->serverDiagnostic = new \OptMedia\Utils\ServerDiagnostic();
    }

    /**
     * Creates the ServerDiagnostic object with given dependencies
     *
     * @param \OptMedia\Utils\ServerDiagnostic $serverDiagnosticUtil
     * @return ServerDiagnostic
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function factory(\OptMedia\Utils\ServerDiagnostic $serverDiagnosticUtil): ServerDiagnostic
    {
        $serverDiagnostic = new ServerDiagnostic();

        $serverDiagnostic->setServerDiagnostic($serverDiagnosticUtil);

        return $serverDiagnostic;
    }

    /**
     * Sets the ServerDiagnostic Utility object
     *
     * @param \OptMedia\Utils\ServerDiagnostic $serverDiagnostic
     * @return void
     */
    public function setServerDiagnostic(\OptMedia\Utils\ServerDiagnostic $serverDiagnostic): void
    {
        $this->serverDiagnostic = $serverDiagnostic;
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
        $diagnostic = $this->serverDiagnostic->checkPluginRequirements();

        if (!empty($diagnostic)) {
            return $this->response([
                "success" => true,
                "diagnostic" => $diagnostic,
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
            "/serverDiagnostic",
            WP_REST_Server::READABLE
            // "defaultPermission"
        );
    }
}
