<?php
session_start();
require_once("bdd.php");

// Vérifie si l'utilisateur est connecté et s'il est administrateur
$est_admin = false;
if(isset($_SESSION['id_utilisateur'])) {
    $id_utilisateur = $_SESSION['id_utilisateur'];
    if(isset($_SESSION['role_utilisateur']) && $_SESSION['role_utilisateur'] == 'admin') {
        $est_admin = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les plats disponibles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>

                /* Styles pour la section du profil */
                .profil {
            background-color: #333;
            color: #fff;
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999; /* Assure que la section du profil soit au-dessus du contenu */
        }
        .profil span {
            font-size: 18px;
        }

        /* Styles pour le contenu de la page */
        .content {
            margin-top: 50px; /* Ajuste cette valeur pour laisser de l'espace sous la section du profil */
        }
        .navbar {
            margin-top: 50px; /* Ajoute une marge en haut de la barre de navigation pour éviter le chevauchement */
        }
        /* Ajoutez vos styles personnalisés ici si nécessaire */
        .card {
            margin-bottom: 20px;
        }
        .card img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.2s; /* Transition de transformation pour une animation fluide */
        }
        .card img:hover {
            transform: scale(1.1); /* Agrandir l'image au survol */
        }
        .card-title {
            max-height: 50px; /* Hauteur maximale pour le titre */
            overflow: hidden; /* Masquer le contenu dépassant */
        }
        .card-text {
            max-height: 100px; /* Hauteur maximale pour la description */
            overflow: hidden; /* Masquer le contenu dépassant */
        }
        .btn-container {
            display: flex;
            justify-content: center; /* Aligner les éléments horizontalement au centre */
            margin-top: 10px;
        }
        .btn-container button {
            flex-grow: 1;
            margin: 0 5px;
        }
        .btn-container button i {
            margin-right: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }
        .btn-commander {
        margin-top: 100px; /* Ajustez cette valeur en fonction de vos besoins */
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

    </style>
</head>
<body>

<div class="navbar">
    <a href="cantine.html">Accueil</a>
    <a href="contact.php">Contact</a>

    <?php if(isset($_SESSION['id_utilisateur'])): ?>
        <a href="logout.php">Déconnexion</a>
    <?php endif; ?>
    <?php if($est_admin): ?>
        <a href="ajoutplat.php">Ajouter une nouvelle plat</a>
        <a href="affichcmd.php">Historique des commandes</a>
    <?php endif; ?>
    <form class="form-inline ml-auto" action="recherche.php" method="GET">
        <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Rechercher" name="q">
        <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Rechercher</button>
    </form>
</div>
<div class="container">
    <!-- Ajoutez le profil de l'utilisateur ou de l'administrateur ici -->
<!-- Section du profil stylisée -->
<div class="profil">
    <span>
        <?php 
        if($est_admin) {
            echo "Profil Administrateur";
        } else {
            echo "Profil Utilisateur";
        }
        ?>
    </span>
</div>
<div class="container">
    <h3 class="mt-5 mb-4 text-center">NOS MENUS DISPONIBLES</h3>
    <div class="row">
        <?php
        require_once("bdd.php");
        $requete = "SELECT * FROM plats";
        $resultat = $bdd->query($requete);
        $Afficher = $resultat->fetchAll();
        $i = 0;
        foreach ($Afficher as $plats) {
            if ($i % 3 == 0) {
                echo '</div><div class="row">';
            }
        ?>
        <div class="col-lg-4">
            <div class="card">
                <img src="img/<?php echo $plats['image']; ?>" class="card-img-top" alt="<?php echo $plats['nom']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $plats['nom']; ?></h5>
                    <p class="card-text"><?php echo $plats['description']; ?></p>
                    <p class="card-text"><strong>Prix: <?php echo $plats['prix']; ?> FCFA</strong></p>
                    <div class="btn-container">
                        <?php if($est_admin): ?>
                            <form action="modifier_plat.php" method="POST">
                                <input type="hidden" name="plat_id" value="<?php echo $plats['id']; ?>">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                            </form>
                            <form action="Suppplat.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plat?');">
                                <input type="hidden" name="plat_id" value="<?php echo $plats['id']; ?>">
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </form>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['id_utilisateur'])): ?>
                            <form action="passer_commande.php" method="POST">
                                <input type="hidden" name="plat_id" value="<?php echo $plats['id']; ?>">
                                <button type="submit" class="btn btn-success btn-commander">
                                    <i class="fas fa-utensils"></i> Commander
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-warning">
                                Se connecter pour commander
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $i++;
        }
        ?>
    </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
