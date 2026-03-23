<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Missing or invalid Authorization header.'
            ], 401);
        }

        $idToken = str_replace('Bearer ', '', $authHeader);

        try {
            $auth = Firebase::auth();
            $verifiedIdToken = $auth->verifyIdToken($idToken);
            
            $claims = $verifiedIdToken->claims();
            $tenantId = $claims->get('tenantId');
            $role = $claims->get('role');

            if (!$tenantId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: No tenantId found in token claims.'
                ], 403);
            }

            // Inject tenantId and role into the request attributes
            $request->attributes->set('tenantId', $tenantId);
            $request->attributes->set('role', $role);
            $request->attributes->set('firebase_user_id', $verifiedIdToken->claims()->get('sub'));

            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: ' . $e->getMessage()
            ], 401);
        }
    }
}
