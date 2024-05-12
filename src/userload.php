<?php 
$jp = new Jasper();

$use = $jp->get_row("sessions", ["sessionId" => $_SESSION['USER-SESSION']]);
if(count($use) > 0){
    $data = $jp->get_row("users", ["userId"=> $use[0]['userId']]);
    // echo $_SESSION['USER-SESSION'] . ": " . $_COOKIE["SMEMY"];
    if(count($data) < 1){
        Page::goto("/auth/login");
    }
}else{
    $_SESSION['USER-SESSION'] = "";
    unset($_COOKIE['SMEMY']); 
    setcookie('SMEMY', '', -1, '/');
    Page::goto("/auth/login");
}
$current_user = $data[0];
