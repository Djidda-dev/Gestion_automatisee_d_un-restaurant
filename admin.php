<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord de l'administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Ajoutez vos styles personnalisés ici si nécessaire */
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        p {
            margin-bottom: 20px;
            color: #555;
        }
        a {
            display: block;
            margin-bottom: 10px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue dans le tableau de bord de l'administrateur</h1>
        <!-- Ajoutez le contenu du tableau de bord ici -->
        <p>Vous êtes connecté en tant qu'administrateur.</p>
        <p>Ajoutez ici les fonctionnalités et les liens nécessaires pour gérer le système.</p>
        <a href="passer_commande.php">Passer une commande</a> <!-- Exemple de lien pour passer une commande -->
        <a href="affichcmd.php">Historique de commande</a>
        <a href="affichplat.php">Gestion des plats</a>
        <a href="deconnexion.php">Déconnexion</a> <!-- Lien pour se déconnecter -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Ajoutez d'autres scripts JavaScript ici si nécessaire -->
</body>
</html>
