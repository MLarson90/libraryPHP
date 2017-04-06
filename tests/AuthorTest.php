<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=library_test', "root", "root");
require_once "src/Author.php";
require_once "src/Member.php";
require_once "src/Books.php";
class AuthorTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Author::deleteAll();
    Member::deleteAll();
    Books::deleteAll();

  }
    function test_Save()
    {
      $newAuthor = new Author ("max", "blue");
      $newAuthor->save();
      $result = Author::getAll();
      $this->assertEquals($result, [$newAuthor]);
    }

    function test_deleteAll()
    {
      $newClass = new Author ("max","blue");
      $newClass->save();
      Author::deleteAll();
      $result = Author::getAll();
      $this->assertEquals($result, []);
    }
    function test_getAll()
    {
      $newClass = new Author ('max', 'blue');
      $newClass2 = new Author ('jack', "black");
      $newClass->save();
      $newClass2->save();
      $result = Author::getAll();
      $this->assertEquals($result, [$newClass, $newClass2] );
    }
    function test_deleteAuthor()
    {
      $newAuthor = new Author ("max", "black");
      $newAuthor->save();
      $newAuthor2=new Author ("jak", "Black");
      $newAuthor2->save();
      $newAuthor->deleteAuthor();
      $result = Author::getAll();
      $this->assertEquals($result, [$newAuthor2]);
    }

  }





?>
