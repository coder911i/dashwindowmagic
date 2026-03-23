<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Str;
use Exception;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new Agency (Admin).
     */
    public function registerAgency(array $data)
    {
        $tenantId = 'T-' . strtoupper(Str::random(8));
        
        // 1. Create Firebase User
        $firebaseUser = Firebase::auth()->createUser([
            'email' => $data['email'],
            'password' => $data['password'],
            'displayName' => $data['name'],
        ]);

        // 2. Set Custom Claims
        Firebase::auth()->setCustomUserClaims($firebaseUser->uid, [
            'tenantId' => $tenantId,
            'role' => 'admin'
        ]);

        // 3. Create Firestore Tenant Document
        Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->set([
                'name' => $data['agency_name'] ?? $data['name'],
                'owner_name' => $data['name'],
                'email' => $data['email'],
                'city' => $data['city'] ?? '',
                'phone' => $data['phone'] ?? '',
                'createdAt' => new \DateTime(),
            ]);

        // 4. Create Local User
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'firebase_uid' => $firebaseUser->uid,
            'tenant_id' => $tenantId,
            'role' => 'admin',
            'agency_name' => $data['agency_name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'city' => $data['city'] ?? '',
        ]);
    }

    /**
     * Handle Agent Signup from Invite.
     */
    public function acceptInvite(array $data, $invite)
    {
        // 1. Create Firebase User
        $firebaseUser = Firebase::auth()->createUser([
            'email' => $invite->email,
            'password' => $data['password'],
            'displayName' => $data['name'],
        ]);

        // 2. Set Custom Claims
        Firebase::auth()->setCustomUserClaims($firebaseUser->uid, [
            'tenantId' => $invite->tenant_id,
            'role' => 'agent'
        ]);

        // 3. Create Local User
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $invite->email,
            'firebase_uid' => $firebaseUser->uid,
            'tenant_id' => $invite->tenant_id,
            'role' => 'agent',
        ]);

        // 4. Mark invite as accepted
        \Illuminate\Support\Facades\DB::table('invites')
            ->where('id', $invite->id)
            ->update(['accepted_at' => now()]);

        return $user;
    }

    /**
     * Sync/Get User Profile.
     */
    public function getProfileByUid(string $uid)
    {
        return $this->userRepository->findByFirebaseUid($uid);
    }
}
