<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Model\Game;

try {
    // Si la méthode HTTP utilisée dans cette requête n'est pas POST, c'est donc que l'utilisateur a tenté d'accéder à ce script manuellement
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('This script must be accessed via a POST HTTP request.', 0);
    }

    // S'il manque un seul des champ présents dans le formulaire, c'est donc que l'utilisateur a contourné le formulaire
    if (!isset($_POST['title']) ||
        !isset($_POST['link']) ||
        !isset($_POST['release_date']) ||
        !isset($_POST['developer']) ||
        !isset($_POST['platform'])
    ) {
        throw new Exception('Form field missing in request.', 1);
    }

    // Teste si l'un des champs est vide
    if (empty($_POST['title']) ||
        empty($_POST['link']) ||
        empty($_POST['release_date']) ||
        empty($_POST['developer']) ||
        empty($_POST['platform'])
    ) {
        throw new Exception('Form should not have empty fields.', 2);
    }

    // Récupère l'ID passé dans les données de formulaire le cas échéant
    $id = null;
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
    }

    // Crée un nouvel objet représentant un jeu vidéo à partir des informations du formulaire
    $game = new Game(
        $id,
        $_POST['title'],
        $_POST['release_date'],
        $_POST['link'],
        $_POST['developer'],
        $_POST['platform']
    );

    // Répercute l'état actuel de l'objet sur un enregistrement en base de données
    $game->save();

    // Redirige sur la liste des jeux
    header('Location: /');
}
catch (Exception $exception) {
    // Redirige sur la liste des jeux
    header('Location: /?error=' . $exception->getCode());
}
    