<?php


namespace Igor\Api\Controllers;


use Igor\Api\Models\Example;

class ExampleController extends Controller
{
    private Example $example;

    public function __construct()
    {
        $this->example = new Example();
    }

    protected function ways($resource)
    {
        $ways = [
            'example_route' => ['index', 'none'],
            'example_show' => ['show', 'none']
        ];

        return $ways[$resource];
    }

    public function index()
    {
        return $this->example->index();
    }

    public function show($data)
    {
        return $this->example->authenticate($data);
    }
}