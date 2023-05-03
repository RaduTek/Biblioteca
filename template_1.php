<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

if (!file_exists("env.php")){
    echo "Environment variable file (env.php) not defined!";
    exit();
}
include_once("env.php");

$titlu_aplicatie = "Biblioteca Mea";

function redirect($url, $permanent = false) {
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}

function random_string($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

$bazadate = new mysqli("localhost", $db_user, $db_passwd, $db_name);

if ($bazadate -> connect_error) {
    die("<hr/>
    <h2>Eroare de conexiune la baza de date!</h2>
    <p>Raporteaza la webadmin aceasta problema!</p>
    <hr/>
    <p>Motiv eroare:<br/>
    <pre>" . $bazadate -> connect_error . "</pre>
    </p>
    <hr/>");
}

?>