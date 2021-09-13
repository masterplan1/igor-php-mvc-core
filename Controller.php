<?php
/**
 * @var app\core\middlewares\BasicMiddleware[]
 */
namespace app\core;

class Controller
{
  public string $layout = 'main';
  public array $middlewares = [];
  public string $action = '';

  public function render($view, $params = []){
    return Application::$app->view->renderView($view, $params);
  }

  public function setLayout($layout){
    $this->layout = $layout;
  }

  public function registerMiddleware($middleware)
  {
    $this->middlewares[] = $middleware;
  }

  public function getMiddlewares(){
    return $this->middlewares;
  }
}