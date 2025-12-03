<?php
session_start();
require_once "/config/database.php";

$errors = [];
$firstname = "";
$lastname = "";
$email = "";
$password = "";
$role_id = 4;
// verifications
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role_id = intval($_POST["role_id"]);

    if (empty($firstname)) $errors["firstname"] = "Le prénom est requis";
    if (empty($lastname)) $errors["lastname"] = "le nom est requis";
    if (empty($email)) $errors["email"] = "l'email est requis ";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Email invalide";
    if (empty($password)) $errors["password"] = "Le mot de passe est requis";
    // vérifier si l'email exixte déja
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = : email");
        $stmt->execute(["email" => $email]);
        if ($stmt->fetch()) $errors["email"] = "Cet email est déja utilisé";
    }
    // si tout est bon ,insérer

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (role_id, firstname , lastname , email,password) VALUES (:role_id , :firstname , :lastname , :email , :password)");
        $stmt->execute([
            "role_id" => $role_id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $hashedPassword
        ]);
        $_SESSION["message"] = "Inscription réussie ! vous pouvez maintenant vous connecter.";
        header("Location: /view/login.php");
        exit;
    }
}
// Récupérer les rôles pour le select
$rolesStmt = $conn->query("SElECT * FROM roles");
$roles = $rolesStmt->fetchAll();
?>
<main>
    <h1>Inscription</h1>
    <form method="POST">
        <div>
            <label for="firstname">Prénom:</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>">
            <?php if (isset($errors["firstname"])): ?>
                <p style="color:red"><?= $errors["firstname"] ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>">
            <?php if (isset($errors["lastname"])): ?>
                <p style="color:red"><?= $errors["lastname"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="email">Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors["email"])): ?>
                <p style="color:red"><?= $errors["email"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password">
            <?php if (isset($errors["password"])): ?>
                <p style="color:red"><?= $errors["password"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="role_id">Rôle :</label>
            <select name="role_id">
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">S'inscrire</button>
    </form>
</main>
</form>
</main>
