<?php
require_once __DIR__ . '/../includes/functions.php';

session_start();

// Inclusion de la base de données
require_once "database.php"; // $conn PDO

$errors = [];
$email = "";
$password = "";

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Nettoyage
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Vérifications simples
    if (empty($email)) {
        $errors["email"] = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Entrez un email valide";
    }

    if (empty($password)) {
        $errors["password"] = "Le mot de passe est requis";
    }

    // Si pas d'erreurs, on vérifie la base
    if (empty($errors)) {

        // Préparation de la requête PDO
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            $errors["general"] = "Identifiants invalides";
        } else {
            // Vérification du mot de passe haché
            if (!password_verify($password, $user['password'])) {
                $errors["general"] = "Identifiants invalides";
            } else {
                // Connexion réussie
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["message"] = "Connexion réussie !";
                header("Location: dashboard.php");
                exit;
            }
        }
    }
}
?>

<main>
    <h1>Connexion</h1>
    <form method="POST">
        <?php if (isset($errors["general"])): ?>
            <div style="color:red"><?= $errors["general"] ?></div>
        <?php endif; ?>

        <div>
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors["email"])): ?>
                <p style="color: red;"><?= $errors["email"] ?></p>
            <?php endif ?>
        </div>

        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password">
            <?php if (isset($errors["password"])): ?>
                <p style="color:red;"><?= $errors["password"] ?></p>
            <?php endif ?>
        </div>

        <button type="submit">Se connecter</button>
    </form>
</main>
