<?php

use core\modules\Container;

/**
 * @param $module
 * @return Container|mixed
 */
function container($module = '')
{
    if ($module) {
        return $_SERVER['container']->load($module);
    }
    return $_SERVER['container'];
}

/**
 * @return \core\modules\Request
 */
function request()
{
    return container('Request');
}
$request = request();

function href($path)
{
    return request()->href($path);
}