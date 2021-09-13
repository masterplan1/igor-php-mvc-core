<?php

namespace app\core;

use app\models\User;

abstract class DbModel extends Model
{
  abstract public function tableName() : string;
  abstract public function attributes() : array;
  abstract public function primaryKey() : string;
  public function save()
  {
    $tableName = $this->tableName();
    $attributes = $this->attributes();
    $params = array_map(fn($attr) => ":$attr", $attributes);

    $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).")
           VALUES(".implode(',', $params).")");
    // echo '<pre>';
    // var_dump($statement, $attributes, $params);exit;
    foreach($attributes as $attribute){
      $statement->bindValue(":$attribute", $this->{$attribute});
    }
    $statement->execute();
    return true;
  }

  public static function prepare($sql)
  {
    return Application::$app->db->pdo->prepare($sql);
  }

  public static function findOne($where){
    $user = new Application::$app->userClass();
    $tableName = $user->tableName();
    $attributes = array_keys($where);
    $sql = implode('AND ', array_map(fn($attr) => "$attr = :$attr", $attributes));
    // SELECT * WHERE email = :email AND firstname = :firstname

    $statement = self::prepare("SELECT * FROM $tableName WHERE ". $sql);
    foreach($where as $key => $val){
      $statement->bindValue(":$key", $val);
    }
    $statement->execute();
    return $statement->fetchObject(static::class);
  }
} 