<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=library_test', "root", "root");
require_once "src/Author.php";
require_once "src/Member.php";
require_once "src/Books.php";
class BooksTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Member::deleteAll();
    Author::deleteAll();
    Books::deleteAll();
  }
    function test_Save()
    {
      $newBook = new Books ("max", "blue");
      $newBook->save();
      $result = Books::getAll();
      $this->assertEquals($result, [$newBook]);
    }

    function test_deleteAll()
    {
      $newClass = new Books ("max","blue");
      $newClass->save();
      Books::deleteAll();
      $result = Books::getAll();
      $this->assertEquals($result, []);
    }
    function test_getAll()
    {
      $newClass = new Books ('max', 'blue');
      $newClass2 = new Books ('jack', "black");
      $newClass->save();
      $newClass2->save();
      $result = Books::getAll();
      $this->assertEquals($result, [$newClass, $newClass2] );
    }
    function test_deleteBook()
    {
      $newBook = new Books ("max", "black");
      $newBook->save();
      $newBook2=new Books ("jak", "Black");
      $newBook2->save();
      $newBook->deleteBook();
      $result = Books::getAll();
      $this->assertEquals($result, [$newBook2]);
    }
    function test_addAuthor()
    {
      $newBook = new Books ("Cannery Row", "E20");
      $newBook->save();
      $newAuthor = new Author ("Jack", "London");
      $newAuthor->save();
      $newBook->addAuthor($newAuthor);
      $result = $newBook->findAuthors();
      $this->assertEquals($result, [$newAuthor]);
    }

  }






?>
