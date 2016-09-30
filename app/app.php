<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";
    date_default_timezone_set('America/Los_Angeles');

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    use Symfony\Component\HttpFoundation\Request;  Request::enableHttpMethodParameterOverride();

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost:8889;dbname=shoes';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
      return $app['twig']->render('home.html.twig');
    });

    $app->get("/stores", function() use ($app) {
      return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'selected_store' =>null, 'selected_brands'=>null));
    });

    $app->post("/stores", function() use ($app) {
        $new_store = new Store(null, $_POST['name']);
        $new_store->save();
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'selected_store' =>null, 'selected_brands'=>null));
    });
    $app->get("/stores/{id}", function($id) use ($app) {
        $selected_store = Store::find($id);
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'selected_store'=>$selected_store, 'selected_brands'=>null));
    });
    $app->delete("/stores/delete", function() use ($app) {
        $selected_store = Store::find($_POST['store_id']);
        $selected_store->delete();
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'selected_store'=>null, 'selected_brands'=>null));
    });
    $app->patch("/stores/update", function() use ($app) {
        $selected_store = Store::find($_POST['store_id']);
        $selected_store->update($_POST['name']);
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'selected_store'=>$selected_store, 'selected_brands'=>null));
    });


    return $app;
?>
