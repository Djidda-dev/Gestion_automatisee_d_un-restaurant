<?php
session_start();
require_once("bdd.php");

// Vérifie si le formulaire de connexion a été soumis
if(isset($_POST['valider'])) {
    if(isset($_POST['email']) && isset($_POST['mdp'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Requête pour vérifier les informations d'authentification
        $requete = "SELECT id, role FROM utilisateurs WHERE email=:email AND mdp=:mdp";
        $stmt = $bdd->prepare($requete);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $stmt->execute();

        // Récupération du résultat de la requête
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            $_SESSION['id_utilisateur'] = $utilisateur['id'];
            $_SESSION['role_utilisateur'] = $utilisateur['role'];
            if ($_SESSION['role_utilisateur'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: affichplat.php");
            }
            exit();
        } else {
            $erreur = "Adresse ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MaCantine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-100 to-yellow-300 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <div class="flex flex-col items-center mb-6">
            <i class="fas fa-utensils text-yellow-500 text-4xl mb-2"></i>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Connexion</h1>
            <p class="text-gray-500">Connectez-vous à votre espace MaCantine</p>
        </div>
        <?php if (isset($erreur)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center">
                <?php echo $erreur; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post" class="space-y-4">
            <input type="text" name="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Adresse Mail" required>
            <input type="password" name="mdp" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Mot de passe" required>
            <input type="submit" name="valider" value="Se connecter" class="w-full py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition">
        </form>
        <div class="text-center mt-4">
            <a href="inscription.php" class="text-yellow-600 hover:underline">Créer un compte</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
