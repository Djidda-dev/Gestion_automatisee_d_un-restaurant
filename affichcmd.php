<?php
session_start();
require_once("bdd.php");

// Pagination
$parPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$debut = ($page - 1) * $parPage;

// Nombre total de commandes
$totalCommandes = $bdd->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$totalPages = ceil($totalCommandes / $parPage);

// Récupérer les commandes avec jointure pour le nom du plat
$requeteCommandes = "
    SELECT c.id_plat, c.quantite, c.date_commande, c.prix_total, c.statut, p.nom AS nom_plat
    FROM commandes c
    JOIN plats p ON c.id_plat = p.id
    ORDER BY c.date_commande DESC
    LIMIT :debut, :parPage
";
$stmtCommandes = $bdd->prepare($requeteCommandes);
$stmtCommandes->bindValue(':debut', $debut, PDO::PARAM_INT);
$stmtCommandes->bindValue(':parPage', $parPage, PDO::PARAM_INT);
$stmtCommandes->execute();
$commandes = $stmtCommandes->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des commandes - Cantine Scolaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Historique des commandes</h1>
        <div class="overflow-x-auto shadow rounded-xl bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Plat</th>
                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase">Prix Total (FCFA)</th>
                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($commande['nom_plat']); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $commande['quantite']; ?></td>
                            <td class="px-6 py-4"><?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?></td>
                            <td class="px-6 py-4 text-right"><?php echo number_format($commande['prix_total'], 0, ',', ' '); ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                    $statut = strtolower($commande['statut']);
                                    if ($statut == 'en attente') {
                                        echo '<span class="inline-block px-3 py-1 rounded-full bg-yellow-400 text-gray-900 text-xs font-semibold">En attente</span>';
                                    } elseif ($statut == 'validée' || $statut == 'validee') {
                                        echo '<span class="inline-block px-3 py-1 rounded-full bg-green-500 text-white text-xs font-semibold">Validée</span>';
                                    } elseif ($statut == 'refusée' || $statut == 'refusee') {
                                        echo '<span class="inline-block px-3 py-1 rounded-full bg-red-500 text-white text-xs font-semibold">Refusée</span>';
                                    } else {
                                        echo '<span class="inline-block px-3 py-1 rounded-full bg-gray-400 text-white text-xs font-semibold">'.htmlspecialchars($commande['statut']).'</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($commandes)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">Aucune commande trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-6 space-x-2">
            <?php if($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?>" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">&laquo; Précédent</a>
            <?php endif; ?>
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-3 py-1 rounded <?php if($i == $page) echo 'bg-yellow-500 text-white'; else echo 'bg-gray-200 hover:bg-gray-300'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if($page < $totalPages): ?>
                <a href="?page=<?php echo $page+1; ?>" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Suivant &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
