<?php
require_once("bdd.php");

if(isset($_POST['plat_id']) && isset($_POST['nom_plat']) && isset($_POST['description']) && isset($_POST['prix'])) {
    $plat_id = $_POST['plat_id'];
    $nom_plat = $_POST['nom_plat'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];

    // Vérifier si une nouvelle image a été téléchargée
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $imgplat = $_FILES['image']['name'];
        $destination = 'img/' . $imgplat; // Chemin relatif au répertoire img dans le même dossier que le script update_plat.php

        // Déplacez le fichier téléchargé vers le répertoire de destination
        if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            // Mettre à jour les données dans la base de données avec la nouvelle image
            $sql = "UPDATE plats SET nom = :nom, description = :description, prix = :prix, image = :image WHERE id = :id";
            $requete = $bdd->prepare($sql);
            $requete->bindParam(':nom', $nom_plat);
            $requete->bindParam(':description', $description);
            $requete->bindParam(':prix', $prix);
            $requete->bindParam(':image', $imgplat);
            $requete->bindParam(':id', $plat_id);
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            exit();
        }
    } else {
        // Aucune nouvelle image téléchargée, mettre à jour les autres données sans changer l'image
        $sql = "UPDATE plats SET nom = :nom, description = :description, prix = :prix WHERE id = :id";
        $requete = $bdd->prepare($sql);
        $requete->bindParam(':nom', $nom_plat);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':prix', $prix);
        $requete->bindParam(':id', $plat_id);
    }

    if($requete->execute()) {
        header("location: affichplat.php");
        //echo "Les modifications ont été enregistrées avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du plat.";
    }
} else {
    echo "Tous les champs doivent être remplis.";
}
?>
