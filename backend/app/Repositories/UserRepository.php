<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByFirebaseUid(string $uid)
    {
        return User::where('firebase_uid', $uid)->first();
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }
}
