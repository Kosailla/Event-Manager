<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$sql = "SELECT * FROM Venue ORDER BY id ASC";
$stmt = $pdo->query($sql);
$venues = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des lieux</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .btn-edit {
            background: #ffc107;
            color: black;
            padding: 5px 10px;
            border-radius: 4px;
            margin-right: 5px;
            text-decoration: none;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-add {
            display: inline-block;
            margin-bottom: 15px;
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
        }

        main {
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 6px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #222;
            color: white;
        }

        h1::before {
            content: "📍 ";
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
    <h1>Gestion des lieux (venues)</h1>
    <a href="add_venue.php" class="btn-add">+ Ajouter un lieu</a>

    <?php if (count($venues) === 0): ?>
        <p>Aucun lieu enregistré.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Adresse</th>
                    <th>URL</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($venues as $venue): ?>
                    <tr>
                        <td><?= $venue['id'] ?></td>
                        <td><?= htmlspecialchars($venue['nom']) ?></td>
                        <td><?= htmlspecialchars($venue['type']) ?></td>
                        <td><?= htmlspecialchars($venue['adresse']) ?></td>
                        <td>
                            <?php if (!empty($venue['url'])): ?>
                                <a href="https://<?= $venue['url'] ?>" target="_blank"><?= $venue['url'] ?></a>
                            <?php else: ?>
                                =
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($venue['photo']) ?></td>
                        <td>
                            <a href="edit_venue.php?id=<?= $venue['id'] ?>" class="btn-edit">✏️ Modifier</a>
                            <a href="delete_venue.php?id=<?= $venue['id'] ?>" class="btn-delete">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>
