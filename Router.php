<?php 

namespace app\core;

use app\core\exception\NotFoundException;

class Router
{
  public Request $request;
  public Response $response;
  protected $routes = [];

  public function __construct(\app\core\Request $request, \app\core\Response $response) 
  {
    $this->request = $request;
    $this->response = $response;
  }
  public function get($path, $callback){
     $this->routes['get'][$path] = $callback;
  }
  public function post($path, $callback){
    $this->routes['post'][$path] = $callback;
 }
  public function resolve(){
    // echo '<pre>';
    // print_r($this->routes);
    // echo '</pre>';
    $path = $this->request->getPath();
    $method = $this->request->method();
    $callback = $this->routes[$method][$path] ?? false;
    if($callback === false){
      // Application::$app->response->setStatusCode(404);
      
      throw new NotFoundException();
      // return $this->renderContent("Not found");
    }
    if(is_string($callback)){
      return Application::$app->view->renderView($callback);
    }
    if(is_array($callback)){
      $controller = new $callback[0]();
      Application::$app->controller = $controller;
      
      $controller->action = $callback[1];
      $callback[0] = $controller;

      foreach($controller->getMiddlewares() as $middleware){
        $middleware->execute();
      }
    }
    return call_user_func($callback, $this->request, $this->response);
  }

  public function renderView($view, $params = []){
    return Application::$app->view->renderView($view, $params);
  }
  public function renderContent($contentLayout){
    return Application::$app->view->renderContent($contentLayout);
  }
}