<?php

namespace AntoineD\Api\Models;

class User extends Model 
{

    
    public function __construct()
    {
        parent::__construct('users' , true, ['email', 'password'], ['email','password']);
    }


}