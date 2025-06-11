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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container py-5">
    <h2>Réserver une table ou un plat</h2>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Plat (facultatif)</label>
            <select name="id_plat" class="form-control">
                <option value="">Réserver une table uniquement</option>
                <?php foreach($plats as $plat): ?>
                    <option value="<?php echo $plat['id']; ?>"><?php echo htmlspecialchars($plat['nom']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Date de réservation</label>
            <input type="date" name="date_reservation" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Heure de réservation</label>
            <input type="time" name="heure_reservation" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nombre de personnes</label>
            <input type="number" name="nombre_personnes" class="form-control" min="1" value="1" required>
        </div>
        <div class="form-group">
            <label>Commentaire (optionnel)</label>
            <textarea name="commentaire" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Réserver</button>
    </form>
</div>
</body>
</html>