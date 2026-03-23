<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function me(Request $request)
    {
        $uid = $request->attributes->get('firebase_user_id');
        $user = $this->authService->getProfileByUid($uid);

        if (!$user) {
            return $this->error('User profile not found.', 404);
        }

        return $this->success($user);
    }
}
