<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/questions.php';
require_once __DIR__ . '/header.php';
 
// Récupération de l'id de la question
$id = $_GET['id'] ?? null;
$question = $id ? question_find($id) : null;
?>
 
<main>
  <?php if ($question): ?>
    <h1>Question</h1>
    <p><?= htmlspecialchars($question['text']) ?></p>
 
    <form method="post">
      <?php foreach ($question['options'] as $j => $opt): ?>
        <label>
          <input type="radio" name="answer" value="<?= $j ?>">
          <?= htmlspecialchars($opt) ?>
        </label>
      <?php endforeach; ?>
      <button type="submit">Valider</button>
    </form>
  <?php else: ?>
    <p>Question introuvable.</p>
  <?php endif; ?>
</main>
 
<?php require_once __DIR__ . '/footer.php'; ?>
<?php
// Ce fichier contient toutes les fonctions liées aux questions
 
// Récupérer une question par son ID
function question_find($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    $question = $stmt->fetch();
 
    if ($question) {
        // Récupérer aussi les options liées à cette question
        $stmtOpt = $pdo->prepare("SELECT * FROM options WHERE question_id = ?");
        $stmtOpt->execute([$id]);
        $question['options'] = $stmtOpt->fetchAll();
 
        // Identifier la bonne réponse
        $question['correct'] = $question['correct_option_id'];
    }
 
    return $question;
}
 
// Créer une nouvelle question
function question_create($quiz_id, $text, $options, $correct) {
    global $pdo;
    // Insertion de la question
    $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, text, correct_option_id) VALUES (?, ?, ?)");
    $stmt->execute([$quiz_id, $text, $correct]);
    $question_id = $pdo->lastInsertId();
 
    // Insertion des options
    foreach ($options as $opt) {
        $stmtOpt = $pdo->prepare("INSERT INTO options (question_id, text) VALUES (?, ?)");
        $stmtOpt->execute([$question_id, $opt]);
    }
 
    return $question_id;
}
 
// Mettre à jour une question existante
function question_update($id, $text, $options, $correct) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE questions SET text = ?, correct_option_id = ? WHERE id = ?");
    $stmt->execute([$text, $correct, $id]);
 
    // Mise à jour des options (simplifiée : supprime et recrée)
    $pdo->prepare("DELETE FROM options WHERE question_id = ?")->execute([$id]);
    foreach ($options as $opt) {
        $stmtOpt = $pdo->prepare("INSERT INTO options (question_id, text) VALUES (?, ?)");
        $stmtOpt->execute([$id, $opt]);
    }
}
 
// Supprimer une question
function question_delete($id) {
    global $pdo;
    // Supprimer les options liées
    $pdo->prepare("DELETE FROM options WHERE question_id = ?")->execute([$id]);
    // Supprimer la question
    $pdo->prepare("DELETE FROM questions WHERE id = ?")->execute([$id]);
}