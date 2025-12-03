<?php
require_once __DIR__ . '/../includes/functions.php';


require_once "../config/database.php";
require_once __DIR__ . "/login.php";  // fichier login dans le même dossier


// vérification que l'utilisateur est connecté
    session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
//récupération de l'identifiant de l'utilisateur depuis la session
$user_id = $_SESSION["user_id"];

//Création de la base de donnée
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les informations de l'utilisateur
$sql = "SELECT firstname, lastname, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Nombre de quiz joués
$sql_quiz = "SELECT COUNT(DISTINCT quiz_id) AS quizzes_joues,
                    SUM(points) AS score_total
             FROM user_responses
             JOIN questions ON user_responses.question_id = questions.id
             WHERE user_responses.user_id = ?";
$stmt_quiz = $conn->prepare($sql_quiz);
$stmt_quiz->bind_param("i", $user_id);
$stmt_quiz->execute();
$result_quiz = $stmt_quiz->get_result();
$stats = $result_quiz->fetch_assoc();


// Requête pour calculer le score total de chaque joueur
$sql_classement = "
    SELECT u.id, u.firstname, u.lastname, SUM(q.points) AS score_total
    FROM users u
    LEFT JOIN user_responses ur ON u.id = ur.user_id
    LEFT JOIN questions q ON ur.question_id = q.id
    GROUP BY u.id
    ORDER BY score_total DESC
    LIMIT 10
";

$result_classement = $conn->query($sql_classement);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Dashboard page</title>
</head>
<body>
    <header>
        <div class="container">
            <!--Logo de la plateforme-->
            <div class="brand">
                <img src="assets/img/logo_quizzeo-png.png" alt="Logo" style="width: 123px; height: 70px"/>
            </div>

            <!-- Menu de navigation -->
            <nav class="top-nav" role="navigation" aria-label="Navigation principale">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="view/quizzes.php">Quiz</a></li>
                    <li><a href="view/dashboard.php">Dashboard</a></li>
                    <li><a href="view/login.php" class="btn-connexion">Connexion</a></li>
                </ul>
            </nav>
        </div>

    </header>

    <main>
        <!-- Message de bienvenue personnalisé avec le nom de l'utilisateur -->

        <h1>Bienvenue<?php echo $user['firstname'] . ' ' . $user['lastname']; ?></h1>

        <!-- Section des statistiques de l'utilisateur -->

        <div class="statistique">
            <div class="card">
                <h2>Quizzes Joués</h2>

                <!-- Affiche le nombre de quizz joués ou 0 si aucune donnée -->
                <p><?php echo $stats['quizzes_joues'] ?? 0; ?></p>
            </div>
            <div class="card">
                <h2>Score Total</h2>

                <!-- Affiche le score total ou 0 si aucune donnée -->
                <p><?php echo $stats['score_total'] ?? 0; ?> pts</p>
            </div>

            <div class="card">
                <h2>Badges Gagnés</h2>

                <!-- Affiche le nombre de badges gagnés -->
                <p><?php echo $badges_count; ?></p>
            </div>
        </div>

        <!-- Tableau du classement des joueurs -->
        <h2>Classement des joueurs</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Nom</th>
                    <th>Score Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Boucle pour afficher chaque joueur dans le classement
                    $position= 1;
                    while ($joueur = $result_classement->fetch_assoc()):
                ?>
                    <tr>

                        <!-- Position du joueur dans le classement -->
                        <td><?php echo $position++; ?></td>
                        <!-- Nom complet du joueur-->
                        <td><?php echo $joueur['firstname'] . ' ' . $joueur['lastname']; ?></td>
                        <!-- Score total du joueur ou 0 si aucune donnée -->
                        <td><?php echo $joueur['score_total'] ?? 0; ?></td>

                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </main>
</body>
</html>
