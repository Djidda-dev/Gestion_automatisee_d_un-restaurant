
<?php
session_start();
require_once("bdd.php"); // Inclure le fichier de connexion à la base de données

// Vérifie si le formulaire de connexion a été soumis
if(isset($_POST['valider'])) {
    if(isset($_POST['email']) && isset($_POST['mdp'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Requête pour vérifier les informations d'authentification
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
            if ($_SESSION['role_utilisateur'] === 'admin') {
                header("Location: admin_dashboard.php"); // Redirection vers la page d'affichage des plats pour les administrateurs
            } else {
                header("Location: affichplat.php"); // Redirection vers la page d'affichage des plats pour les utilisateurs normaux
            }
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .login-form {
            width: 300px;
            margin: 0 auto;
            padding: 30px 0;
            text-align: center;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        .login-form input[type="submit"] {
            padding: 10px 20px;
            background: #333;
            color: #fff;
            border: none;
        }

        .login-form input[type="submit"]:hover {
            background: #444;
        }

        .signup-link {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h1>Connexion</h1>
        <form action="traitement_connexion.php" method="post">
            <input type="text" name="email" class="form-control" placeholder="Adresse Mail" required>
            <input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required>
            <input type="submit" name="valider" class="btn btn-primary" value="Se connecter">
            <a href="inscription.php" class="signup-link">S'inscrire</a>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
