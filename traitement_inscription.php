<?php
// filepath: c:\wamp64\www\Macantine\traitement_inscription.php
$message = "";
$success = false;

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifie si les données nécessaires ont été reçues
    if (
        isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['confirm_mdp'])
        && !empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['confirm_mdp'])
    ) {
        // Inclure le fichier de connexion à la base de données
        require_once("bdd.php");

        // Récupère les données du formulaire
        $email = trim($_POST['email']);
        $mdp = $_POST['mdp'];
        $confirm_mdp = $_POST['confirm_mdp'];

        // Vérification de la correspondance des mots de passe
        if ($mdp !== $confirm_mdp) {
            $message = "Les mots de passe ne correspondent pas.";
        } else {
            // Vérifie si l'e-mail existe déjà
            $requeteEmailExistant = "SELECT id FROM utilisateurs WHERE email = :email";
            $stmtEmailExistant = $bdd->prepare($requeteEmailExistant);
            $stmtEmailExistant->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtEmailExistant->execute();

            if ($stmtEmailExistant->rowCount() > 0) {
                $message = "L'adresse e-mail est déjà utilisée. Veuillez choisir une autre adresse e-mail.";
            } else {
                // Hachage du mot de passe pour la sécurité
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                $requeteInscription = "INSERT INTO utilisateurs (email, mdp) VALUES (:email, :mdp)";
                $stmtInscription = $bdd->prepare($requeteInscription);
                $stmtInscription->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtInscription->bindParam(':mdp', $mdp_hash, PDO::PARAM_STR);

                if ($stmtInscription->execute()) {
                    $success = true;
                    $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    // Redirection après 10 secondes
                    header("refresh:10;url=login.php");
                } else {
                    $message = "Erreur lors de l'inscription. Veuillez réessayer.";
                }
            }
        }
    } else {
        $message = "Tous les champs du formulaire doivent être remplis.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Restaurant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        </div>
        <?php if ($message): ?>
            <div class="<?php echo $success ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-2 rounded mb-4 text-center animate-fade-in">
                <?php echo $message; ?>
                <?php if ($success): ?>
                    <br><span class="text-sm text-gray-500">Redirection vers la connexion...</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="login.php" class="text-yellow-600 hover:underline font-semibold"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: none; } }
        .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
    </style>
</body>
</html>
