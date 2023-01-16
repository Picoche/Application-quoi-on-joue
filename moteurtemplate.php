<?php
// gestion des templates avec Twig
require_once('./twig/vendor/autoload.php');
require_once("index.php");
$loader = new \Twig\Loader\FilesystemLoader('Views');
$twig = new \Twig\Environment($loader, ['cache' => false, 'debug' => true]);
$twig->addGlobal("session", $_SESSION);
$twig->addGlobal("post", $_POST);
$twig->addGlobal("server", $_SERVER);
$twig->addExtension(new \Twig\Extension\DebugExtension());
?>