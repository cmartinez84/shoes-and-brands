<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";
    date_default_timezone_set('America/Los_Angeles');

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    use Symfony\Component\HttpFoundation\Request;
     Request::enableHttpMethodParameterOverride();

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
      return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=> Brand::getAll(), 'selected_store' =>null, 'selected_brands'=>null));
    });

    $app->post("/stores", function() use ($app) {
        $new_store = new Store(null, $_POST['name']);
        $new_store->save();
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=>Brand::getAll(), 'selected_store' =>null, 'selected_brands'=>null));
    });
    $app->get("/stores/{id}", function($id) use ($app) {
        $selected_store = Store::find($id);
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=>Brand::getAll(), 'selected_store'=>$selected_store, 'selected_brands'=>$selected_store->getBrands()));
    });

    $app->post("/stores/add/{id}", function($id) use ($app) {
        $selected_store = Store::find($id);
        $selected_store->addBrand($_POST['brand_id']);
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=>Brand::getAll(), 'selected_store'=>$selected_store, 'selected_brands'=>$selected_store->getBrands()));
    });
    $app->delete("/stores/remove/{id}", function($id) use ($app) {
        $selected_store = Store::find($id);
        $selected_store->removeBrand($_POST['brand_id']);
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=>Brand::getAll(), 'selected_store'=>$selected_store, 'selected_brands'=>$selected_store->getBrands()));
    });

    $app->delete("/stores/delete", function() use ($app) {
        $selected_store = Store::find($_POST['store_id']);
        $selected_store->delete();
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=>Brand::getAll(), 'selected_store'=>null, 'selected_brands'=>null));
    });
    $app->patch("/stores/update", function() use ($app) {
        $selected_store = Store::find($_POST['store_id']);
        $selected_store->update($_POST['name']);
        return $app['twig']->render('stores.html.twig', array('allStores' => Store::getAll(), 'allBrands'=>Brand::getAll(), 'selected_store'=>$selected_store, 'selected_brands'=>null));
    });
    $app->get("/brands", function() use ($app) {
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(), 'selected_brand'=>null));
    });
    $app->post("/brands", function() use ($app) {
        $new_brand = new Brand(null, $_POST['name']);
        $new_brand->save();
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(),'selected_brand'=>null));
    });

    $app->get("/brands/{id}", function($id) use ($app) {
        $selected_brand = Brand::find($id);
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(),'allStores' =>Store::getAll(), 'selected_brand'=>$selected_brand, 'selected_stores'=> $selected_brand->getStores()));
    });

    $app->patch("/brands/{id}", function($id) use ($app) {
        $selected_brand = Brand::find($id);
        $selected_brand->update($_POST['name']);
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(), 'allStores' =>Store::getAll(), 'selected_brand'=>$selected_brand,  'selected_stores'=> $selected_brand->getStores()));
    });

    $app->post("/brands/add_store/{id}", function($id) use ($app) {
        $selected_brand = Brand::find($id);
        $selected_brand->addStore($_POST['store_id']);
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(), 'allStores' =>Store::getAll(), 'selected_brand'=>$selected_brand,  'selected_stores'=> $selected_brand->getStores()));
    });

    $app->delete("/brands/remove_store/{id}", function($id) use ($app) {
        $selected_brand = Brand::find($id);
        $selected_brand->removeStore($_POST['store_id']);
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(), 'allStores' =>Store::getAll(), 'selected_brand'=>$selected_brand,  'selected_stores'=> $selected_brand->getStores()));
    });


    $app->delete("/brands/delete", function() use ($app) {
        $selected_brand = Brand::find($_POST['brand_id']);
        $selected_brand->delete();
      return $app['twig']->render('brands.html.twig', array('allBrands' =>Brand::getAll(),'allStores' =>Store::getAll(),'selected_brand'=>null, 'selected_stores' =>null));
    });


    return $app;
?>
