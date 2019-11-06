<?php

namespace OptMedia\Tests\Resources\Utils;

use WP_REST_Request;

class REST
{

    /**
     * Creates a JSON Request
     *
     * @param array $body The request body
     * @return WP_REST_Request The REST Request
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public static function setUpJSONRequest(array $body): WP_REST_Request
    {
        $request = new WP_REST_Request;

        $request->set_header("content-type", "application/json");
        $request->set_body(json_encode($body));

        return $request;
    }
}
