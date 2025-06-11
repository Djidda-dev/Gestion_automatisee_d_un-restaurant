<?php
session_start();
require_once("bdd.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php");
    exit();
}

// Traitement du formulaire
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $id_plat = !empty($_POST['id_plat']) ? $_POST['id_plat'] : null;
    $date_reservation = $_POST['date_reservation'];
    $heure_reservation = $_POST['heure_reservation'];
    $nombre_personnes = $_POST['nombre_personnes'];
    $commentaire = $_POST['commentaire'];

    $stmt = $bdd->prepare("INSERT INTO reservations (id_utilisateur, id_plat, date_reservation, heure_reservation, nombre_personnes, commentaire) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_utilisateur, $id_plat, $date_reservation, $heure_reservation, $nombre_personnes, $commentaire]);
    $message = "Réservation enregistrée !";
}

// Récupérer la liste des plats
$plats = $bdd->query("SELECT id, nom FROM plats")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver une table ou un plat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-100 to-yellow-300 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center"><i class="fas fa-calendar-plus text-yellow-500"></i> Réserver une table ou un plat</h2>
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold">Plat (facultatif)</label>
                <select name="id_plat" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="">Réserver une table uniquement</option>
                    <?php foreach($plats as $plat): ?>
                        <option value="<?php echo $plat['id']; ?>"><?php echo htmlspecialchars($plat['nom']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Date de réservation</label>
                <input type="date" name="date_reservation" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Heure de réservation</label>
                <input type="time" name="heure_reservation" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Nombre de personnes</label>
                <input type="number" name="nombre_personnes" min="1" value="1" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Commentaire (optionnel)</label>
                <textarea name="commentaire" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400"></textarea>
            </div>
            <button type="submit" class="w-full py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition">Réserver</button>
        </form>
        <div class="text-center mt-4">
            <a href="mes_reservations.php" class="text-yellow-600 hover:underline"><i class="fas fa-calendar-check"></i> Voir mes réservations</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>