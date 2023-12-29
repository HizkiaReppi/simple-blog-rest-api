<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Log the user out (Revoke the token).
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $tokensDeleted = $user->tokens()->delete();

            if ($tokensDeleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logout Success!',
                ]);
            }
        }

        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
    }
}
