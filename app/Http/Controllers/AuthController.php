<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // /**
    //  * Create a new AuthController instance.
    //  *
    //  * @return void
    //  */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

        
    /**
    *  User Login
    *  @OA\Post (
    *     path="/api/auth/login",
    *     description="Login user",
    *     tags={"Auth Module"},
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="email",
    *                     format="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     format="password",
    *                     minLength=6,
    *                     type="string"
    *                 ),
    *                 example={"email": "fernando@mail.com", "password": "123456"}
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(
    *             @OA\Property(property="access_token", type="string", 
    *               example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F"),
    *             @OA\Property(property="token_type", type="string", example="bearer"),
    *             @OA\Property(property="expires_in", type="string", example=3600)
    *         )
    *     ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Unauthorized"),
    *          )
    * 
    *     )
    * )
    */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        activity()->causedBy(Auth::user())->withProperties(['from' => 'foreign api'])->log('login');

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        activity()->causedBy(Auth::user())->withProperties(['from' => 'foreign api'])->log('me');
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        activity()->causedBy(Auth::user())->withProperties(['from' => 'foreign api'])->log('logout');
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        activity()->causedBy(Auth::user())->withProperties(['from' => 'foreign api'])->log('register');
        return response()->json([
            'message' => 'Successfully create user',
            'user' => $user
        ]);
    }
}