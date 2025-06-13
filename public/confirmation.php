<?php
require_once '../includes/db.php';

$code = $_GET['code'] ?? '';

$stmt = $pdo->prepare("SELECT Ticket.*, Event.titre, Event.date_heure, Venue.nom AS lieu_nom 
                       FROM Ticket 
                       JOIN Event ON Ticket.id_1 = Event.id
                       JOIN Venue ON Event.id_1 = Venue.id
                       WHERE code_unique = ?");
$stmt->execute([$code]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de réservation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #28a745;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            margin: 5px 0;
        }

        .btn {
            margin-top: 25px;
            background: #007bff;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>✅ Réservation confirmée</h1>
    <p>Merci <strong><?= htmlspecialchars($ticket['nom_complet']) ?></strong>, votre réservation est bien enregistrée !</p>
    <hr>
    <p><strong>Événement :</strong> <?= htmlspecialchars($ticket['titre']) ?></p>
    <p><strong>Date :</strong> <?= date('d F Y à H:i', strtotime($ticket['date_heure'])) ?></p>
    <p><strong>Lieu :</strong> <?= htmlspecialchars($ticket['lieu_nom']) ?></p>
    <p><strong>Quantité :</strong> <?= $ticket['quantite'] ?></p>
    <p><strong>Code de réservation :</strong> <?= $ticket['code_unique'] ?></p>
    <p><strong>Prix total :</strong> <?= $ticket['prix_total'] == 0 ? 'Gratuit' : number_format($ticket['prix_total'], 2) . ' €' ?></p>
    <hr>

    <a href="index.php" class="btn">← Retour à l'accueil</a>
</div>

</body>
</html>
