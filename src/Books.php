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
      function addTotalQuanity($quanity)
      {
          $executed = $GLOBALS['DB']->exec("INSERT INTO totalcopies (book_id, total) VALUES ({$this->getId()}, {$quanity});");
      }
      function addQuanity($quanity)
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO copies (book_id, quanity) VALUES ({$this->getId()}, {$quanity})");
        if($executed){
          return true;
        }else{
          return false;
        }
      }
      function checkout()
      {
        $executed = $GLOBALS['DB']->exec("UPDATE copies SET quanity = quanity -1 WHERE book_id = {$this->id}");
        if($exectued){
          return true;
        }else{
          return false;
        }
      }
      function return()
      {
        $executed = $GLOBALS['DB']->exec("UPDATE copies SET quanity = quanity +1 WHERE book_id = {$this->id}");
        if($exectued){
          return true;
        }else{
          return false;
        }
      }
      function findAuthors(){
        $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN books_authors ON (books.id = books_authors.book_id)JOIN authors ON(books_authors.author_id = authors.id) WHERE books.id = {$this->getId()};");
        $authors = array();
        foreach($returned_authors as $author){
          $newAuthor = new Author($author['first'], $author['last'],$author['id']);
          array_push($authors, $newAuthor);
        }
        return $authors;
      }
      function findQuanity(){
        $query = $GLOBALS['DB']->query("SELECT quanity FROM copies WHERE book_id = {$this->getId()};");
        $quanitys = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($quanitys as $quanity){
          return $quanity;
        }
      }
      function findTotalQuanity(){
        $query = $GLOBALS['DB']->query("SELECT total FROM totalcopies WHERE book_id = {$this->getId()};");
        $quanitys = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($quanitys as $quanity){
          return $quanity;
        }
      }
      static function findbookByTitle($title)
      {
        $returned_books = $GLOBALS['DB']->prepare("SELECT * FROM books WHERE title = :title");
        $returned_books->bindParam(':title', $title, PDO::PARAM_STR);
        $returned_books->execute();
        foreach($returned_books as $book){
          $id=$book['title'];
          if($id == $title){
          $newAuthor = new Books($book['title'],$book['location'], $book['id']);
          return $newAuthor;
        }
        }
      }
      function update_title($new_title)
      {
          $executed = $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
          if($executed){
            $this->setFirst($new_name);
            return true;
          }else{
            return false;
          }
      }
      function update_last($new_location)
      {
          $executed = $GLOBALS['DB']->exec("UPDATE books SET location = '{$new_location}' WHERE id = {$this->getId()};");
          if($executed){
            $this->setLast($new_location);
            return true;
          }else{
            return false;
          }
      }
      static function findBookbyId($id)
      {

        $returned_books = $GLOBALS['DB']->prepare("SELECT * FROM books WHERE id = :id");
        $returned_books->bindParam(':id', $id, PDO::PARAM_STR);
        $returned_books->execute();
        foreach($returned_books as $book){
          $bookid=$book['id'];
          if($bookid == $id){
          $book = new Books($book['title'],$book['location'], $book['id']);
            return $book;
        }
        }
      }
    }

 ?>
