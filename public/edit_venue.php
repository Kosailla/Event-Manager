<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM Venue WHERE id = ?");
$stmt->execute([$id]);
$venue = $stmt->fetch();

if (!$venue) {
    echo "Lieu introuvable.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $type = $_POST['type'];
    $adresse = $_POST['adresse'];
    $url = $_POST['url'];

    // Gestion de la photo
    $photo = $venue['photo']; // valeur actuelle par défaut

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

    $stmt = $pdo->prepare("UPDATE Venue SET nom = ?, type = ?, adresse = ?, url = ?, photo = ? WHERE id = ?");
    $stmt->execute([$nom, $type, $adresse, $url, $photo, $id]);

    header("Location: venues.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un lieu</title>
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
            background: #007bff;
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
            content: "🖊️ ";
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
    <h1>Modifier un lieu</h1>

    <form method="post" enctype="multipart/form-data">
        <label for="nom">Nom du lieu</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($venue['nom']) ?>" required>

        <label for="type">Type de lieu</label>
        <input type="text" name="type" id="type" value="<?= htmlspecialchars($venue['type']) ?>" required>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($venue['adresse']) ?>" required>

        <label for="url">URL du site (optionnel)</label>
        <input type="url" name="url" id="url" value="<?= htmlspecialchars($venue['url']) ?>">

        <label for="photo">Changer la photo (optionnel)</label>
        <input type="file" name="photo" id="photo">
        <small>Laisse vide pour conserver la photo actuelle.</small>

        <br><br>
        <strong>Photo actuelle :</strong><br>
        <?= htmlspecialchars($venue['photo']) ?>

        <div class="form-actions">
            <a href="venues.php" class="btn-back">← Retour</a>
            <button type="submit" class="btn-save">💾 Enregistrer les modifications</button>
        </div>
    </form>
</main>

</body>
</html>
