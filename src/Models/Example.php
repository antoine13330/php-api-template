<?php

namespace Igor\Api\Models;

use Igor\Api\Helpers\Http;
use Igor\Api\Middleware\AuthConfig;
use Igor\Api\Helpers\Validator;

class Example extends Model
{
    public function index()
    {
        return send(['success' => 'congratulations! enjoy this template']);
    }

    public function requestExample($data)
    {
        $response = Http::send('GET', 'http://example.com/');

        if(!is_array($response)){
            return send(json_decode($response));
        }else{
            return send(['error' => ['statusCode' => $response['StatusCode'], 'Message' => json_decode($response['Content'])]], $response['StatusCode']);
        }
    }

    public function authenticate($data)
    {

        $validator = Validator::refused(['email', 'password'], $data);
        if($validator) return $validator;

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