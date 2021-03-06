<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";
    require_once "src/Store.php";


    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class BrandTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown(){
                Brand::deleteAll();
                Store::deleteAll();
        }
        function test_getName()
        {
            //Arange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);

            //Act
            $result = $test_brand->getName();

            //Assert
            $this->assertEquals($name, $result);
        }
        function test_getId()
        {
            //Arange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();

            //Act
            $result = $test_brand->getName();

            //Assert
            $this->assertEquals($name, $result);

        }
        function test_save()
        {
            //Arange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand], $result);

        }
        function test_getAll()
        {
            //Arange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();

            $id2 = null;
            $name2 = "Nike";
            $test_brand2 = new Brand($id2, $name2);
            $test_brand2->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand,$test_brand2], $result);
        }
        function test_deleteAll()
        {
            //Arange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();

            $id2 = null;
            $name2 = "Nike";
            $test_brand2 = new Brand($id2, $name2);
            $test_brand2->save();

            //Act
            Brand::deleteAll();
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([], $result);
        }
        function test_delete()
        {
            //Arrange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();
            $test_brand->delete();

            $id2 = null;
            $name2 = "Nike";
            $test_brand2 = new Brand($id2, $name2);
            $test_brand2->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand2], $result);
        }
        function test_find()
        {
            //Arrange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();
            $test_brand_id = $test_brand->getId();
            //Act
            $result = Brand::find($test_brand_id);
            //Assert
            $this->assertEquals($test_brand, $result);
        }
        function test_update(){
            //Arrange
            $id = null;
            $name = "Nike";
            $test_brand = new Brand($id, $name);
            $test_brand->save();
            $test_brand_id = $test_brand->getId();

            //Act
            $new_name = "Adidas";
            $test_brand->update($new_name);
            $altered_brand = Brand::find($test_brand_id);
            $result = $altered_brand->getName();

            //Assert
            $this->assertEquals($new_name, $result);
        }
        function test_addStore()
        {
            //Arrange
            $id = null;
            $brand_name = "Nike";
            $test_brand = new Brand($id, $brand_name);
            $test_brand->save();
            $store_name = "Shoe Store";
            $test_store = new Store($id, $store_name);
            $test_store->save();
            $test_store_id = $test_store->getId();

            //Act
            $test_brand->addStore($test_store_id);

            $result = $test_brand->getStores();

            //Assert
            $this->assertEquals([$test_store], $result);
        }
        function test_getStores()
        {
            //Arrange
            $id = null;
            $brand_name = "Nike";
            $test_brand = new Brand($id, $brand_name);
            $test_brand->save();

            $store_name = "Shoe Store";
            $test_store = new Store($id, $store_name);
            $test_store->save();
            $test_store_id = $test_store->getId();

            $store_name2 = "Shoe Store";
            $test_store2 = new Store($id2, $store_name2);
            $test_store2->save();
            $test_store_id2 = $test_store2->getId();


            //Act
            $test_brand->addStore($test_store_id);
            $test_brand->addStore($test_store_id2);


            $result = $test_brand->getStores();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);

        }
        function test_deleteStore()
        {
            //Act
            $id = null;
            $brand_name = "Nike";
            $test_brand = new Brand($id, $brand_name);
            $test_brand->save();

            $store_name = "Shoe Store";
            $test_store = new Store($id, $store_name);
            $test_store->save();
            $test_store_id = $test_store->getId();

            $store_name2 = "Shoe Store";
            $test_store2 = new Store($id2, $store_name2);
            $test_store2->save();
            $test_store_id2 = $test_store2->getId();

            $test_brand->addStore($test_store_id);
            $test_brand->addStore($test_store_id2);

            //Act
            $test_brand->removeStore($test_store_id);

            $result = $test_brand->getStores();

            //Assert
            $this->assertEquals([$test_store2], $result);

        }
    }
?>
