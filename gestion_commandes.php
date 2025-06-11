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
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestion des commandes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .badge-en-attente { background: #ffc107; color: #212529; }
        .badge-validee { background: #28a745; }
        .badge-refusee { background: #dc3545; }
        .table thead th { background: #343a40; color: #fff; }
        .table td, .table th { vertical-align: middle; }
        .search-bar { max-width: 350px; }
        .pagination { justify-content: center; }
        .action-btns form { display: inline; }
        .action-btns .btn { margin-right: 0.2rem; }
        .table-responsive { box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 1rem; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-tasks"></i> Gestion des commandes</h2>
        <a href="admin_dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Dashboard</a>
    </div>
    <form class="form-inline mb-3" method="get">
        <input class="form-control mr-2 search-bar" type="search" name="search" placeholder="Recherche email ou plat..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-info" type="submit"><i class="fas fa-search"></i> Rechercher</button>
        <?php if ($search !== ''): ?>
            <a href="gestion_commandes.php" class="btn btn-outline-secondary ml-2">Réinitialiser</a>
        <?php endif; ?>
    </form>
    <table class="table table-bordered table-hover bg-white">
        <thead class="thead-dark">
            <tr>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Plat</th>
                <th>Quantité</th>
                <th>Prix Total</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($commandes as $cmd): ?>
            <tr>
                <td><?php echo date('d/m/Y H:i', strtotime($cmd['date_commande'])); ?></td>
                <td><i class="fas fa-user"></i> <?php echo htmlspecialchars($cmd['utilisateur']); ?></td>
                <td><i class="fas fa-utensils"></i> <?php echo htmlspecialchars($cmd['plat']); ?></td>
                <td class="text-center"><?php echo $cmd['quantite']; ?></td>
                <td><?php echo number_format($cmd['prix_total'], 0, ',', ' '); ?> FCFA</td>
                <td>
                    <?php
                        $statut = strtolower($cmd['statut']);
                        if ($statut == 'en attente') {
                            echo '<span class="badge badge-en-attente">En attente</span>';
                        } elseif ($statut == 'validée' || $statut == 'validee') {
                            echo '<span class="badge badge-validee">Validée</span>';
                        } elseif ($statut == 'refusée' || $statut == 'refusee') {
                            echo '<span class="badge badge-refusee">Refusée</span>';
                        } else {
                            echo '<span class="badge badge-secondary">'.htmlspecialchars($cmd['statut']).'</span>';
                        }
                    ?>
                </td>
                <td class="action-btns">
                    <?php if ($statut == 'en attente'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="commande_id" value="<?php echo $cmd['id']; ?>">
                            <button name="nouveau_statut" value="validée" class="btn btn-success btn-sm" title="Valider"><i class="fas fa-check"></i></button>
                            <button name="nouveau_statut" value="refusée" class="btn btn-danger btn-sm" title="Refuser"><i class="fas fa-times"></i></button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted"><i class="fas fa-ban"></i> Aucune action</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($commandes)): ?>
            <tr>
                <td colspan="7" class="text-center text-muted">Aucune commande trouvée.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav>
        <ul class="pagination">
            <?php if($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>">&laquo; Précédent</a>
                </li>
            <?php endif; ?>
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page+1])); ?>">Suivant &raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>