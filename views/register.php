<?php
// Démarrer la session pour stocker messages flash et données utilisateurs
session_start();

// Connexion à la base de données via PDO
require_once __DIR__ . "/../config/database.php";

// Initialisation des variables
$errors = [];
$firstname = "";
$lastname = "";
$email = "";
$password = "";
$role_id = 4; // rôle par défaut (utilisateur)


// Traitement du formulaire lorsque soumis

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nettoyage des champs
    $firstname = trim($_POST["firstname"]);
    $lastname  = trim($_POST["lastname"]);
    $email     = trim($_POST["email"]);
    $password  = trim($_POST["password"]);
    $role_id   = intval($_POST["role_id"]);

    // Vérifications simples
    if (empty($firstname)) $errors["firstname"] = "Le prénom est requis";
    if (empty($lastname))  $errors["lastname"]  = "Le nom est requis";
    if (empty($email))     $errors["email"]     = "L'email est requis";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Email invalide";
    if (empty($password))  $errors["password"]  = "Le mot de passe est requis";

    // Vérifier si l'email existe déjà
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
        if ($stmt->fetch()) $errors["email"] = "Cet email est déjà utilisé";
    }

    // Si pas d'erreurs → insertion dans la base
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe

        $stmt = $conn->prepare("INSERT INTO users (role_id, firstname, lastname, email, password) 
                                VALUES (:role_id, :firstname, :lastname, :email, :password)");
        $stmt->execute([
            "role_id"   => $role_id,
            "firstname" => $firstname,
            "lastname"  => $lastname,
            "email"     => $email,
            "password"  => $hashedPassword
        ]);

        // Message flash + redirection vers la page de connexion
        $_SESSION["message"] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: ../views/login.php");
        exit;
    }
}


// Récupération des rôles pour le select

$rolesStmt = $conn->query("SELECT * FROM roles");
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- CONTENU HTML : formulaire d'inscription -->
<main class="container single">
    <h1>Inscription</h1>
    <form method="POST">
        <!-- Prénom -->
        <div>
            <label for="firstname">Prénom :</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>">
            <?php if (isset($errors["firstname"])): ?>
                <p style="color:red"><?= htmlspecialchars($errors["firstname"]) ?></p>
            <?php endif; ?>
        </div>

        <!-- Nom -->
        <div>
            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>">
            <?php if (isset($errors["lastname"])): ?>
                <p style="color:red"><?= htmlspecialchars($errors["lastname"]) ?></p>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div>
            <label for="email">Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors["email"])): ?>
                <p style="color:red"><?= htmlspecialchars($errors["email"]) ?></p>
            <?php endif; ?>
        </div>

        <!-- Mot de passe -->
        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password">
            <?php if (isset($errors["password"])): ?>
                <p style="color:red"><?= htmlspecialchars($errors["password"]) ?></p>
            <?php endif; ?>
        </div>

        <!-- Sélection du rôle -->
        <div>
            <label for="role_id">Rôle :</label>
            <select name="role_id">
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>" <?= $role_id == $role['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">S'inscrire</button>
    </form>
</main>

<?php 
// Footer HTML
require_once __DIR__ . "/../views/footer.php";
?>
