<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
*  @OA\Info(title="Api for tennis tournament challenge", version="1.0",  description="Tournaments Actions" )
*   OA\Server(url: 'http://localhost:8000', description: "local server"),
*   OA\Server(url: 'http://staging.example.com', description: "staging server"),
*   OA\Server(url: 'http://example.com', description: "production server"),
*   OAS\SecurityScheme( securityScheme: 'bearerAuth', type: "http", name: "Authorization", in: "header", scheme: "bearer")
*
*/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
