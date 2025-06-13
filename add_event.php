<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Récupérer les lieux et artistes
$venues = $pdo->query("SELECT id, nom FROM Venue ORDER BY nom")->fetchAll();
$artistes = $pdo->query("SELECT id, nom FROM Artiste ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_heure = $_POST['date_heure'];
    $prix = $_POST['prix'];
    $id_1 = $_POST['venue_id'];
    $id_2 = $_POST['artiste_id'];
    $photo = null;

    // Gérer l’upload d’image
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $filename = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $photo = $filename;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO Event (titre, description, date_heure, prix, id_1, id_2, photo) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $date_heure, $prix, $id_1, $id_2, $photo]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un événement</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        form {
            max-width: 700px;
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
        input[type="datetime-local"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
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
            content: "🗓️ ";
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
    <h1>Ajouter un événement</h1>

    <form method="post" enctype="multipart/form-data">
        <label for="titre">Titre de l’événement</label>
        <input type="text" name="titre" id="titre" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"></textarea>

        <label for="date_heure">Date et heure</label>
        <input type="datetime-local" name="date_heure" id="date_heure" required>

        <label for="prix">Prix (€)</label>
        <input type="number" name="prix" id="prix" step="0.01" required>

        <label for="venue_id">Lieu (venue)</label>
        <select name="venue_id" id="venue_id" required>
            <option value="">-- Choisir un lieu --</option>
            <?php foreach ($venues as $venue): ?>
                <option value="<?= $venue['id'] ?>"><?= htmlspecialchars($venue['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="artiste_id">Artiste</label>
        <select name="artiste_id" id="artiste_id" required>
            <option value="">-- Choisir un artiste --</option>
            <?php foreach ($artistes as $artiste): ?>
                <option value="<?= $artiste['id'] ?>"><?= htmlspecialchars($artiste['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="photo">Image de l’événement (optionnelle)</label>
        <input type="file" name="photo" id="photo">

        <div class="form-actions">
            <a href="dashboard.php" class="btn-back">← Retour</a>
            <button type="submit" class="btn-save">✅ Ajouter l’événement</button>
        </div>
    </form>
</main>

</body>
</html>
