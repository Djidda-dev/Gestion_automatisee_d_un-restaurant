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
<html>
<head>
    <title>Historique des commandes - Cantine Scolaire</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .table thead th { background: #343a40; color: #fff; }
        .badge-en-attente { background: #ffc107; color: #212529; }
        .badge-validee { background: #28a745; }
        .badge-refusee { background: #dc3545; }
        .pagination { justify-content: center; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="text-center mb-4">Historique des commandes</h1>
                <div class="table-responsive shadow rounded">
                    <table class="table table-striped table-hover bg-white">
                        <thead>
                            <tr>
                                <th>Plat</th>
                                <th>Quantité</th>
                                <th>Date</th>
                                <th>Prix Total (FCFA)</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($commande['nom_plat']); ?></td>
                                    <td><?php echo $commande['quantite']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?></td>
                                    <td><?php echo number_format($commande['prix_total'], 0, ',', ' '); ?></td>
                                    <td>
                                        <?php
                                            $statut = strtolower($commande['statut']);
                                            if ($statut == 'en attente') {
                                                echo '<span class="badge badge-en-attente">En attente</span>';
                                            } elseif ($statut == 'validée' || $statut == 'validee') {
                                                echo '<span class="badge badge-validee">Validée</span>';
                                            } elseif ($statut == 'refusée' || $statut == 'refusee') {
                                                echo '<span class="badge badge-refusee">Refusée</span>';
                                            } else {
                                                echo '<span class="badge badge-secondary">'.htmlspecialchars($commande['statut']).'</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($commandes)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune commande trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav>
                    <ul class="pagination mt-4">
                        <?php if($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page-1; ?>">&laquo; Précédent</a>
                            </li>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page+1; ?>">Suivant &raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
