<?php
// Connexion à la base de données
try {
    $bdd = new PDO('mysql:host=localhost;dbname=macantine', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}
?>
