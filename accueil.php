<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant</title>
    <style>
        /* Styles pour le corps de la page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Styles pour la barre de navigation */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        /* Styles pour les liens de la barre de navigation */
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        /* Styles pour les liens de la barre de navigation lorsqu'ils sont survolés */
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Styles pour l'en-tête */
        .header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        /* Styles pour la section principale */
        .main {
            margin: 15px;
            text-align: center;
        }

        /* Styles pour les titres */
        h1 {
            color: #333;
        }

        /* Styles pour les paragraphes */
        p {
            color: #666;
        }

        /* Styles pour les boutons */
        .btn {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            margin: 20px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #444;
        }

        /* Styles pour le carrousel d'images */
        .carousel {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 300px; /* Ajustez la hauteur selon vos besoins */
        }

        .carousel__image-container {
            display: flex;
            width: 100%;
            height: 100%;
            animation: carousel 30s infinite linear;
        }

        .carousel__image {
            max-width: 100%;
            height: auto;
            margin: 10px;
            padding: 5px;
        }

        @keyframes carousel {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="accueil.php">Accueil</a>
        <a href="affichplat.php">Plats</a>
        <a href="#">Contact</a>
    </div>
    <div class="header">
        <h1>Bienvenue à la Cantine</h1>
    </div>
    <div class="main">
        <h1>Des repas délicieux vous attendent !</h1>
        <p>Connectez-vous pour voir le menu du jour et passer votre commande.</p>
        <a href="authentification.php" class="btn">Se connecter</a>
        <div class="carousel">
            <div class="carousel__image-container">
                <img src="img/menu-burger.jpg" alt="Image 1" class="carousel__image">
                <img src="img/menu-burger.jpg" alt="Image 2" class="carousel__image">
                <img src="img/momo.jpg" alt="Image 1" class="carousel__image">
                <img src="img/menu-momo.jpg" alt="Image 2" class="carousel__image">
                <img src="img/momo.jpg" alt="Image 1" class="carousel__image">
                <img src="img/menu-momo.jpg" alt="Image 2" class="carousel__image">
                <!-- Ajoutez autant d'images que vous le souhaitez -->
            </div>
        </div>
    </div>
</body>
</html>
