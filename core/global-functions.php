<?php
const DS = DIRECTORY_SEPARATOR;

function dd(...$vars)
{
    foreach ($vars as $var) {
        pretty_dump($var);
    }
    die();
}

function pretty_dump(...$vars)
{
    $k = debug_backtrace();
    $calledFrom = $k[0] ? $k[0] : false;
    if (isset($k[1])) {
        $calledFrom = $k[1];
    }
    echo('<div class="pretty_dump">');


    if ($calledFrom && is_array($calledFrom)) {
        echo('<p>Called from:</p>');
        echo('<p><b>' . $calledFrom['file'] . ':' . $calledFrom['line'] . '</b></p>');
        echo('<p></p>');
    }

    foreach ($vars as $var) {
        echo('<pre>');
        var_dump($var);
        echo('</pre>');
    }

    echo('</div>');
    echo('<style>
        .pretty_dump {
            background-color: #18171B!important;
            color: #56DB3A!important;
            padding: 5px;
            margin-top: 5px;
        }
        .pretty_dump p {
            padding: 2px;
            margin: 0px;
        }

</style>');
}

function dump(...$vars)
{
    foreach ($vars as $var) {
        pretty_dump($var);
    }
}

function core_dir($path = '')
{
    $result = __DIR__;
    if ($path) {
        if ($path[0] !== '\\') {
            $result .= '\\';
        }
    }
    return $result . $path;
}

function site_dir($path = '')
{
    $result = explode('\\', core_dir());
    array_pop($result);

    $result = implode('\\', $result);

    if ($path) {
        if ($path[0] !== '\\') {
            $result .= '\\';
        }
    }
    return $result . $path;
}

function template_dir($path = '')
{
    $result = '';
    if ($path) {
        if ($path[0] !== '\\') {
            $result .= '\\';
        }
    }

    $result .= $path;

    return site_dir(DS . 'template' . $result);
}

function data_dir($path = '')
{
    $result = '';
    if ($path) {
        if ($path[0] !== '\\') {
            $result .= '\\';
        }
    }

    $result .= $path;

    return site_dir(DS . 'data' . $result);
}

spl_autoload_register(function ($class) {
    $fileName = site_dir($class . '.php');
    include_once ( $fileName );
});

function redirect($uri = '/', $code = 302) {

    if (mb_substr(mb_strtolower($uri), 0, 4) === 'http') {
        $newLocation = $uri;
    } else {
        $newLocation = \core\modules\Request::href($uri);
    }

    header("Location $newLocation", true, $code);
    exit();
}


/**
 * @param string $template
 * @param array $variables
 * @return \core\modules\View
 */
function render(string $template = '', array $variables = []) {
    $view = new \core\modules\View($template, $variables);
    return $view;
}