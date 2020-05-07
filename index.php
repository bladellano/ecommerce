<?php

session_start();

require_once "vendor/autoload.php";

use \Hcode\Page;
use \Hcode\PageAdmin;
use \Slim\Slim;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function () {
    $page = new Page();
    $page->setTpl("index");
});

$app->get('/admin', function () {

	User::verifyLogin();

    $page = new PageAdmin();
    $page->setTpl("index");
});

$app->get('/admin/login', function () {
    $page = new PageAdmin([
        "header" => false,
        "footer" => false,
    ]);
    $page->setTpl("login");
});

$app->post('/admin/login', function () {

	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});

$app->get('/admin/logout',function(){
	User::logout();
	header("Location: /admin/login");
	exit;
});

//User-lista
$app->get("/admin/users",function(){

    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();

    $page->setTpl("users",array(
        "users"=>$users
    ));

}); 

//User-create-formulário
$app->get("/admin/users/create",function(){
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("users-create");

});

//User-delete
$app->get("/admin/users/:iduser/delete",function($iduser){
    User::verifyLogin();
});

//User-update-carregamento
$app->get("/admin/users/:iduser",function($iduser){
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("users-update");

});

//User-create-insere
$app->post("/admin/users/create",function(){

    User::verifyLogin();

    $user = new User();

    $user->setData($_POST);

    var_dump($user);

});

//User-update-atualiza
$app->post("/admin/users/:iduser",function($iduser){
    User::verifyLogin();

});


$app->run();
