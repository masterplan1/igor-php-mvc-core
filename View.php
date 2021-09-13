<?php

namespace app\core;

class View
{
  public string $title = '';

  public function renderView($view, $params = []){
    
    $renderOnlyView = $this->renderOnlyView($view, $params);
    $layoutContent = $this->layoutContent();
    
    return str_replace('{{content}}', $renderOnlyView, $layoutContent);
  }
  public function renderContent($contentLayout){
    $layoutContent = $this->layoutContent();
    return str_replace('{{content}}', $contentLayout, $layoutContent);
  }
  protected function layoutContent(){
    $layout = Application::$app->layout;
    if(Application::$app->controller){
      $layout = Application::$app->controller->layout;
    }
    ob_start();
    include_once(Application::$ROOT_PATH."/views/layouts/$layout.php");
    return ob_get_clean();
  }
  protected function renderOnlyView($view, $params){

    foreach($params as $key => $val){
      $$key = $val;
    }
    ob_start();
    include_once(Application::$ROOT_PATH."/views/$view.php");
    return ob_get_clean();
  }
}