<?php 
$jd = new Jasper();
$username = $_POST['username'];
$password = hash('sha256', $_POST['password']);
$displayname = $_POST['fullname'];
$password_confirmation = hash('sha256', $_POST['password-conf']);
$data = $jd->get_row("users", ["username" => $username]);
$now = DateTime::createFromFormat('U.u', microtime(true));


if(count($data) < 1){
    $dtn = $now->format("d:m:Y::H:i:s:u");
    $randchars = Basic::random(special: false, length:15);
    $randMchars = hash('md5', Basic::random(length: 20));
    $randSchars = hash('md5', Basic::random(length: 22));
    $userID = hash('sha256', Basic::group($dtn, $randchars, $username, "::"));
    $keywordId = hash('sha256', Basic::group($dtn, $randMchars, "-o-"));
    $reviewId = hash('sha256', Basic::group($dtn, $randSchars, "-r-"));

    
    $inlemArray= [
        "userId" => $userID,
        "username" => $username,
        "displayname" => $displayname,
        "keywordId" => $keywordId,
        "reviewId" => $reviewId,
        "reviewWeight" => 0,
        "password" => $password
    ];

    $jd->add_row('users', $inlemArray);
    $randVchars = hash('md5', Basic::random(length: 42));
    $session_idx = hash('sha256', Basic::group($dtn, $randVchars, $username, "-ses-"));
    $session_arr = [
        "sessionId" => $session_idx,
        "userId" => $userID
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
    echo "User Already Exists";
}


?>