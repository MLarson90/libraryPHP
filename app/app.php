<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Books.php";
  require_once __DIR__.'/../src/Author.php';
  require_once __DIR__.'/../src/Member.php';
  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();
  $DB = new PDO('mysql:host=localhost:8889;dbname=library', 'root', 'root');
  $app['debug'] = true;
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig');
  });
  return $app;
 ?>
