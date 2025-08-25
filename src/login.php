<?php
session_start();
require 'donnees/users.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    // Validation des champs
    if (empty($email)) {
        $errors['email'] = 'Mail obligatoire';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Mail non valide';
    }

    if (empty($mdp)) {
        $errors['mdp'] = 'Mot de passe obligatoire';
    } elseif (strlen($mdp) < 6) {
        $errors['mdp'] = 'Mot de passe trop court';
    }

    // Vérification des identifiants uniquement si pas d'erreurs
    if (empty($errors)) {
        $userFound = false;

        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($mdp, $user['password'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $userFound = true;
                header("Location: accueil.php");
                exit;
            }
        }

        if (!$userFound) {
            $errors['login'] = "Email ou mot de passe incorrect";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main>
        <div>
            <h1 class="text-center mt-5 fw-bold">Formulaire</h1>
        </div>
        <div class="formulaire container border rounded-4 mt-5">
            <form class="row g-3 p-5" method="POST" action="" novalidate>
                <div class="col-12">
                    <label for="inputAddress" class="form-label">E-mail</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["email"] ?? '' ?></span>
                    <input type="email" name="email" value="<?= $_POST["email"] ?? "" ?>" class="form-control" id="inputAddress">
                </div>
                <div class="col-md-6">
                    <label for="inputPassword4" class="form-label">Mot de passe</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["mdp"] ?? '' ?></span>
                    <input type="password" name="mdp" value="<?= $_POST["mdp"] ?? "" ?>" class="form-control" id="inputPassword4">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Confirmer mon inscription</button>
                </div>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>