<?php
require_once __DIR__ . "/../config/database.php";

// Vérification que l'utilisateur est admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != 'administrateur') {
    header("Location: users.php");
    exit;
}

$conn = new PDO("mysql:host=$servername;dbname=quizzeo_db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupération de tous les utilisateurs
$stmt = $conn->query("SELECT u.id, u.firstname, u.lastname, u.email, r.name AS role
                      FROM users u
                      JOIN roles r ON u.role_id = r.id");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Gestion des utilisateurs</h1>

<a href="/views/add_user.php">Ajouter un utilisateur</a>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $user): ?>

            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['firstname']) ?></td>
                <td><?= htmlspecialchars($user['lastname']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="/views/update_user.php?id=<?= $user['id'] ?>">Modifier</a>

                    <a href="controller/userController.php?action=delete&id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>

                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
