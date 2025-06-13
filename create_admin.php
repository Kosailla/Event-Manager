<?php
require_once 'includes/db.php'; 

$username = 'kosailla';
$email = 'kosailla.abdelouhab@gmail.com';
$password = password_hash('root', PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO organisateurs (username, email, mot_de_passe) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);
    echo "✅ Utilisateur créé avec succès.";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>
