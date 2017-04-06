<?php
  class Member
{
    private $first;
    private $last;
    private $username;
    private $password;
    private $id;


    function __construct($first, $last, $username, $password, $id=null)
      {
        $this->first =$first;
        $this->last =$last;
        $this->username = $username;
        $this->password = $password;
        $this->id = $id;
      }
      function getFirst()
      {
        return $this->first;
      }
      function setFirst($new_first)
      {
         $this->first = $new_first;
      }
      function getLast()
      {
        return $this->last;
      }
      function setLast($new_last)
      {
         $this->last = $new_last;
      }
      function getUsername()
      {
          return $this->username;
      }
      function setUsername($new_name)
      {
        $this->username = $new_name;
      }
      function getPassword()
      {
        return $this->password;
      }
      function setPassword($new_pass)
      {
        $this->password = $new_pass;
      }
      function getId()
      {
        return $this->id;
      }
      function save()
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO patrons (first, last, username, password) VALUES ('{$this->getFirst()}', '{$this->getLast()}','{$this->getUsername()}','{$this->getPassword()}'); ");
          if($executed){
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          }else{
          return false;
          }
      }
      static function getAll()
      {
        $patrons = array();
        $returned_patrons = $GLOBALS['DB']->query('SELECT * FROM patrons;');
        foreach($returned_patrons as $patron){
          $newPatron = new Member($patron['first'], $patron["last"],  $patron["id"]);
          array_push($patrons, $newPatron);
        }
          return $patrons;
      }
      static function login($username, $password)
      {
        $login = $GLOBALS['DB']->prepare("SELECT * FROM patrons WHERE username = :username;");
        $login->bindParam(':username', $username, PDO::PARAM_STR);
        $login->execute();
        foreach($login as $check){
          $user = $check['username'];
          $pass = $check['password'];
        if(($user == $username) && ($pass == $password)){
          return true;
        }else{
          return false;
          }
        }
      }
      static function deleteAll()
      {
        $deleteAll = $GLOBALS['DB']->exec("DELETE FROM patrons;");
        if ($deleteAll)
        {
          return true;
        }else {
          return false;
        }
      }
      function deleteMember()
      {
        $executed=$GLOBALS['DB']->exec("DELETE FROM patrons WHERE id={$this->getId()};");
        if ($executed){
          return true;
        } else{
          return false;
        }
      }
      function update_first($new_first)
      {
          $executed = $GLOBALS['DB']->exec("UPDATE patrons SET first = '{$new_first}' WHERE id = {$this->getId()};");
          if($executed){
            $this->setFirst($new_name);
            return true;
          }else{
            return false;
          }
      }
      function update_last($new_last)
      {
          $executed = $GLOBALS['DB']->exec("UPDATE patrons SET last = '{$new_last}' WHERE id = {$this->getId()};");
          if($executed){
            $this->setLast($new_last);
            return true;
          }else{
            return false;
          }
      }
}





 ?>
