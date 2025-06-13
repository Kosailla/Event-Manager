<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $url = $_POST['url'];
    $photo = null;

    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $filename = basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $photo = $filename;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO Artiste (nom, url, photo) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $url, $photo]);

    header("Location: artistes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un artiste</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        form {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="url"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 4px;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 9px 14px;
            border-radius: 4px;
            text-decoration: none;
        }

        h1 {
            text-align: center;
        }

        h1::before {
            content: "🎙️ ";
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
    <h1>Ajouter un artiste</h1>

    <form method="post" enctype="multipart/form-data">
        <label for="nom">Nom de l’artiste</label>
        <input type="text" name="nom" id="nom" required>

        <label for="url">URL (site, profil, page...)</label>
        <input type="url" name="url" id="url">

        <label for="photo">Photo (optionnel)</label>
        <input type="file" name="photo" id="photo">

        <div class="form-actions">
            <a href="artistes.php" class="btn-back">← Retour</a>
            <button type="submit" class="btn-save">✅ Ajouter l’artiste</button>
        </div>
    </form>
</main>

</body>
</html>
