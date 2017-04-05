<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=library_test', "root", "root");
require_once "src/Author.php";
require_once "src/Member.php";
require_once "src/Books.php";
class MemberTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Member::deleteAll();
    Author::deleteAll();
    Books::deleteAll();
  }
  function test_Save()
  {
    $newClass = new Member ("max", "blue");
    $newClass->save();
    $result = Member::getAll();
    $this->assertEquals($result, [$newClass]);
  }
  function test_deleteAll()
  {
    $newClass = new Member ("max","blue");
    $newClass->save();
    Member::deleteAll();
    $result = Member::getAll();
    $this->assertEquals($result, []);
  }
  function test_getAll()
  {
    $newClass = new Member ('max', 'blue');
    $newClass2 = new Member ('jack', "black");
    $newClass->save();
    $newClass2->save();
    $result = Member::getAll();
    $this->assertEquals($result, [$newClass, $newClass2] );
  }
  function test_deleteMember()
  {
    $newMember = new Member ("max", "black");
    $newMember->save();
    $newMember2=new Member ("jak", "Black");
    $newMember2->save();
    $newMember->deleteMember();
    $result = Member::getAll();
    $this->assertEquals($result, [$newMember2]);
  }
}






?>
