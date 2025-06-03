<?php
// Inclure le fichier de connexion à la base de données
require_once("bdd.php");

// Récupérer les commandes passées
$requeteCommandes = "SELECT id_plat, quantite, date_commande, prix_total, statut FROM commandes ORDER BY date_commande DESC";
$stmtCommandes = $bdd->query($requeteCommandes);
$commandes = $stmtCommandes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historique des commandes - Cantine Scolaire</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mt-5 mb-4">Historique des commandes</h1>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plat</th>
                                <th>Quantité</th>
                                <th>Date</th>
                                <th>Prix Total (€)</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td><?php echo $commande['id_plat']; ?></td>
                                    <td><?php echo $commande['quantite']; ?></td>
                                    <td><?php echo $commande['date_commande']; ?></td>
                                    <td><?php echo $commande['prix_total']; ?></td>
                                    <td><?php echo $commande['statut']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
