<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../controller/userController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_login_submit();
}

$error = get_flash('error');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- CSS principal du site (header + layout) -->
    <link rel="stylesheet" href="/QUIZZEO/assets/css/style.css">

    <!-- CSS spécifique login neutre -->
    <link rel="stylesheet" href="/QUIZZEO/assets/css/login.css">
</head>

<body style="style = background-image: url('assets/img/background.png'); 
             background-size: cover; 
             background-position: center; 
             background-repeat: no-repeat;">

<!-- HEADER identique à index -->
<?php include __DIR__ . '/../views/header.php'; ?>

<main class="login-center">
    <section class="login-card">
        <h1>Connexion</h1>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" class="login-form">
            <label>Nom d'utilisateur
                <input type="text" name="username" required>
            </label>

            <label>Mot de passe
                <input type="password" name="password" required>
            </label>

            <button type="submit" class="btn-primary">Se connecter</button>
        </form>

        <p class="test-info">
            Comptes de test : admin, ecole, user (password : password)
        </p>
    </section>
</main>

<?php include __DIR__ . '/../views/footer.php'; ?>

</body>
</html>
