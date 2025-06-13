<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM Venue WHERE id = ?");
$stmt->execute([$id]);
$venue = $stmt->fetch();

if (!$venue) {
    echo "Lieu introuvable.";
    exit;
}

// Suppression confirmée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM Venue WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: venues.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un lieu</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        main {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h1 {
            color: #dc3545;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .alert-warning {
            background-color: #fff3cd;
            padding: 10px 15px;
            border-radius: 4px;
            border: 1px solid #ffeeba;
            margin-top: 15px;
            font-size: 14px;
        }

        .details p {
            margin: 5px 0;
        }

        .form-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 9px 14px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            border: none;
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
    <h1>🗑️ Confirmation de suppression</h1>
    <p><strong>⚠️ Tu es sur le point de supprimer le lieu suivant :</strong></p>

    <div class="details">
        <p><strong>Nom :</strong> <?= htmlspecialchars($venue['nom']) ?></p>
        <p><strong>Type :</strong> <?= htmlspecialchars($venue['type']) ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($venue['adresse']) ?></p>
        <p><strong>Photo :</strong> <?= htmlspecialchars($venue['photo']) ?></p>
    </div>

    <div class="alert-warning">
        Cette action est <strong>définitive</strong> et supprimera ce lieu de la base de données.
    </div>

    <form method="post" class="form-actions">
        <a href="venues.php" class="btn-cancel">← Annuler</a>
        <button type="submit" class="btn-delete">🗑️ Oui, supprimer ce lieu</button>
    </form>
</main>

</body>
</html>
