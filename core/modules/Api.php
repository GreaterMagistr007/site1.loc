<?php

namespace core\modules;

class Api
{
    public function __construct()
    {
    }

    public function test()
    {
        $testClass = new Test();
        return $testClass->run();
    }
}