<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Portfolio API",
 *     version="1.0.0",
 *     description="API documentation for the Portfolio project"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local development server"
 * )
 */
class OpenApi
{
    // This class can remain empty
}
