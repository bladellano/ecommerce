<?php

use \Hcode\Model\Products;

use \Hcode\Model\User;

use \Hcode\PageAdmin;

$app->get("/admin/products",function(){

    User::verifyLogin();
    
    $products = Products::listAll();

    $page = new PageAdmin();

    $page->setTpl("products",array(
        "products"=>$products
    ));    
});

$app->get("/admin/products/create",function(){

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");

});

$app->post("/admin/products/create",function(){

    User::verifyLogin();

    $product = new Products();

    $product->setData($_POST);

    $product->save();

    header("Location:/admin/products");
    exit;


});


