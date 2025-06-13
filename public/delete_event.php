<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer l'événement à supprimer
$sql = "SELECT Event.*, Venue.nom AS lieu, Artiste.nom AS artiste
        FROM Event
        JOIN Venue ON Event.id_1 = Venue.id
        JOIN Artiste ON Event.id_2 = Artiste.id
        WHERE Event.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Événement introuvable.";
    exit;
}

// Suppression confirmée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Supprimer les tickets liés d'abord (si contrainte non ON DELETE CASCADE)
    $pdo->prepare("DELETE FROM Ticket WHERE id_1 = ?")->execute([$id]);

    // Supprimer l'événement
    $pdo->prepare("DELETE FROM Event WHERE id = ?")->execute([$id]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un événement</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .confirm-box {
            background: #fff;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 30px;
            margin: 40px auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .confirm-box h1 {
            color: #dc3545;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .alert-warning {
            background-color: #fff3cd;
            padding: 10px 15px;
            border-radius: 4px;
            border: 1px solid #ffeeba;
            margin-top: 15px;
            font-size: 14px;
        }

        .confirm-details {
            margin-top: 20px;
            font-size: 16px;
        }

        .confirm-details strong {
            display: inline-block;
            width: 80px;
        }

        .form-actions {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .btn-cancel, .btn-delete {
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            border: none;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
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
    <div class="confirm-box">
        <h1>🗑️ Confirmation de suppression</h1>
        <p><strong>⚠️ Tu es sur le point de supprimer l’événement suivant :</strong></p>

        <div class="confirm-details">
            <p><strong>Titre :</strong> <?= htmlspecialchars($event['titre']) ?></p>
            <p><strong>Date :</strong> <?= date('d F Y à H:i', strtotime($event['date_heure'])) ?></p>
            <p><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></p>
            <p><strong>Artiste :</strong> <?= htmlspecialchars($event['artiste']) ?></p>
            <p><strong>Prix :</strong> <?= number_format($event['prix'], 2) ?> €</p>
        </div>

        <div class="alert-warning">
            Cette action est <strong>définitive</strong> et supprimera aussi les tickets liés.
        </div>

        <form method="post" class="form-actions">
            <a href="dashboard.php" class="btn-cancel">← Annuler</a>
            <button type="submit" class="btn-delete">🗑️ Oui, supprimer cet événement</button>
        </form>
    </div>
</main>

</body>
</html>
