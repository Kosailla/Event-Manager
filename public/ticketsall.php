<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$sql = "SELECT Ticket.*, Event.titre AS event_titre, Venue.nom AS lieu_nom
        FROM Ticket
        JOIN Event ON Ticket.id_1 = Event.id
        JOIN Venue ON Event.id_1 = Venue.id
        ORDER BY date_reservation DESC";

$tickets = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tous les tickets</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; }

        main {
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #007bff;
            color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            text-align: left;
        }

        .status-yes {
            color: green;
            font-weight: bold;
        }

        .status-no {
            color: red;
            font-weight: bold;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 8px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-delete:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<!-- Barre de navigation -->
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
    <h1>🎟️ Tous les tickets réservés</h1>

    <?php if (count($tickets) === 0): ?>
        <p>Aucun ticket n’a été réservé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Date</th>
                    <th>Événement</th>
                    <th>Lieu</th>
                    <th>Quantité</th>
                    <th>Prix total (€)</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['nom_complet']) ?></td>
                        <td><?= htmlspecialchars($ticket['code_unique']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($ticket['date_reservation'])) ?></td>
                        <td><?= htmlspecialchars($ticket['event_titre']) ?></td>
                        <td><?= htmlspecialchars($ticket['lieu_nom']) ?></td>
                        <td><?= $ticket['quantite'] ?></td>
                        <td><?= number_format($ticket['prix_total'], 2) ?></td>
                        <td>
                            <?php if ($ticket['utilise']): ?>
                                <span class="status-yes">✅ Utilisé</span>
                            <?php else: ?>
                                <span class="status-no">⛔ Non utilisé</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="delete_tickets.php?id=<?= $ticket['id'] ?>" class="btn-delete">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>
