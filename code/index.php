<?php
session_start();

require ('../../includes/flight/flight/Flight.php');
require ('../../includes/Smarty/libs/Smarty.class.php');
require ('../../includes/pdo.php');
$db = new PDO
(
    // !! une seule ligne, sans espace !!
    "mysql:host=$adresse;port=$port;dbname=$base;charset=utf8",
    $user,
    $passwd
);

Flight::set('db',$db);



$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

Flight::register('view', 'Smarty', array(), function($smarty){
    $smarty->template_dir='./templates/';
    $smarty->compile_dir='./templates_c/';
    $smarty->config_dir='./config/';
    $smarty->cache_dir='./cache/';
});

Flight::map('render', function($template, $data){
    Flight::view()->assign($data);
    Flight::view()->display($template);
 });

 require ('fonctions.php');

 if(isset($_SESSION['user'])){
     Flight::view() -> assign('user',$_SESSION['user']);
 }

 



Flight::start();
