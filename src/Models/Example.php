<?php

namespace Igor\Api\Models;

use Igor\Api\Middleware\AuthConfig;

class Example extends Model
{
    public function index()
    {
        return send(['success' => 'congratulations! enjoy this template']);
    }

    public function authenticate($data)
    {
        if(!$this->verifyUserInDatabase($data['id'])){
            return send(['error' => 'user not found'], 404);
        }

        $token = AuthConfig::auth($data['id']);
        return send(['token' => $token]);
    }

    private function verifyUserInDatabase(int $id)
    {
        return true;
    }
}