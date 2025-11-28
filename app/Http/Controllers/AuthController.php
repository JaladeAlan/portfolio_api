<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * @OA\Tag(name="Authentication", description="Admin authentication endpoints")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Admin login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */

     private function sendSuccessResponse($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    // Helper method for standardized error responses
    private function sendErrorResponse($message, $status = 400, $errors = [])
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

         return $this->sendSuccessResponse(['token' => $token], 'Login successful');
    }


    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Get logged-in admin info",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Admin info returned"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
  
    public function me()
    {
        return response()->json(auth('api')->user());
    }


    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout admin",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Logout successful")
     * )
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }

}
