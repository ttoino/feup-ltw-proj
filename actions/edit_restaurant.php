<?php 

    if (strcmp($_SERVER['REQUEST_METHOD'], "POST") !== 0) {
        header("Location: /");
        die;
    }

    include_once("../lib/params.php");
    include_once("../database/models/user.php");

    $params = parseParams(post_params: [
        'id' => new IntParam(),
        'name' => new StringParam(),
        'address' => new StringParam(),
        'referer'
    ]);

    session_start();

    if (!isset($_SESSION['user'])) { // prevents edits from unauthenticated users
        header("Location: /restaurant?id=".$params['id']);
        die;
    }

    $restaurant = Restaurant::get($params['id']);

    if ($restaurant === null) { // error fetching restaurant model
        header("Location: /restaurant?id=".$params['id']);
        die;
    }

    if($_SESSION['user'] !== $restaurant->owner) { // prevents edits from everyone other than the restaurant owner
        header("Location: /restaurant?id=".$params['id']);
        die();
    }

    $restaurant->name = $params['name'];
    $restaurant->address = $params['address'];

    $restaurant->update();

    header("Location: /restaurant?id=".$params['id']);
?>
