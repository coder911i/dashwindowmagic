<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class InviteController extends Controller
{
    use ApiResponse;

    public function invite(Request $request)
    {
        if ($request->attributes->get('role') !== 'admin') {
            return $this->error('Access denied. Admin only.', 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $token = Str::random(40);
        $tenantId = $request->attributes->get('tenantId');

        DB::table('invites')->insert([
            'email' => $request->email,
            'token' => $token,
            'tenant_id' => $tenantId,
            'role' => 'agent',
            'expires_at' => now()->addDays(7),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->success(['token' => $token], 'Invite sent successfully.');
    }
}
