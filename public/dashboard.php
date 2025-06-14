<?php
// Affichage des erreurs pour débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authentification + connexion DB
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Récupération des événements avec lieu et artist
$sql = "SELECT Event.id, Event.titre, Event.date_heure, Event.prix, Venue.nom AS lieu, Artiste.nom AS artiste
        FROM Event
        JOIN Venue ON Event.id_1 = Venue.id
        JOIN Artiste ON Event.id_2 = Artiste.id
        ORDER BY Event.date_heure ASC";

$stmt = $pdo->query($sql);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – Événements</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
    <h1>Dashboard – Événements</h1>
    <a href="add_event.php" class="btn-add">+ Ajouter un événement</a>

    <?php if (count($events) === 0): ?>
        <p>Aucun événement trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Lieu</th>
                    <th>Artiste</th>
                    <th>Prix (€)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= $event['id'] ?></td>
                        <td><?= htmlspecialchars($event['titre']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($event['date_heure'])) ?></td>
                        <td><?= htmlspecialchars($event['lieu']) ?></td>
                        <td><?= htmlspecialchars($event['artiste']) ?></td>
                        <td><?= number_format($event['prix'], 2) ?></td>
                        <td>
                            <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-edit">✏️</a>
                            <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-delete">🗑️</a>
                            <a href="tickets.php?event_id=<?= $event['id'] ?>" class="btn btn-ticket">🎟️ Tickets</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>
