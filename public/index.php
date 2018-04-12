<?php

use App\Controllers\HomeController;
use App\Controller;
//use App\Models\HomeModel;

$error_reporting = 1; // 1 = errors 0 = none
if ($error_reporting == 1){
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);}

session_start();

$_SESSION["messages"] = null;
if (array_key_exists("messages", $_COOKIE)) {
}
else{
  $_COOKIE["messages"] = null;
}

require(__DIR__ . "/../vendor/autoload.php");

global $config;
$config = parse_ini_file(__DIR__ . "/../config/config.php");

$loader = new Twig_Loader_Filesystem(__DIR__ . "/../views/");
$twig = new Twig_Environment($loader);
$twig->addGlobal('config', $config);
require(__DIR__ . "/../app/View.php");

$router = new AltoRouter();
$router->setBasePath('');
$router->map('GET','/', "App\Controllers\HomeController#index", 'home');
$match = $router->match();


// not sure if code after this comment  is the best way to handle matched routes
list( $controller, $action ) = explode( '#', $match['target'] );
if ( is_callable(array($controller, $action)) ) {
    $obj = new $controller($twig);
    call_user_func_array(array($obj,$action), array($match['params']));
} else if ($match['target']==''){
    echo 'Error: no route was matched';
    //possibly throw a 404 error
} else {
    echo 'Error: can not call '.$controller.'#'.$action;
    //possibly throw a 404 error
}
