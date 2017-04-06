<?php
  class Books
{
    private $title;
    private $location;
    private $id;


    function __construct($title, $location, $id=null)
      {
        $this->title =$title;
        $this->location =$location;
        $this->id = $id;
      }
      function getTitle()
      {
        return $this->title;
      }
      function setTitle($new_title)
      {
         $this->title = $new_title;
      }
      function getLocation()
      {
        return $this->location;
      }
      function setLocation($new_location)
      {
         $this->location = $new_location;
      }
      function getId()
      {
        return $this->id;
      }
      function save()
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO books (title, location) VALUES ('{$this->getTitle()}', '{$this->getLocation()}'); ");
          if($executed){
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          }else{
          return false;
      }
    }
    static function getAll()
      {
        $books = array();
        $returned_books = $GLOBALS['DB']->query('SELECT * FROM books;');
        foreach($returned_books as $book)
        {
          $newBook = new Books($book['title'], $book["location"],  $book["id"]);
          array_push($books, $newBook);
        }
        return $books;
      }
      static function deleteAll()
      {
        $deleteAll = $GLOBALS['DB']->exec("DELETE FROM books;");
        if ($deleteAll){
          return false;
        }
        $executed = $GLOBALS['DB']->exec("DELETE FROM books_authors;");
      if (!$executed){
        return false;
      }else{
        return true;
      }
      }
      function deleteBook()
      {
        $executed=$GLOBALS['DB']->exec("DELETE FROM books WHERE id={$this->getId()};");
        if ($executed){
          return true;
        } else{
          return false;
        }
      }
      function addAuthor($authorId)
      {
        $executed=$GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->getId()}, {$authorId->getId()});");
        if($executed){
          return true;
        } else{
          return false;
        }
      }
      function findAuthors(){
        $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN books_authors ON (book.id = books_authors.book_id)JOIN authors ON(books_authors.author_id=author.id) WHERE book.id = {$this->getId()};");
        $authors = array();
        foreach($returned_authors as $author){
          $newAuthor = new Author($author['first'], $author['last'],$author['id']);
          array_push($authors, $newAuthor);
        }
        return $authors;
      }
    }

 ?>
