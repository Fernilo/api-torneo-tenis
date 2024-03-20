<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *  @OA\Info(
 *     version="v1.9.0",
 *     title="Tennis tournament → API-REST Documentation",
 *     description="**API-REST:** Admin for tennis tournament",
 * ),
 *
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Server"
 * ),
 *
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     in="header",
 *     name="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 * */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
