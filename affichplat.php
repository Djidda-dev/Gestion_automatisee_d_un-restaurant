<?php
session_start();
require_once("bdd.php");

// Vérifie si l'utilisateur est connecté et s'il est administrateur
$est_admin = false;
if(isset($_SESSION['id_utilisateur'])) {
    $id_utilisateur = $_SESSION['id_utilisateur'];
    if(isset($_SESSION['role_utilisateur']) && $_SESSION['role_utilisateur'] == 'admin') {
        $est_admin = true;
    }
}

// Récupérer les plats
$requete = "SELECT * FROM plats";
$resultat = $bdd->query($requete);
$plats = $resultat->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les plats disponibles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
<!-- Navbar -->
<nav class="bg-gray-900 text-white shadow sticky top-0 z-50">
    <div class="container mx-auto flex items-center justify-between py-4 px-4">
        <a href="cantine.html" class="text-2xl font-bold tracking-wide flex items-center">
            <i class="fas fa-utensils text-yellow-400 mr-2"></i> MaCantine
        </a>
        <div class="hidden md:flex space-x-6">
            <a href="cantine.html" class="hover:text-yellow-400 transition">Accueil</a>
            <a href="contact.php" class="hover:text-yellow-400 transition">Contact</a>
            <?php if(isset($_SESSION['id_utilisateur'])): ?>
                <a href="reservation.php" class="hover:text-yellow-400 transition"><i class="fas fa-calendar-plus"></i> Réserver</a>
                <a href="mes_reservations.php" class="hover:text-yellow-400 transition"><i class="fas fa-calendar-check"></i> Mes réservations</a>
                <a href="logout.php" class="hover:text-red-400 transition"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="hover:text-yellow-400 transition"><i class="fas fa-sign-in-alt"></i> Connexion</a>
            <?php endif; ?>
            <?php if($est_admin): ?>
                <a href="ajoutplat.php" class="hover:text-yellow-400 transition"><i class="fas fa-plus"></i> Ajouter un plat</a>
                <a href="affichcmd.php" class="hover:text-yellow-400 transition"><i class="fas fa-history"></i> Historique commandes</a>
                <a href="gestion_commandes.php" class="hover:text-yellow-400 transition"><i class="fas fa-tasks"></i> Gérer commandes</a>
                <a href="gestion_reservations.php" class="hover:text-yellow-400 transition"><i class="fas fa-calendar-alt"></i> Gérer réservations</a>
            <?php endif; ?>
        </div>
        <button id="menuBtn" class="md:hidden text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-gray-800 px-4 pb-4">
        <a href="cantine.html" class="block py-2 hover:text-yellow-400">Accueil</a>
        <a href="contact.php" class="block py-2 hover:text-yellow-400">Contact</a>
        <?php if(isset($_SESSION['id_utilisateur'])): ?>
            <a href="reservation.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-calendar-plus"></i> Réserver</a>
            <a href="mes_reservations.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-calendar-check"></i> Mes réservations</a>
            <a href="logout.php" class="block py-2 hover:text-red-400"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        <?php else: ?>
            <a href="login.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-sign-in-alt"></i> Connexion</a>
        <?php endif; ?>
        <?php if($est_admin): ?>
            <a href="ajoutplat.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-plus"></i> Ajouter un plat</a>
            <a href="affichcmd.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-history"></i> Historique commandes</a>
            <a href="gestion_commandes.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-tasks"></i> Gérer commandes</a>
            <a href="gestion_reservations.php" class="block py-2 hover:text-yellow-400"><i class="fas fa-calendar-alt"></i> Gérer réservations</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Profil -->
<div class="bg-gray-800 text-white text-center py-2">
    <?php 
        if($est_admin) {
            echo "<i class='fas fa-user-shield text-yellow-400'></i> Profil Administrateur";
        } elseif(isset($_SESSION['id_utilisateur'])) {
            echo "<i class='fas fa-user text-yellow-400'></i> Profil Utilisateur";
        } else {
            echo "<i class='fas fa-user'></i> Visiteur";
        }
    ?>
</div>

<!-- Recherche -->
<div class="container mx-auto px-4 mt-6">
    <form class="flex justify-center mb-8" action="recherche.php" method="GET">
        <input class="w-full max-w-md px-4 py-2 rounded-l border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400" type="search" placeholder="Rechercher" aria-label="Rechercher" name="q">
        <button class="px-4 py-2 bg-yellow-500 text-white rounded-r font-semibold hover:bg-yellow-600 transition" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Plats -->
<div class="container mx-auto px-4">
    <h3 class="mt-5 mb-8 text-2xl font-bold text-center text-gray-800">NOS MENUS DISPONIBLES</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php if(empty($plats)): ?>
            <div class="col-span-full text-center text-gray-400">Aucun plat disponible pour le moment.</div>
        <?php endif; ?>
        <?php foreach ($plats as $plat): ?>
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col">
                <img src="img/<?php echo htmlspecialchars($plat['image']); ?>" class="w-full h-48 object-cover rounded-t-xl" alt="<?php echo htmlspecialchars($plat['nom']); ?>">
                <div class="p-4 flex flex-col flex-1">
                    <h5 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($plat['nom']); ?></h5>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($plat['description']); ?></p>
                    <p class="font-bold text-yellow-600 mb-4">Prix : <?php echo number_format($plat['prix'], 0, ',', ' '); ?> FCFA</p>
                    <div class="mt-auto flex flex-wrap gap-2 justify-center">
                        <?php if($est_admin): ?>
                            <form action="modifier_plat.php" method="POST">
                                <input type="hidden" name="plat_id" value="<?php echo $plat['id']; ?>">
                                <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"><i class="fas fa-edit"></i> Modifier</button>
                            </form>
                            <form action="Suppplat.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plat?');">
                                <input type="hidden" name="plat_id" value="<?php echo $plat['id']; ?>">
                                <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </form>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['id_utilisateur']) && !$est_admin): ?>
                            <form action="passer_commande.php" method="POST">
                                <input type="hidden" name="plat_id" value="<?php echo $plat['id']; ?>">
                                <button type="submit" class="px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm flex items-center gap-1">
                                    <i class="fas fa-utensils"></i> Commander
                                </button>
                            </form>
                        <?php elseif(!isset($_SESSION['id_utilisateur'])): ?>
                            <a href="login.php" class="px-4 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm flex items-center gap-1">
                                Se connecter pour commander
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    // Menu mobile toggle
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
</body>
</html>
