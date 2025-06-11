<?php
session_start();
require_once("bdd.php");

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$plat = null;

// Si on reçoit plat_id en POST sans quantité, afficher le formulaire de quantité
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plat_id']) && !isset($_POST['quantite'])) {
    $plat_id = $_POST['plat_id'];
    $stmt = $bdd->prepare("SELECT * FROM plats WHERE id = :id");
    $stmt->bindParam(':id', $plat_id, PDO::PARAM_INT);
    $stmt->execute();
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$plat) {
        $message = "Plat non trouvé.";
    }
}

// Si on reçoit plat_id et quantite en POST, traiter la commande
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plat_id']) && isset($_POST['quantite'])) {
    $plat_id = $_POST['plat_id'];
    $quantite = $_POST['quantite'];
    $stmt = $bdd->prepare("SELECT * FROM plats WHERE id = :id");
    $stmt->bindParam(':id', $plat_id, PDO::PARAM_INT);
    $stmt->execute();
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($plat) {
        $prixTotal = $plat['prix'] * $quantite;
        $requeteCommande = "INSERT INTO commandes (id_utilisateur, id_plat, quantite, date_commande, statut, prix_total) 
                            VALUES (:idUtilisateur, :plat_id, :quantite, NOW(), 'en attente', :prixTotal)";
        $stmtCommande = $bdd->prepare($requeteCommande);
        $stmtCommande->bindParam(':idUtilisateur', $_SESSION['id_utilisateur'], PDO::PARAM_INT);
        $stmtCommande->bindParam(':plat_id', $plat_id, PDO::PARAM_INT);
        $stmtCommande->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $stmtCommande->bindParam(':prixTotal', $prixTotal, PDO::PARAM_STR);
        if ($stmtCommande->execute()) {
            $message = "Votre commande a été passée avec succès !";
        } else {
            $message = "Erreur lors de l'insertion de la commande.";
            print_r($stmtCommande->errorInfo()); // Affiche l’erreur SQL
        }
    } else {
        $message = "Plat non trouvé dans la base de données.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Passer une commande</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if ($message): ?>
        <div class="alert alert-success text-center"><?php echo $message; ?></div>
        <a href="affichplat.php" class="btn btn-primary">Retour aux plats</a>
    <?php elseif ($plat): ?>
        <h2><?php echo htmlspecialchars($plat['nom']); ?></h2>
        <img src="img/<?php echo htmlspecialchars($plat['image']); ?>" alt="" style="max-width:200px;">
        <p><?php echo htmlspecialchars($plat['description']); ?></p>
        <p>Prix unitaire : <strong id="prix_unitaire"><?php echo $plat['prix']; ?></strong> FCFA</p>
        <form method="POST" action="passer_commande.php">
            <input type="hidden" name="plat_id" value="<?php echo $plat['id']; ?>">
            <div class="form-group">
                <label for="quantite">Quantité :</label>
                <input type="number" name="quantite" id="quantite" class="form-control" min="1" value="1" required>
            </div>
            <p><strong>Prix total : <span id="prix_total"><?php echo $plat['prix']; ?></span> FCFA</strong></p>
            <button type="submit" class="btn btn-success">Valider la commande</button>
        </form>
        <script>
            const prixUnitaire = <?php echo $plat['prix']; ?>;
            const quantiteInput = document.getElementById('quantite');
            const prixTotalSpan = document.getElementById('prix_total');
            quantiteInput.addEventListener('input', function() {
                let qte = parseInt(quantiteInput.value) || 1;
                prixTotalSpan.textContent = prixUnitaire * qte;
            });
        </script>
    <?php else: ?>
        <div class="alert alert-danger text-center">Aucun plat sélectionné.</div>
        <a href="affichplat.php" class="btn btn-primary">Retour aux plats</a>
    <?php endif; ?>
</div>
</body>
</html>
