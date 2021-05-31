<?php

use \Hcode\Model\Products;

use \Hcode\Model\User;

use \Hcode\PageAdmin;

$app->get("/admin/products", function () {

    User::verifyLogin();

    $search = (isset($_GET['search'])) ? $_GET['search'] : "";
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

    if ($search != '') {
        $pagination = Products::getPageSearch(trim($search), $page);
    } else {
        $pagination = Products::getPage($page);
    }

    $pages = [];

    for ($x = 0; $x <  $pagination['pages']; $x++) {
        array_push($pages, [
            'href' => '/admin/products?' . http_build_query([
                'page' => $x + 1,
                'search' => $search
            ]),
            'text' => $x + 1
        ]);
    }

    $page = new PageAdmin();

    $page->setTpl("products", array(
        "products" => $pagination['data'],
        "search" => $search,
        "pages" => $pages
    ));
});

$app->get("/admin/products/create", function () {

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("products-create");
});

//STORE
$app->post("/admin/products/create", function () {

    User::verifyLogin();

    $data = filter_var_array($_POST, FILTER_SANITIZE_STRIPPED);
    $image = new CoffeeCode\Uploader\Image("storaged", "images");

    $oProduct =  new Hcode\Model\ProductDataLayer();

    /* SETA OS ATRIBUTOS */
    foreach ($data as $key => $value)
        $oProduct->{$key} = $value;
    $oProduct->dtregister = date('Y-m-d H:m:s');

    $saved = $oProduct->save();

    if (!$saved) {
        flash('error', 'Preencha todos os campos corretamente');
        header("Location:/admin/products/create");
        exit;
    }

    /* LIMITA ATÉ 5 ARQUIVOS */
    if (count($_FILES['images']['name']) > 5) {
        flash('error', 'No máxima até 5 imagens');
        header("Location:/admin/products/create");
        exit;
    }

    /* PEGANDO ULTIMO REGISTRO */
    $products = $oProduct->find()->limit(1)->order("idproduct DESC")->fetch();
    $id = $products->data()->idproduct;

    if (!$_FILES['images']['error'] || count($_FILES['images']['name']) > 1) {

        try {
            foreach ($image->multiple("images", $_FILES) as $file) {

                $basename = bin2hex(random_bytes(8));
                $path_file = $image->upload($file, $basename, 1200);
                $thumb_path_file = $image->upload($file, $basename, 300);

                $oProductImages =  new Hcode\Model\ProductImagesDataLayer();
                $oProductImages->product_id = $id;
                $oProductImages->image = $path_file;
                $oProductImages->thumb_image = $thumb_path_file;
                $oProductImages->save();
            }
        } catch (Exception $e) {
            flash('error', $e->getMessage());
            header("Location:/admin/products/create");
            exit;
        }
    }

    header("Location:/admin/products");
    exit;
});

$app->get("/admin/products/:idproduct", function ($idproduct) {

    User::verifyLogin();

    $product = new Products();

    $product->get((int)$idproduct);

    $images = (new Hcode\Model\ProductImagesDataLayer())
        ->find("product_id = :product_id", "product_id={$idproduct}")->fetch(true) ?? [];

    $aImages = [];
    foreach ($images as $image)
        $aImages[] = (array) $image->data();

    $page = new PageAdmin();

    $page->setTpl("products-update", [
        'product' => $product->getValues(),
        'images' => $aImages
    ]);
});

//UPDATE
$app->post("/admin/products/:idproduct", function ($idproduct) {

    User::verifyLogin();

    $product = new Products();

    $product->get((int)$idproduct);

    $image = new CoffeeCode\Uploader\Image("storaged", "images");

    if (!$_FILES['images']['error'] || count($_FILES['images']['name']) > 1) {

        try {
            foreach ($image->multiple("images", $_FILES) as $file) {

                $basename = bin2hex(random_bytes(8));
                $path_file = $image->upload($file, $basename, 1200);
                $thumb_path_file = $image->upload($file, $basename, 300);

                $oProductImages =  new Hcode\Model\ProductImagesDataLayer();
                $oProductImages->product_id = (int)$idproduct;
                $oProductImages->image = $path_file;
                $oProductImages->thumb_image = $thumb_path_file;
                $oProductImages->save();
            }
        } catch (Exception $e) {
            flash('error', $e->getMessage());
            header("Location:/admin/products/create");
            exit;
        }
    }

    $product->setData($_POST);
    $product->save();

    flash('success', "Produto atualizado com sucesso");
    header("Location:/admin/products/{$idproduct}");
    exit;
});


$app->get("/admin/products/:idimage/delete/image", function ($idimage) {

    $image = (new Hcode\Model\ProductImagesDataLayer())
        ->find("id = :id", "id={$idimage}")->fetch();

    $idproduct = $image->product_id;

    if ($image->destroy()) {
        @unlink($image->data()->image);
        @unlink($image->data()->thumb_image);
    }

    header("Location:/admin/products/" .  $idproduct);
    exit;
});

$app->get("/admin/products/:idproduct/delete", function ($idproduct) {

    User::verifyLogin();

    $product = new Products();

    $product->get((int)$idproduct);

    $images = (new Hcode\Model\ProductImagesDataLayer())
        ->find("product_id = :product_id", "product_id={$idproduct}")->fetch(true);

    if ($images) {
        foreach ($images as $value) {
            @unlink($value->data()->image);
            @unlink($value->data()->thumb_image);
        }
    }

    $product->delete();

    header("Location:/admin/products");
    exit;
});
