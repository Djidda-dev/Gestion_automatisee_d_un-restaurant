<?php
require_once("bdd.php");

if(isset($_POST['plat_id'])) {
    $plat_id = $_POST['plat_id'];

    // Utilisez PDO pour supprimer l'entrée de la base de données
    $sql = "DELETE FROM plats WHERE id = :id";
    $requete = $bdd->prepare($sql);
    $requete->bindParam(':id', $plat_id);
    
    if($requete->execute()) {
        header("location:affichplat.php");
        //echo "Le plat a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du plat.";
    }
} else {
    echo "ID du plat non spécifié.";
}
?>
