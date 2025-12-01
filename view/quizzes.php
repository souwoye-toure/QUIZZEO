<?php
// On inclut le contrôleur pour récupérer et préparer les données.
require_once 'quizzesController.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de Tous les Quizzes - QUIZZEO</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background-color: #f0f4f8; }
        header { text-align: center; padding-bottom: 20px; }
        h1 { color: #333; } /* À adapter selon le logo */
        .quiz-container { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 25px; 
            margin-top: 30px; 
        }
        .quiz-card { 
            background-color: #fff; 
            border: 1px solid #ddd; 
            border-left: 5px solid #ff4136; /* Exemple de couleur - à changer */
            border-radius: 8px; 
            padding: 20px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); 
            transition: transform 0.2s, box-shadow 0.2s; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .quiz-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); }
        .quiz-card h3 { margin-top: 0; color: #333; font-size: 1.5em; }
        .quiz-card p { color: #666; font-size: 0.9em; line-height: 1.4; flex-grow: 1; }
        .info-footer { border-top: 1px solid #eee; padding-top: 10px; margin-top: 15px; font-size: 0.85em; }
        .creator { color: #007bff; font-weight: bold; }
        .status { float: right; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
        .status-writing { background-color: #ffc107; color: #333; }
        .status-launched { background-color: #28a745; color: white; }
        .status-finished { background-color: #6c757d; color: white; }
        .start-btn { 
            display: inline-block; 
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            text-align: center;
            text-decoration: none; 
            border-radius: 5px; 
            margin-top: 10px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <header>
        <h1>📚 Catalogue des Quizzes QUIZZEO</h1>
        <p>Découvrez les questionnaires créés par nos utilisateurs (écoles et entreprises).</p>
    </header>

    <?php if (!$is_user_logged_in): ?>
        <div style="text-align: center; color: red; padding: 20px; border: 1px solid red;">
            Vous devez être connecté pour visualiser cette page.
        </div>
    <?php else: ?>
        <main class="quiz-container">
            
            <?php if (empty($quizzes_list)): ?>
                <p style="text-align: center; width: 100%;">
                    Désolé, aucun quiz n'a été trouvé pour le moment.
                </p>
            <?php else: ?>
                <?php foreach ($quizzes_list as $quiz): 
                    // Déterminer la classe CSS pour le statut
                    $status_class = match(strtolower($quiz['status'] ?? '')) {
                        "en cours d'écriture" => 'status-writing',
                        "lancé" => 'status-launched',
                        "terminé" => 'status-finished',
                        default => 'status-finished',
                    };
                ?>
                    <div class="quiz-card" data-quiz-id="<?= htmlspecialchars($quiz['id']) ?>">
                        <div>
                            <h3><?= htmlspecialchars($quiz['title']) ?></h3>
                            
                            <p><?= nl2br(htmlspecialchars($quiz['description'] ?? 'Pas de description.')) ?></p>
                        </div>

                        <div class="info-footer">
                            <span class="status <?= $status_class ?>">
                                <?= htmlspecialchars(ucwords($quiz['status'] ?? 'Statut inconnu')) ?>
                            </span>

                            <span>Créé par : <span class="creator"><?= $quiz['creator'] ?></span></span>
                        </div>
                        
                        <a href="quiz_access.php?id=<?= htmlspecialchars($quiz['id']) ?>" class="start-btn">
                            Accéder au Quiz
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </main>
    <?php endif; ?>

</body>
</html>
