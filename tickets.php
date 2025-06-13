<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Récupérer l'ID de l'événement
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

// Infos de l'événement
$sql = "SELECT Event.*, Venue.nom AS lieu
        FROM Event
        JOIN Venue ON Event.id_1 = Venue.id
        WHERE Event.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Événement introuvable.";
    exit;
}

// Tickets liés
$stmt = $pdo->prepare("SELECT * FROM Ticket WHERE id_1 = ? ORDER BY date_reservation ASC");
$stmt->execute([$event_id]);
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tickets achetés – <?= htmlspecialchars($event['titre']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        main {
            padding: 20px 40px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background: #222;
            color: white;
        }

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-waiting {
            color: orange;
            font-weight: bold;
        }

        .used-yes {
            background: #28a745;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .used-no {
            background: #6c757d;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .btn-back {
            margin-top: 20px;
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-left">🎫 <strong>Back Office</strong></div>
    <div class="nav-links">
        <a href="dashboard.php">Événements</a>
        <a href="ticketsall.php">Tickets</a>
        <a href="venues.php">Lieux</a>
        <a href="artistes.php">Artistes</a>
    </div>
    <div class="nav-right">
    Connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']) ?></strong> |
    <a href="logout.php" style="color: red; text-decoration: none;">🚪 Déconnexion</a>
</div>

</nav>

<main>
    <h2>🎟️ Tickets achetés – <?= htmlspecialchars($event['titre']) ?></h2>
    <div class="subtitle">
        Date : <?= date('d F Y à H:i', strtotime($event['date_heure'])) ?> |
        Lieu : <?= htmlspecialchars($event['lieu']) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Nom de l’acheteur</th>
                <th>Quantité</th>
                <th>Date d’achat</th>
                <th>Statut</th>
                <th>Utilisé ?</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $i => $ticket): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($ticket['code_unique']) ?></td>
                    <td><?= htmlspecialchars($ticket['nom_complet']) ?></td>
                    <td><?= $ticket['quantite'] ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($ticket['date_reservation'])) ?></td>
                    <td><?= $ticket['prix_total'] > 0 ? '<span class="status-paid">Payé</span>' : '<span class="status-waiting">En attente</span>' ?></td>
                    <td><?= $ticket['utilise'] ? '<span class="used-yes">✔ Oui</span>' : '<span class="used-no">✖ Non</span>' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn-back">← Retour aux événements</a>
</main>

</body>
</html>
