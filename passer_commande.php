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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-100 to-yellow-300 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-center">
                <?php echo $message; ?>
            </div>
            <div class="text-center mt-4">
                <a href="affichplat.php" class="inline-block px-6 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition"><i class="fas fa-arrow-left"></i> Retour aux plats</a>
            </div>
        <?php elseif ($plat): ?>
            <h2 class="text-2xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($plat['nom']); ?></h2>
            <div class="flex flex-col items-center mb-4">
                <img src="img/<?php echo htmlspecialchars($plat['image']); ?>" alt="" class="w-48 h-36 object-cover rounded-lg shadow mb-2">
                <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($plat['description']); ?></p>
                <p class="mb-2">Prix unitaire : <strong id="prix_unitaire"><?php echo $plat['prix']; ?></strong> FCFA</p>
            </div>
            <form method="POST" action="passer_commande.php" class="space-y-4">
                <input type="hidden" name="plat_id" value="<?php echo $plat['id']; ?>">
                <div>
                    <label for="quantite" class="block font-semibold mb-1">Quantité :</label>
                    <input type="number" name="quantite" id="quantite" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" min="1" value="1" required>
                </div>
                <p class="font-semibold">Prix total : <span id="prix_total"><?php echo $plat['prix']; ?></span> FCFA</p>
                <button type="submit" class="w-full py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition">Valider la commande</button>
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center">
                Aucun plat sélectionné.
            </div>
            <div class="text-center mt-4">
                <a href="affichplat.php" class="inline-block px-6 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition"><i class="fas fa-arrow-left"></i> Retour aux plats</a>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
