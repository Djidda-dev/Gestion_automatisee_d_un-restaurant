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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="bg-gray-900 text-gray-100 w-64 flex-shrink-0 flex flex-col">
            <div class="flex items-center justify-center h-20 border-b border-gray-800">
                <span class="text-2xl font-bold"><i class="fas fa-utensils mr-2"></i>Admin Cantine</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="admin_dashboard.php" class="flex items-center px-3 py-2 rounded-lg bg-gray-800 text-yellow-400 font-semibold">
                    <i class="fas fa-chart-line mr-2"></i> Dashboard
                </a>
                <a href="ajoutplat.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-plus mr-2"></i> Ajouter un plat
                </a>
                <a href="affichplat.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-list mr-2"></i> Voir les plats
                </a>
                <a href="affichcmd.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-history mr-2"></i> Historique commandes
                </a>
                <a href="gestion_commandes.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-tasks mr-2"></i> Gérer les commandes
                </a>
                <a href="gestion_reservations.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-calendar-alt mr-2"></i> Gérer les réservations
                </a>
                <a href="reservation.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-calendar-plus mr-2"></i> Réserver
                </a>
                <a href="recherche.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800">
                    <i class="fas fa-search mr-2"></i> Recherche
                </a>
                <a href="logout.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-800 text-red-400">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>
            </nav>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Tableau de bord Administrateur</h2>
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
                    <div class="text-4xl text-blue-500 mb-2"><i class="fas fa-receipt"></i></div>
                    <div class="text-2xl font-bold"><?php echo $totalCommandes; ?></div>
                    <div class="text-gray-600">Commandes passées</div>
                </div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
                    <div class="text-4xl text-green-500 mb-2"><i class="fas fa-utensils"></i></div>
                    <div class="text-2xl font-bold"><?php echo $totalPlats; ?></div>
                    <div class="text-gray-600">Plats disponibles</div>
                </div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center hover:scale-105 transition">
                    <div class="text-4xl text-indigo-500 mb-2"><i class="fas fa-users"></i></div>
                    <div class="text-2xl font-bold"><?php echo $totalUtilisateurs; ?></div>
                    <div class="text-gray-600">Utilisateurs inscrits</div>
                </div>
            </div>
            <!-- Dernières commandes -->
            <div class="bg-white rounded-xl shadow mb-8">
                <div class="bg-gray-800 text-white px-6 py-4 rounded-t-xl flex items-center">
                    <i class="fas fa-clock mr-2"></i> 5 dernières commandes
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Plat</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Quantité</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Prix Total</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($dernieresCommandes as $cmd): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo date('d/m/Y H:i', strtotime($cmd['date_commande'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($cmd['nom_utilisateur']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($cmd['nom_plat']); ?></td>
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
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($dernieresCommandes)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">Aucune commande récente.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>