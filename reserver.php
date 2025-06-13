<?php
require_once 'includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer l'événement
$stmt = $pdo->prepare("SELECT Event.*, Venue.nom AS lieu_nom FROM Event JOIN Venue ON Event.id_1 = Venue.id WHERE Event.id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Événement introuvable.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom_complet'];
    $quantite = (int)$_POST['quantite'];
    $prix_total = $event['prix'] * $quantite;
    $code_unique = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
    $date_reservation = date('Y-m-d H:i:s');
    $utilise = 0;

    $stmt = $pdo->prepare("INSERT INTO Ticket (prix_total, prix_personne, utilise, date_reservation, nom_complet, code_unique, quantite, id_1) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $prix_total,
        number_format($event['prix'], 2),
        $utilise,
        $date_reservation,
        $nom,
        $code_unique,
        $quantite,
        $event['id']
    ]);

    header("Location: confirmation.php?code=$code_unique");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver – <?= htmlspecialchars($event['titre']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #f8f9fa; font-family: Arial, sans-serif; }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        p { margin: 8px 0; }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .btn {
            margin-top: 20px;
            background: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover { background: #0056b3; }

        .event-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 6px;
            background: #e9ecef;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🎫 Réserver pour : <?= htmlspecialchars($event['titre']) ?></h1>

    <?php
    $imagePath = !empty($event['photo']) ? 'uploads/' . htmlspecialchars($event['photo']) : 'https://via.placeholder.com/600x300?text=Aucune+image';
    ?>
    <img src="<?= $imagePath ?>" alt="Image de l’événement" class="event-image">

    <p><strong>Date :</strong> <?= date('d F Y à H:i', strtotime($event['date_heure'])) ?></p>
    <p><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu_nom']) ?></p>
    <p><strong>Prix par personne :</strong> <?= ($event['prix'] == 0) ? 'Gratuit' : number_format($event['prix'], 2) . ' €' ?></p>

    <form method="post">
        <label for="nom_complet">Nom complet</label>
        <input type="text" name="nom_complet" id="nom_complet" required>

        <label for="quantite">Quantité</label>
        <input type="number" name="quantite" id="quantite" min="1" max="10" value="1" required>

        <button type="submit" class="btn">✅ Réserver maintenant</button>
    </form>
</div>

</body>
</html>
