<?php
require_once __DIR__ . "/../config/database.php";




// Vérification que l'utilisateur est admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'administrateur') {
    header("Location: add_user.php");
    exit;
}

// Initialisation des variables
$errors = [];
$firstname = "";
$lastname = "";
$email = "";
$password = "";
$role_id = 4; // rôle par défaut
$availableRoles = [];

// Récupération des rôles depuis la base

/** @var PDOStatement $stmt_roles */

$stmt_roles = $conn->query("SELECT * FROM roles");
$availableRoles = $stmt_roles->fetchAll(PDO::FETCH_ASSOC); // variable corrigée

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstname = trim($_POST["firstname"]);
    $lastname  = trim($_POST["lastname"]);
    $email     = trim($_POST["email"]);
    $password  = trim($_POST["password"]);
    $role_id   = $_POST["role_id"];

    // Vérification des champs
    if (empty($firstname)) $errors["firstname"] = "Le prénom est requis";
    if (empty($lastname))  $errors["lastname"] = "Le nom est requis";
    if (empty($email))     $errors["email"] = "L'email est requis";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Email invalide";
    if (empty($password))  $errors["password"] = "Le mot de passe est requis";

    // Si OK → insertion
    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users (firstname, lastname, email, password, role_id)
            VALUES (:firstname, :lastname, :email, :password, :role_id)
        ");

        $stmt->execute([
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email,
            'password'  => $hashedPassword,
            'role_id'   => $role_id
        ]);

        $_SESSION["message"] = "Utilisateur ajouté avec succès !";
        header("Location: users.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajout utilisateur</title>
</head>

<body>

    <h1>Ajouter un utilisateur</h1>

    <form method="POST">

        <div>
            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>">
            <?php if (isset($errors['firstname'])): ?>
                <p style="color:red"><?= htmlspecialchars($errors['firstname']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>">
            <?php if (isset($errors['lastname'])): ?>
                <p style="color:red"><?= htmlspecialchars($errors['lastname']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors['email'])): ?>
                <p style="color:red"><?= htmlspecialchars($errors['email']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password">
            <?php if (isset($errors['password'])): ?>
                <p style="color:red"><?= htmlspecialchars($errors['password']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="role_id">Rôle :</label>
            <select name="role_id" id="role_id">
                <?php foreach ($availableRoles as $role): ?>
                    <option value="<?= $role['id'] ?>" <?= ($role['id'] == $role_id) ? "selected" : "" ?>>
                        <?= htmlspecialchars($role['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Ajouter</button>
    </form>

    <p><a href="users.php">← Retour</a></p>

</body>

</html>
