<?php
namespace Jade;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Application {
    protected $route;
    public $prettyprint = false;
    public $cache = null;
    public function __construct($srcPath)
    {
        $request =explode("?", $_SERVER['REQUEST_URI'], 2);
        if ($request[0] == "/"){
            $this->route = '/';
        }
        else {
            $this->route = ltrim($request[0], '/');
        }
        spl_autoload_register(function($class) use($srcPath) {
            if (! strstr($class, 'Jade')) return;
            include($srcPath . str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php');
        });
    }
    public function action($path, \Closure $callback)
    {
        if ($path == $this->route || $path == '') {
            $jade = new Jade([
                'prettyprint' => $this->prettyprint,
                'cache' => $this->cache
            ]);
            $vars = $callback($path) ?: [];
            $jade->render($path. '.jade', $vars);
            die;
        }
    }
}
$app = new Application('../src/');
