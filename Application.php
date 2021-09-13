<?php 

namespace app\core;

use Exception;

class Application
{
  public string $layout = 'main';
  public static $ROOT_PATH;
  public string $userClass;
  public Router $router;
  public Request $request;
  public Response $response;
  public Database $db;
  public View $view;
  public Session $session;
  public static $app;
  public ?DbModel $user;
  public ?Controller $controller = null;
  public function __construct($root_path, array $config)
  {
    $this->userClass = $config['userClass'];
    $this->response = new Response();
    self::$ROOT_PATH = $root_path;
    $this->request = new Request();
    $this->session = new Session();
    $this->router = new Router($this->request, $this->response);
    self::$app = $this;
    $this->db = new Database($config['db']);
    $this->view = new View();

    $primaryValue = $this->session->get('user');
    if($primaryValue){
      $primaryKey = (new $this->userClass())->primaryKey();
      $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
    }else{
      $this->user = null;
    }
    
    
  }
  public function run(){
    try{
      echo $this->router->resolve();
    }catch(Exception $e){
      $this->response->setStatusCode($e->getCode());
      echo $this->view->renderView('_error', ['exception' => $e]);
    }
    
  }

  public function getController(): Controller
  {
    return $this->controller;
  }
  public function setController(Controller $controller): void
  {
    $this->controller = $controller;
  }
  public function login(DbModel $user)
  {
    $this->user = $user;
    $primaryKey = $user->primaryKey();
    $primaryValue = $user->{$primaryKey};
    $this->session->set('user', $primaryValue);
    return true;
  }

  public function logout(){
    $this->user = null;
    $this->session->remove('user');
  }
  public static function isGuest(){
    return !self::$app->user;
  }

}