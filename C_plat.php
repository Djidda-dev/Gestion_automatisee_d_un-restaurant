<?php
require_once("bdd.php");
//isset($_POST['code']) && 
if(isset($_POST['plats']) && isset($_POST['description']) && isset($_POST['prix']) && isset($_FILES['image'])) {
    $nomplat = $_POST['plats'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $imgplat = $_FILES['image']['name'];

    $destination = 'img/' . $imgplat; // Chemin relatif au répertoire img dans le même dossier que le script C_plat.php

    // Déplacez le fichier téléchargé vers le répertoire de destination
    if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        // Utilisez PDO pour insérer les données dans la base de données
        $sql = "INSERT INTO plats (nom, description, prix, image) VALUES (:nom, :description, :prix, :image)";
        $requete = $bdd->prepare($sql);
        $requete->bindParam(':nom', $nomplat);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':prix', $prix);
        $requete->bindParam(':image', $imgplat);
        
        if($requete->execute()) {
            header("location:affichplat.php");
            //echo "Un nouveau plat a été ajouté avec succès.";
        } else {
            echo "Erreur lors de l'insertion du plat.";
        }
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
} else {
    echo "Tous les champs doivent être remplis.";
}



?>