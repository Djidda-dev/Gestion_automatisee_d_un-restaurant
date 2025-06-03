<?php
session_start();
require_once("bdd.php"); // Inclure le fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: login.php");
    exit();
}

// Traitement du formulaire de commande
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifie si les données nécessaires ont été reçues
    if (isset($_POST['menu']) && isset($_POST['quantite'])) {
        // Récupère les données du formulaire
        $menuId = $_POST['menu'];
        $quantite = $_POST['quantite'];

        // Requête pour obtenir les détails du plat sélectionné
        $requeteMenu = "SELECT * FROM plats WHERE id = :menuId";
        $stmtMenu = $bdd->prepare($requeteMenu);
        $stmtMenu->bindParam(':menuId', $menuId, PDO::PARAM_INT);
        $stmtMenu->execute();
        $plat = $stmtMenu->fetch(PDO::FETCH_ASSOC);

        // Vérifie si le plat existe
        if ($plat) {
            // Calculer le prix total de la commande
            $prixTotal = $plat['prix'] * $quantite;

            // Requête pour insérer la commande dans la base de données
            $requeteCommande = "INSERT INTO commandes (id_utilisateur, id_plat, quantite, date_commande, statut, prix_total) 
                                VALUES (:idUtilisateur, :menuId, :quantite, NOW(), 'en attente', :prixTotal)";
            $stmtCommande = $bdd->prepare($requeteCommande);
            $stmtCommande->bindParam(':idUtilisateur', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
            $stmtCommande->bindParam(':menuId', $menuId, PDO::PARAM_INT);
            $stmtCommande->bindParam(':quantite', $quantite, PDO::PARAM_INT); // Lier la quantité ici
            $stmtCommande->bindParam(':prixTotal', $prixTotal, PDO::PARAM_STR);

            // Exécute la requête d'insertion
            if ($stmtCommande->execute()) {
                // Affiche un message de confirmation
                $message = "Votre commande a été passée avec succès !";
            } else {
                $message = "Erreur lors de l'insertion de la commande.";
            }
        } else {
            $message = "Plat non trouvé dans la base de données.";
        }
    } else {
        $message = "Votre commande a été passée avec succès !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une commande</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .confirmation-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirmation-message {
            text-align: center;
            padding: 20px;
            border: 2px solid green;
            border-radius: 50%;
            width: 200px; /* Largeur fixe pour limiter le texte */
        }
        .confirmation-message::before {
            content: "\2713"; /* Code unicode du symbole check (✓) */
            font-size: 60px;
            color: green;
            display: block;
            margin-bottom: 20px;
        }
        .confirmation-text {
            font-size: 14px; /* Taille de police plus petite */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center">Passer une commande</h1>
                        <!-- Afficher le message de confirmation -->
                        <?php if (!empty($message)): ?>
                        <div class="confirmation-message">
                            <div class="confirmation-icon"></div>
                            <div class="confirmation-text"><?php echo $message; ?></div><br>
                            
                        </div>
                        <a href="passer_commande.php" class="btn btn-primary mt-3">Passer une nouvelle commande</a>
                        <?php else: ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <!-- Liste déroulante pour sélectionner le plat -->
                            <div class="form-group">
                                <label for="menu">Sélectionnez un plat :</label>
                                <select class="form-control" name="menu" id="menu" required>
                                    <option value="">Choisir un plat...</option>
                                    <?php
                                    // Requête pour obtenir la liste des plats disponibles
                                    $sql = "SELECT * FROM plats";
                                    $resultat = $bdd->query($sql);

                                    // Affichage des options pour chaque plat
                                    while($plat = $resultat->fetch(PDO::FETCH_ASSOC)) {
                                        // Création d'une option avec le nom, le prix et le chemin de l'image
                                        echo "<option value='".$plat['id']."' data-image='img/".$plat['image']."'>".$plat['nom']." - ".$plat['prix']." fcfa</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Affichage de l'image du plat sélectionné -->
                            <div class="text-center">
                                <img src="" alt="Image du plat" id="platImage" class="img-fluid mb-3" style="max-height: 200px;">
                            </div>

                            <!-- Champ pour saisir la quantité -->
                            <div class="form-group">
                                <label for="quantite">Quantité :</label>
                                <input type="number" name="quantite" id="quantite" class="form-control" min="1" required>
                            </div>

                            <!-- Bouton pour soumettre le formulaire -->
                            <button type="submit" class="btn btn-primary btn-block">Passer la commande</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS et jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Écouter les changements de sélection dans la liste déroulante
    document.getElementById('menu').addEventListener('change', function() {
        // Récupérer l'option sélectionnée
        var selectedOption = this.options[this.selectedIndex];
        
        // Récupérer le chemin de l'image à partir de l'attribut data-image
        var imagePath = selectedOption.getAttribute('data-image');
        
        // Mettre à jour l'attribut src de l'image avec le chemin de l'image du plat sélectionné
        document.getElementById('platImage').src = imagePath;
    });
    </script>
</body>
</html>
