<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-100 to-yellow-300 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 animate-fade-in">
        <div class="flex flex-col items-center mb-6">
            <i class="fas fa-user-plus text-yellow-500 text-4xl mb-2"></i>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Inscription</h1>
            <p class="text-gray-500">Créez votre compte client Restaurant</p>
        </div>
        <form action="traitement_inscription.php" method="post" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Adresse Mail</label>
                <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" placeholder="Adresse Mail" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Mot de passe</label>
                <input type="password" name="mdp" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" placeholder="Mot de passe" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="confirm_mdp" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" placeholder="Confirmer le mot de passe" required>
            </div>
            <input type="submit" name="valider" class="w-full py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition cursor-pointer" value="S'inscrire">
        </form>
        <div class="text-center mt-4">
            <span class="text-gray-600">Déjà inscrit ?</span>
            <a href="login.php" class="text-yellow-600 hover:underline font-semibold">Se connecter</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: none; } }
        .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
    </style>
</body>
</html>
