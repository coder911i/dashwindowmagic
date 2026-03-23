<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AcceptInviteController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function accept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $invite = DB::table('invites')
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->whereNull('accepted_at')
            ->first();

        if (!$invite) {
            return $this->error('Invalid or expired invite token.', 400);
        }

        try {
            $user = $this->authService->acceptInvite($request->all(), $invite);
            return $this->success($user, 'Account created successfully.', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
