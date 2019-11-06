<?php

namespace OptMedia\Api;

use OptMedia\Lib\DirectoryIterator;

class Api
{
    /**
     * Get the endpoint class from its path
     *
     * @param string $path The endpoint class path
     * @return string The endpoint class
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    protected function getEndpointClass($path)
    {
        $name = str_replace(["/", ".php"], ["\\", ""], $path);

        return "\\OptMedia\\Api\\Endpoints{$name}";
    }

    /**
     * Load the REST API endpoints
     *
     * @return void
     *
     * @since 0.1.1
     * @author Renan Batel Rodrigues <renanbatel@gmail.com>
     */
    public function registerRoutes()
    {
        $endpointsPath = __DIR__ . "/Endpoints";
        $endpointFiles = DirectoryIterator::getFiles($endpointsPath, "php");

        foreach ($endpointFiles as $endpointFile) {
            $path = str_replace($endpointsPath, "", $endpointFile);
            $class = $this->getEndpointClass($path);
            $endpoint = new $class;

            $endpoint->load();
        }
    }
}
