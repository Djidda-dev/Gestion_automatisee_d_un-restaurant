<?php
// Définir une variable pour stocker le message
$message = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifie si les données nécessaires ont été reçues
    if (isset($_POST['menu']) && isset($_POST['quantite'])) {
        // Inclure le fichier de connexion à la base de données
        require_once("bdd.php");

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
            $requeteCommande = "INSERT INTO commandes (id_plat, quantite, date_commande, statut, prix_total) VALUES (:menuId,$quantite ,NOW(), 'en attente', :prixTotal)";
$requeteCommande = "INSERT INTO commandes (id_plat, quantite, date_commande, statut, prix_total) VALUES (:menuId, :quantite, NOW(), 'en attente', :prixTotal)";
$stmtCommande = $bdd->prepare($requeteCommande);
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
        $message = "Tous les champs du formulaire doivent être remplis.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Passer une commande - Cantine Scolaire</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .order-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-form h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style pour le texte de confirmation en vert */
        .text-success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="order-form">
                    <h1>Passer une commande</h1>
                    <!-- Afficher le message -->
                    <div class="message <?php echo $message ? 'text-success' : ''; ?>"><?php echo $message; ?></div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!-- Votre formulaire ici -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

