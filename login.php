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
    <title>Connexion - Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-100 to-yellow-300 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 animate-fade-in">
        <div class="flex flex-col items-center mb-6">
            <i class="fas fa-utensils text-yellow-500 text-4xl mb-2"></i>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Connexion</h1>
            <p class="text-gray-500">Connectez-vous à votre espace Restaurant</p>
        </div>
        <?php if (isset($erreur)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center animate-shake">
                <?php echo $erreur; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Adresse Mail</label>
                <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" placeholder="Adresse Mail" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Mot de passe</label>
                <input type="password" name="mdp" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" placeholder="Mot de passe" required>
            </div>
            <input type="submit" name="valider" value="Se connecter" class="w-full py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition cursor-pointer">
        </form>
        <div class="text-center mt-4">
            <span class="text-gray-600">Pas encore de compte ?</span>
            <a href="inscription.php" class="text-yellow-600 hover:underline font-semibold">Créer un compte</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: none; } }
        .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
        @keyframes shake { 10%, 90% { transform: translateX(-1px); } 20%, 80% { transform: translateX(2px); } 30%, 50%, 70% { transform: translateX(-4px); } 40%, 60% { transform: translateX(4px); } }
        .animate-shake { animation: shake 0.4s; }
    </style>
</body>
</html>
