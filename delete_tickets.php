<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: ticketsall.php");
    exit;
}

// Récupérer les infos du ticket
$stmt = $pdo->prepare("SELECT Ticket.*, Event.titre AS event_titre FROM Ticket 
                       JOIN Event ON Ticket.id_1 = Event.id 
                       WHERE Ticket.id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket introuvable.";
    exit;
}

// Si confirmation (POST), supprimer le ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("DELETE FROM Ticket WHERE id = ?")->execute([$id]);
    header("Location: ticketsall.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer le ticket</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        main {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>

<main>
    <h1>🗑️ Supprimer la réservation</h1>

    <p>Êtes-vous sûr de vouloir supprimer le ticket pour :</p>

    <ul style="list-style: none; padding: 0;">
        <li><strong>Nom :</strong> <?= htmlspecialchars($ticket['nom_complet']) ?></li>
        <li><strong>Événement :</strong> <?= htmlspecialchars($ticket['event_titre']) ?></li>
        <li><strong>Code :</strong> <?= htmlspecialchars($ticket['code_unique']) ?></li>
    </ul>

    <form method="post">
        <button type="submit" class="btn btn-danger">✅ Confirmer la suppression</button>
        <a href="ticketsall.php" class="btn btn-secondary">↩️ Annuler</a>
    </form>
</main>

</body>
</html>
