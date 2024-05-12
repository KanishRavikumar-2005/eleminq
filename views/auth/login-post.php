<?php
session_regenerate_id();
$jd = new Jasper();
$username = $_POST['username'];
$password =  hash('sha256', $_POST['password']);
$ue = $jd->get_row('users', ["username" => $username, "password" => $password]);
$now = DateTime::createFromFormat('U.u', microtime(true));
$dtn = $now->format("d:m:Y::H:i:s:u");

if(count($ue) > 0){
    $ue = $ue[0];

    $randVchars = hash('md5', Basic::random(length: 42));
    $session_idx = hash('sha256', Basic::group($dtn, $randVchars, $username, "-ses-"));
    $session_arr = [
        "sessionId" => $session_idx,
        "userId" => $ue['userId']
    ];

    $jd->add_row('sessions', $session_arr);
    $_SESSION['USER-SESSION'] = $session_idx;
    setcookie(
    "SMEMY",                    // Cookie name
    $session_idx,               // Cookie value
    time() + (10 * 365 * 24 * 60 * 60),  // Expiration time: 10 years from now
    '/',                        // Path: accessible from all paths on the domain
    $_SERVER['HTTP_HOST']      // Domain: accessible from all subdomain                     // HTTP Only: accessible only via HTTP
);


    Page::goto("/");
}else{
    echo "User not found";
}
?>