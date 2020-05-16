<?php

use Hcode\Page;
use Hcode\Model\Category;
use Hcode\Model\Products;

$app->get('/', function () {

    $products = Products::listAll();    

    $page = new Page();
    
    $page->setTpl("index",[
        'products'=> Products::checkList($products)]);
});

$app->get("/categories/:idcategory",function($idcategory){

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

    $category = new Category();

    $category->get((int)$idcategory);

    $pagination = $category->getProductsPage($page);

//    echo '<pre>'; var_dump($pagination);exit;
    $pages = [];
    
    for ($i=1; $i <= $pagination['pages']; $i++) { 
        array_push($pages,[
            'link' => '/categories/'.$category->getidcategory().'?page='.$i,
            'page' => $i
        ]);
    }
    // echo '<pre>'; var_dump($pages);exit;

    $page = new Page();

    $page->setTpl("category",[
        'category'=> $category->getValues(),
        'products'=> $pagination['data'],
        'pages'=>$pages
    ]);

});
