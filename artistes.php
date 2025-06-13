<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$sql = "SELECT * FROM Artiste ORDER BY id ASC";
$stmt = $pdo->query($sql);
$artistes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des artistes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
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

        h1::before {
            content: "🎤 ";
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
    <h1>Liste des artistes</h1>
    <a href="add_artiste.php" class="btn-add">+ Ajouter un artiste</a>

    <?php if (count($artistes) === 0): ?>
        <p>Aucun artiste enregistré.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>URL</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($artistes as $artiste): ?>
                    <tr>
                        <td><?= $artiste['id'] ?></td>
                        <td><?= htmlspecialchars($artiste['nom']) ?></td>
                        <td>
                            <?php if (!empty($artiste['url'])): ?>
                                <a href="https://<?= $artiste['url'] ?>" target="_blank"><?= $artiste['url'] ?></a>
                            <?php else: ?>
                                =
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($artiste['photo']) ?></td>
                        <td>
                            <a href="edit_artiste.php?id=<?= $artiste['id'] ?>" class="btn-edit">✏️</a>
                            <a href="delete_artiste.php?id=<?= $artiste['id'] ?>" class="btn-delete">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>
