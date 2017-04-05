<?php
  class Author
{
    private $first;
    private $last;
    private $id;


    function __construct($first, $last, $id=null)
      {
        $this->first =$first;
        $this->last =$last;
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
      function getId()
      {
        return $this->id;
      }
      function save()
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO authors (first, last) VALUES ('{$this->getFirst()}', '{$this->getLast()}'); ");
          if($executed){
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          }else{
          return false;
      }
    }
    static function getAll()
      {
        $classes = array();
        $returned_classes = $GLOBALS['DB']->query('SELECT * FROM authors;');
        foreach($returned_classes as $class)
        {
          $newClass = new Author($class['first'], $class["last"],  $class["id"]);
          array_push($classes, $newClass);
        }

        return $classes;
      }
      static function deleteAll()
      {
        $deleteAll = $GLOBALS['DB']->exec("DELETE FROM authors;");
        if ($deleteAll)
        {
          return true;
        }else {
          return false;
        }
      }
      function deleteAuthor()
      {
        $executed=$GLOBALS['DB']->exec("DELETE FROM authors WHERE id={$this->getId()};");
        if ($executed){
          return true;
        } else{
          return false;
        }
      }
      static function findAuthor($search_author_id)
      {
        $new_Books = array();
        $returned_author= $GLOBALS['DB']->prepare("SELECT * FROM author WHERE author_id = :id");
        $returned_author->bindParam(':id', $search_author_id, PDO::PARAM_STR);
        $returned_author->execute();
        foreach($returned_author as $author){
          $first = $author['first'];
          $last = $author['last'];
          $author_id = $author['author_id'];
          $id = $author['id'];
          if($author_id == $search_author_id){
          $new_author= new Author($first,$last,$author_id, $id);
          array_push($new_Books,$new_author);
          }
        }
    }
  }

 ?>
