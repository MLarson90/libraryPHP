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
          $returned_author= $GLOBALS['DB']->prepare("SELECT * FROM authors WHERE id = :id");
          $returned_author->bindParam(':id', $search_author_id, PDO::PARAM_STR);
          $returned_author->execute();
          foreach($returned_author as $author){
            $first = $author['first'];
            $last = $author['last'];
            $id = $author['id'];
          if($id == $search_author_id){
            $new_author = new Author($first,$last, $id);
            return $new_author;
          }
        }
    }
    function findBooks(){
      $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM authors join books_authors ON (authors.id = books_authors.book_id) JOIN books ON(books_authors.book_id = books.id) WHERE authors.id = {$this->getId()};");
      $books = array();
      foreach($returned_books as $book){
        $newbook = new Books ($book['title'], $book['location'], $book['id']);
        array_push($books, $newbook);
      }
      return $books;
    }
    function update_first($new_first)
    {
        $executed = $GLOBALS['DB']->exec("UPDATE authors SET first = '{$new_first}' WHERE id = {$this->getId()};");
        if($executed){
          $this->setFirst($new_name);
          return true;
        }else{
          return false;
        }
    }
    function update_last($new_last)
    {
        $executed = $GLOBALS['DB']->exec("UPDATE authors SET last = '{$new_last}' WHERE id = {$this->getId()};");
        if($executed){
          $this->setLast($new_last);
          return true;
        }else{
          return false;
        }
    }
    static function findAuthorByName($last)
    {
      $authors = array();
      $returned_authors = $GLOBALS['DB']->prepare("SELECT * FROM authors WHERE last = :last");
      $returned_authors->bindParam(':last', $last, PDO::PARAM_STR);
      $returned_authors->execute();
      foreach($returned_authors as $author){
        $id=$author['last'];
        if($id == $last){
        $newAuthor = new Author($author['first'],$author['last'], $author['id']);
        array_push($authors,$newAuthor);
      }
      }
      return $authors;
    }

  }

 ?>
