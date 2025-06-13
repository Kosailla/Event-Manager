<?php
require_once 'includes/db.php';

$sql = "SELECT Event.*, Venue.nom AS lieu_nom
        FROM Event
        JOIN Venue ON Event.id_1 = Venue.id
        WHERE date_heure >= NOW()
        ORDER BY date_heure ASC";
$events = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Événements à venir</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #333;
            color: white;
            padding: 15px 30px;
            font-size: 24px;
            position: relative;
        }

        .admin-button {
            position: absolute;
            right: 30px;
            top: 15px;
            background: #28a745;
            color: white;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .admin-button:hover {
            background: #218838;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 15px;
        }

        h1 {
            margin-bottom: 25px;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: white;
            width: calc(25% - 20px);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #ddd;
        }

        .card-content {
            padding: 15px;
            flex-grow: 1;
        }

        .card h3 {
            margin: 0 0 10px;
        }

        .card p {
            margin: 5px 0;
            font-size: 14px;
        }

        .card .reserve {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            margin: 10px 15px;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
        }

        .card .reserve:hover {
            background: #0056b3;
        }

        @media (max-width: 992px) {
            .card { width: calc(33.333% - 20px); }
        }

        @media (max-width: 768px) {
            .card { width: calc(50% - 20px); }
        }

        @media (max-width: 480px) {
            .card { width: 100%; }
        }
    </style>
</head>
<body>

<div class="header">
    🎟️ Event Manager
    <a href="login.php" class="admin-button">Administration</a>
</div>

<div class="container">
    <h1>🎉 Événements à venir</h1>

    <?php if (count($events) === 0): ?>
        <p>Aucun événement prévu.</p>
    <?php else: ?>
        <div class="cards">
            <?php foreach ($events as $event): ?>
                <div class="card">
                    <?php
                    $img = !empty($event['photo'])
                        ? 'uploads/' . htmlspecialchars($event['photo'])
                        : 'https://via.placeholder.com/600x400?text=Aucune+image';
                    ?>
                    <img src="<?= $img ?>" alt="Image de l’événement">

                    <div class="card-content">
                        <h3><?= htmlspecialchars($event['titre']) ?></h3>
                        <p>📅 Le <?= date('d F Y à H:i', strtotime($event['date_heure'])) ?></p>
                        <p>📍 <?= htmlspecialchars($event['lieu_nom']) ?></p>
                        <p>💰 <?= ($event['prix'] == 0) ? 'Gratuit' : number_format($event['prix'], 2) . ' €' ?></p>
                    </div>
                    <a href="reserver.php?id=<?= $event['id'] ?>" class="reserve">Réserver</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
