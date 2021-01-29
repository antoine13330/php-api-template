<?php


namespace Igor\Api\Models;


class Example extends Model
{
    public function index()
    {
        return send(['success' => 'congratulations! enjoy this template']);
    }
}