<?php
session_start();
require_once("bdd.php");

// Sécurité : accès réservé aux admins
if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['role_utilisateur']) || $_SESSION['role_utilisateur'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Statistiques globales
$totalCommandes = $bdd->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$totalPlats = $bdd->query("SELECT COUNT(*) FROM plats")->fetchColumn();
$totalUtilisateurs = $bdd->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();

// Dernières commandes (5 dernières)
$dernieresCommandes = $bdd->query("
    SELECT c.id, c.date_commande, c.prix_total, c.statut, c.quantite, u.email AS nom_utilisateur, p.nom AS nom_plat
    FROM commandes c
    JOIN utilisateurs u ON c.id_utilisateur = u.id
    JOIN plats p ON c.id_plat = p.id
    ORDER BY c.date_commande DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin - Cantine</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 220px;
            background: #23272b;
            color: #fff;
            padding-top: 60px;
            z-index: 1000;
            transition: all 0.2s;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            font-size: 1.1rem;
            margin-bottom: 10px;
            border-radius: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #343a40;
            color: #fff;
        }
        .sidebar .sidebar-header {
            position: absolute;
            top: 0; left: 0; width: 100%;
            background: #1a1d20;
            padding: 18px 0;
            text-align: center;
            font-size: 1.3rem;
            font-weight: bold;
            letter-spacing: 1px;
            border-bottom: 1px solid #343a40;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px 30px 30px 30px;
        }
        .dashboard-card {
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            transition: transform 0.2s;
        }
        .dashboard-card:hover { transform: translateY(-5px) scale(1.02);}
        .dashboard-icon {
            font-size: 2.5rem;
            opacity: 0.7;
        }
        .quick-actions .btn {
            margin: 0.3rem;
        }
        .table thead th { background: #343a40; color: #fff; }
        @media (max-width: 991.98px) {
            .sidebar { width: 100%; height: auto; position: relative; padding-top: 0; }
            .main-content { margin-left: 0; padding: 20px 5px; }
        }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column">
        <div class="sidebar-header">
            <i class="fas fa-utensils"></i> Admin Cantine
        </div>
        <nav class="nav flex-column mt-4 px-3">
            <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a class="nav-link" href="ajoutplat.php"><i class="fas fa-plus"></i> Ajouter un plat</a>
            <a class="nav-link" href="affichplat.php"><i class="fas fa-list"></i> Voir les plats</a>
            <a class="nav-link" href="affichcmd.php"><i class="fas fa-history"></i> Historique commandes</a>
            <a class="nav-link" href="gestion_commandes.php"><i class="fas fa-tasks"></i> Gérer les commandes</a>
                        <a class="nav-link" href="gestion_reservations.php"><i class="fas fa-tasks"></i> Gérer les reservations</a>
            <a href="reservation.php" class="nav-link"><i class="fas fa-calendar-plus"></i> Réserver</a>
            <a class="nav-link" href="recherche.php"><i class="fas fa-search"></i> Recherche</a>
            <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </div>
    <div class="main-content">
        <h2 class="mb-4 text-center">Tableau de bord Administrateur</h2>
        <div class="row text-center mb-4">
            <div class="col-md-4 mb-3">
                <div class="dashboard-card bg-white p-4">
                    <div class="dashboard-icon text-primary mb-2"><i class="fas fa-receipt"></i></div>
                    <h4><?php echo $totalCommandes; ?></h4>
                    <p class="mb-0">Commandes passées</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-card bg-white p-4">
                    <div class="dashboard-icon text-success mb-2"><i class="fas fa-utensils"></i></div>
                    <h4><?php echo $totalPlats; ?></h4>
                    <p class="mb-0">Plats disponibles</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-card bg-white p-4">
                    <div class="dashboard-icon text-info mb-2"><i class="fas fa-users"></i></div>
                    <h4><?php echo $totalUtilisateurs; ?></h4>
                    <p class="mb-0">Utilisateurs inscrits</p>
                </div>
            </div>
        </div>
        <div class="card shadow rounded">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-clock"></i> 5 dernières commandes
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Utilisateur</th>
                                <th>Plat</th>
                                <th>Quantité</th>
                                <th>Prix Total</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dernieresCommandes as $cmd): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($cmd['date_commande'])); ?></td>
                                    <td><?php echo htmlspecialchars($cmd['nom_utilisateur']); ?></td>
                                    <td><?php echo htmlspecialchars($cmd['nom_plat']); ?></td>
                                    <td><?php echo $cmd['quantite']; ?></td>
                                    <td><?php echo number_format($cmd['prix_total'], 0, ',', ' '); ?> FCFA</td>
                                    <td>
                                        <?php
                                            $statut = strtolower($cmd['statut']);
                                            if ($statut == 'en attente') {
                                                echo '<span class="badge badge-warning">En attente</span>';
                                            } elseif ($statut == 'validée' || $statut == 'validee') {
                                                echo '<span class="badge badge-success">Validée</span>';
                                            } elseif ($statut == 'refusée' || $statut == 'refusee') {
                                                echo '<span class="badge badge-danger">Refusée</span>';
                                            } else {
                                                echo '<span class="badge badge-secondary">'.htmlspecialchars($cmd['statut']).'</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($dernieresCommandes)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Aucune commande récente.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and FontAwesome -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>