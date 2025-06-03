<!-- <?php
require_once("bdd.php");
session_start();

// Vérifie si le formulaire de connexion a été soumis
if(isset($_POST['valider'])) {
    if(isset($_POST['email']) && isset($_POST['mdp'])) {
        $mail = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Requête pour vérifier les informations d'authentification
        $requete = "SELECT id, mdp FROM utilisateurs WHERE email=:email";
        $stmt = $bdd->prepare($requete);
        $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
        $stmt->execute();
        
        // Récupération du résultat de la requête
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultat && password_verify($mdp, $resultat['mdp'])) {
            // Authentification réussie
            $_SESSION['id_utilisateur'] = $resultat['id'];
            if(isset($_SESSION["commande_en_cours"])) {
                // Redirection vers la page de commande en cours
                header("location: ".$_SESSION["commande_en_cours"]);
            } else {
                // Redirection vers la page traitement_commande.php après l'authentification
                header("location: traitement_commande.php");
            }
            exit();
        } else {
            // En cas d'échec d'authentification, définissez un message d'erreur
            $erreur = "Adresse ou mot de passe non valide";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion - Application Cantine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
    </style>
</head>
<body>
    <div class="login-form">
        <h1>Connexion</h1>
        <form action="" method="post">
            <input type="text" name="email" placeholder="Adresse Mail" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <input type="submit" name="valider" value="Se connecter">
        </form>
        <?php if(isset($erreur)) { ?>
            <p style="color: red;"><?php echo $erreur; ?></p>
        <?php } ?>
    </div>
</body>
</html> -->
