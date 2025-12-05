<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/header.php';
 
// Vérification : seul un utilisateur connecté peut créer un quiz
session_start();
if (!isset($_SESSION['user'])) {
    echo "<p style='color:red'>Vous devez être connecté pour créer un quiz.</p>";
} else {
    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
 
        if ($title) {
            $stmt = $pdo->prepare("INSERT INTO quizzes (title, description, author, status) VALUES (?, ?, ?, 'lancé')");
            $stmt->execute([$title, $description, $_SESSION['user']]);
            echo "<p style='color:green'>Quiz créé avec succès !</p>";
        } else {
            echo "<p style='color:red'>Le titre est obligatoire.</p>";
        }
    }
}
?>
 
<main>
  <h1>Créer un nouveau quiz</h1>
  <form method="post">
    <label>Titre du quiz</label>
    <input type="text" name="title" required>
 
    <label>Description</label>
    <textarea name="description"></textarea>
 
    <button type="submit">Créer</button>
  </form>
</main>
 
<?php require_once __DIR__ . '/footer.php'; ?>