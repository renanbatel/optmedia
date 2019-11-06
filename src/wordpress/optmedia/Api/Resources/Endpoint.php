<?php

namespace OptMedia\Api\Resources;

use ReflectionMethod;
use WP_Error;
use WP_REST_Response;

use OptMedia\Constants;
use OptMedia\Settings\Option;

abstract class Endpoint
{
    abstract public function load();

    /**
     * Register a new route for an endpoint
     *
     * @param string $route The endpoint route
     * @param string $methods The endpoint methods
     * @param string $permission The endpoint permission callback name
     * @return void
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function registerRoute($route, $methods, $permission = "")
    {
        $args = [
            "callback" => [$this, "router"],
            "methods" => $methods,
        ];

        if ($permission) {
            $args["permission_callback"] = [$this, $permission];
        }

        \register_rest_route(
            OPTMEDIA_API_NAMESPACE,
            $route,
            $args
        );
    }

    /**
     * Check user capabilities for default permission
     *
     * @return bool If the user is permitted
     *
     * @since 0.1.0
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function defaultPermission()
    {
        $option = new Option();
        $permissions = $option->getOption(Constants::SETTINGS_USER_ACCESS_LEVEL);

        if (!current_user_can($permissions)) {
            return new WP_Error(
                "Rest Forbidden",
                esc_html__("You do not have permissions to access this endpoint.", OPTMEDIA_DOMAIN),
                ["status" => 401]
            );
        }

        return true;
    }

    /**
     * Routes the request for the specif method by its type
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function router($request)
    {
        $method = strtolower($request->get_method());
        $reflection = new ReflectionMethod($this, $method);

        if ($reflection->getNumberOfParameters() === 1) {
            return $this->{$method}($request);
        } else {
            return $this->{$method}();
        }
    }

    /**
     * Creates an API Response Object
     *
     * @param mixed $body The response body
     * @param integer $status The response status
     * @return WP_REST_Response The response
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function response($body, $status = 200)
    {
        return new WP_REST_Response($body, $status);
    }
    
    /**
     * Returns an API success message response
     *
     * @param string $message The response message
     * @return WP_REST_Response The response
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function success($message = "Your request was sent successfully")
    {
        return $this->response([
            "success" => true,
            "message" => $message,
        ], 200);
    }
    
    /**
     * Returns an API client error message response
     *
     * @param string $message The response message
     * @return WP_REST_Response The response
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function error($message = "Bad Request")
    {
        return $this->response([
            "error" => true,
            "message" => $message,
        ], 400);
    }
    
    /**
     * Returns an API internal error message response
     *
     * @param string $message The response message
     * @return WP_REST_Response The response
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function internalError($message = "Server Internal Error")
    {
        return $this->response([
            "error" => true,
            "message" => $message,
        ], 500);
    }
}
