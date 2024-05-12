<?php 
session_start();
echo "<!DOCTYPE html><html lang='en'><head>";
require_once '.config/_init.php';
require_once '.config/_src.php'; 
echo "</head><body>";

// ini_set('display_errors',1); 
// error_reporting(E_ALL); 

/* -- ROOT -- */
if(isset($_COOKIE['SMEMY'])){
    $_SESSION['USER-SESSION'] = $_COOKIE['SMEMY'];
}

if(isset($_SESSION['USER-SESSION'])){
    if($_SESSION['USER-SESSION'] == ""){
        unset($_SESSION['USER-SESSION']);
    }
}

Router::add('/', function() {
    Page::title("Welcome");
    if(Page::session("USER-SESSION")){
        Page::load('index');
    }else{
        Page::goto("/auth/login");
    }
});

Router::add("/user/<name>", function($name) {
    Page::import('iconscout', 'fontawesome');
    Page::title("Eleminq | $name");
    if(Page::session("USER-SESSION")){
        Page::load('user/profile', ["cname" => $name]);
    }else{
        Page::goto("/");
    }
});

Router::add("/view/<id>", function($id) {
    Page::import('fontawesome', 'iconscout');
    Page::title("Eleminq | $id");
    if(Page::session("USER-SESSION")){
        Page::load('view/service', ["idm" => $id]);
    }else{
        Page::goto("/");
    }
});

/* -- AUTH -- */

Router::get("/auth/login", function(){
    Page::import('iconscout');
    Page::title("Login");
    Page::load("auth/login");
});

Router::post("/auth/login", function(){
    Page::title("Login");
    Page::load("auth/login-post");
});

Router::get("/auth/register", function(){
    Page::import('iconscout');
    Page::title("Register");
    Page::load("auth/register");
});

Router::post("/auth/register", function(){
    Page::title("Register");
    Page::load("auth/register-post");
});


Router::get("/<action>-success", function($action){
    Page::title("$action Success");
});


Router::route();
echo "</body></html>";
?>
  

