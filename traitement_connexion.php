<?php
session_start();
require_once("bdd.php"); // Inclure le fichier de connexion à la base de données

// Vérifie si le formulaire de connexion a été soumis
if(isset($_POST['valider'])) {
    if(isset($_POST['email']) && isset($_POST['mdp'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Requête pour vérifier les informations d'authentification et le rôle de l'utilisateur
        $requete = "SELECT id, role FROM utilisateurs WHERE email=:email AND mdp=:mdp";
        $stmt = $bdd->prepare($requete);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $stmt->execute();
        
        // Récupération du résultat de la requête
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            // Authentification réussie, enregistrer l'ID de l'utilisateur et son rôle dans la session
            $_SESSION['id_utilisateur'] = $utilisateur['id'];
            $_SESSION['role_utilisateur'] = $utilisateur['role'];
            header("Location: affichplat.php");
            //header("Location: passer_commande.php"); // Redirection vers la page de commande
            exit();
        } else {
            // En cas d'échec d'authentification, rediriger vers la page de connexion avec un message d'erreur
            $_SESSION['erreur_auth'] = "Adresse ou mot de passe incorrect.";
            header("Location: login.php");
            exit();
        }
    }
}
?>
