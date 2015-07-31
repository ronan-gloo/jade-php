<?php

require 'bootstrap.php';

/**
 * formatted output
 */
$app->prettyprint = true;

/**
 * for cache
 */
$app->cache = "cache"; //Specify the directory

/**
 * Home pages list
 */
$app->action('/', function(&$view)
{
    $view  = 'index';
    $items = array(
        array('route' => 'login',  'name' => 'The Login page example'),
        array('route' => 'cities', 'name' => 'The City API search example'),
    );
    return compact('items');
});

/**
 * Basic user form
 */
$app->action('login', function()
{
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    $message['error']   = !! $_POST;
    $message['success'] = false;

    if (trim($username) && trim($password)){
        $message['error']   = false;
        $message['success'] = true;
    }

    return array(
        'message'=> $message,
        'user'   => compact('username', 'password', 'invalid')
    );
});

/**
 * Cities list search
 */
$app->action('cities', function()
{
    $query  = isset($_GET['q']) ? trim($_GET['q']) : null;
    $cities = ['total' => 0, 'result' => null];

    if ($query)
    {
        $ch = curl_init('http://gd.geobytes.com/AutoCompleteCity?q=' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $cities['result'] = json_decode(curl_exec($ch));
        $cities['total']  = count($cities['result']);
    }

    return compact('cities', 'query');
});

/**
 * Catch routing error
 */
$app->action('', function(&$view)
{
    header("Status: 404 Not Found");
    $view  = 'error';
    return array(
        'message'=> 'Error: 404'
    );
});
