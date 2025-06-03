<?php
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifie si les données nécessaires ont été reçues
    if (isset($_POST['email']) && isset($_POST['mdp'])) {
        // Inclure le fichier de connexion à la base de données
        require_once("bdd.php");

        // Récupère les données du formulaire
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Requête pour vérifier si l'e-mail est déjà utilisé
        $requeteEmailExistant = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmtEmailExistant = $bdd->prepare($requeteEmailExistant);
        $stmtEmailExistant->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtEmailExistant->execute();

        // Vérifie si l'e-mail existe déjà dans la base de données
        if ($stmtEmailExistant->rowCount() > 0) {
            // L'e-mail est déjà utilisé, affiche un message d'erreur
            echo "L'adresse e-mail est déjà utilisée. Veuillez choisir une autre adresse e-mail.";
        } else {
            // L'e-mail n'est pas déjà utilisé, procéder à l'insertion dans la base de données
            $requeteInscription = "INSERT INTO utilisateurs (email, mdp) VALUES (:email, :mdp)";
            $stmtInscription = $bdd->prepare($requeteInscription);
            $stmtInscription->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtInscription->bindParam(':mdp', $mdp, PDO::PARAM_STR);

            // Exécute la requête d'insertion
            if ($stmtInscription->execute()) {
                // Affiche un message de succès
                echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                // Affiche un message d'erreur en cas d'échec de l'insertion
                echo "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    } else {
        // Affiche un message si des champs sont manquants dans le formulaire
        echo "Tous les champs du formulaire doivent être remplis.";
    }
}
?>
