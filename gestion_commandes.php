<?php
session_start();
require_once("bdd.php");

// Sécurité : accès réservé à l'admin
if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['role_utilisateur']) || $_SESSION['role_utilisateur'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Traitement du changement de statut
if (isset($_POST['commande_id']) && isset($_POST['nouveau_statut'])) {
    $commande_id = $_POST['commande_id'];
    $nouveau_statut = $_POST['nouveau_statut'];
    $stmt = $bdd->prepare("UPDATE commandes SET statut = :statut WHERE id = :id");
    $stmt->bindParam(':statut', $nouveau_statut);
    $stmt->bindParam(':id', $commande_id);
    $stmt->execute();
}

// Pagination
$parPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$debut = ($page - 1) * $parPage;

// Recherche rapide par email ou plat
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
$params = [];
if ($search !== '') {
    $where = "WHERE u.email LIKE :search OR p.nom LIKE :search";
    $params[':search'] = "%$search%";
}

// Nombre total de commandes (pour pagination)
$countReq = $bdd->prepare("
    SELECT COUNT(*) FROM commandes c
    JOIN utilisateurs u ON c.id_utilisateur = u.id
    JOIN plats p ON c.id_plat = p.id
    $where
");
$countReq->execute($params);
$totalCommandes = $countReq->fetchColumn();
$totalPages = ceil($totalCommandes / $parPage);

// Récupérer les commandes
$req = $bdd->prepare("
    SELECT c.id, c.date_commande, c.prix_total, c.statut, c.quantite, u.email AS utilisateur, p.nom AS plat
    FROM commandes c
    JOIN utilisateurs u ON c.id_utilisateur = u.id
    JOIN plats p ON c.id_plat = p.id
    $where
    ORDER BY c.date_commande DESC
    LIMIT :debut, :parPage
");
foreach ($params as $key => $val) {
    $req->bindValue($key, $val, PDO::PARAM_STR);
}
$req->bindValue(':debut', $debut, PDO::PARAM_INT);
$req->bindValue(':parPage', $parPage, PDO::PARAM_INT);
$req->execute();
$commandes = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des commandes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container mx-auto py-10 px-4">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-tasks"></i> Gestion des commandes</h2>
        <a href="admin_dashboard.php" class="inline-block px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900 transition"><i class="fas fa-arrow-left"></i> Dashboard</a>
    </div>
    <form class="flex flex-col md:flex-row gap-2 mb-4" method="get">
        <input class="w-full md:w-auto px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400" type="search" name="search" placeholder="Recherche email ou plat..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="px-4 py-2 rounded bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition" type="submit"><i class="fas fa-search"></i> Rechercher</button>
        <?php if ($search !== ''): ?>
            <a href="gestion_commandes.php" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition">Réinitialiser</a>
        <?php endif; ?>
    </form>
    <div class="overflow-x-auto shadow rounded-xl bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Plat</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Quantité</th>
                    <th class="px-6 py-3 text-right text-xs font-bold uppercase">Prix Total</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Statut</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($commandes as $cmd): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo date('d/m/Y H:i', strtotime($cmd['date_commande'])); ?></td>
                    <td class="px-6 py-4"><i class="fas fa-user"></i> <?php echo htmlspecialchars($cmd['utilisateur']); ?></td>
                    <td class="px-6 py-4"><i class="fas fa-utensils"></i> <?php echo htmlspecialchars($cmd['plat']); ?></td>
                    <td class="px-6 py-4 text-center"><?php echo $cmd['quantite']; ?></td>
                    <td class="px-6 py-4 text-right"><?php echo number_format($cmd['prix_total'], 0, ',', ' '); ?> FCFA</td>
                    <td class="px-6 py-4 text-center">
                        <?php
                            $statut = strtolower($cmd['statut']);
                            if ($statut == 'en attente') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-yellow-400 text-gray-900 text-xs font-semibold">En attente</span>';
                            } elseif ($statut == 'validée' || $statut == 'validee') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-green-500 text-white text-xs font-semibold">Validée</span>';
                            } elseif ($statut == 'refusée' || $statut == 'refusee') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-red-500 text-white text-xs font-semibold">Refusée</span>';
                            } else {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-gray-400 text-white text-xs font-semibold">'.htmlspecialchars($cmd['statut']).'</span>';
                            }
                        ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($statut == 'en attente'): ?>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="commande_id" value="<?php echo $cmd['id']; ?>">
                                <button name="nouveau_statut" value="validée" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600 transition text-xs" title="Valider"><i class="fas fa-check"></i></button>
                                <button name="nouveau_statut" value="refusée" class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600 transition text-xs" title="Refuser"><i class="fas fa-times"></i></button>
                            </form>
                        <?php else: ?>
                            <span class="text-gray-400 text-xs"><i class="fas fa-ban"></i> Aucune action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($commandes)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">Aucune commande trouvée.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-center mt-6 space-x-2">
        <?php if($page > 1): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">&laquo; Précédent</a>
        <?php endif; ?>
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="px-3 py-1 rounded <?php if($i == $page) echo 'bg-yellow-500 text-white'; else echo 'bg-gray-200 hover:bg-gray-300'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if($page < $totalPages): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page+1])); ?>" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Suivant &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
</body>
</html>