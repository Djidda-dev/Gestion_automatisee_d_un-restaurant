<?php
session_start();
require_once("bdd.php");

// Sécurité : utilisateur connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php");
    exit();
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Annulation ou modification
if (isset($_POST['action']) && isset($_POST['reservation_id'])) {
    $id = $_POST['reservation_id'];
    // Vérifier que la réservation appartient à l'utilisateur
    $verif = $bdd->prepare("SELECT * FROM reservations WHERE id=? AND id_utilisateur=?");
    $verif->execute([$id, $id_utilisateur]);
    if ($verif->fetch()) {
        if ($_POST['action'] === 'annuler') {
            $bdd->prepare("UPDATE reservations SET statut='annulée' WHERE id=?")->execute([$id]);
        } elseif ($_POST['action'] === 'modifier' && isset($_POST['date_reservation'], $_POST['heure_reservation'], $_POST['nombre_personnes'])) {
            $bdd->prepare("UPDATE reservations SET date_reservation=?, heure_reservation=?, nombre_personnes=?, commentaire=? WHERE id=?")
                ->execute([
                    $_POST['date_reservation'],
                    $_POST['heure_reservation'],
                    $_POST['nombre_personnes'],
                    $_POST['commentaire'],
                    $id
                ]);
        }
    }
}

// Récupérer les réservations de l'utilisateur
$reservations = $bdd->prepare("
    SELECT r.*, p.nom AS plat_nom
    FROM reservations r
    LEFT JOIN plats p ON r.id_plat = p.id
    WHERE r.id_utilisateur = ?
    ORDER BY r.date_reservation DESC, r.heure_reservation DESC
");
$reservations->execute([$id_utilisateur]);
$reservations = $reservations->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .badge-en-attente { background: #ffc107; color: #212529; }
        .badge-validée { background: #28a745; }
        .badge-refusée { background: #dc3545; }
        .badge-annulée { background: #6c757d; }
        .action-btns form { display: inline; }
        .action-btns .btn { margin-right: 0.2rem; }
        .edit-form { background: #f8f9fa; padding: 10px; border-radius: 8px; }
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-calendar-alt"></i> Mes réservations</h2>
    <a href="reservation.php" class="btn btn-primary mb-3"><i class="fas fa-calendar-plus"></i> Nouvelle réservation</a>
    <table class="table table-bordered table-hover bg-white">
        <thead class="thead-dark">
            <tr>
                <th>Plat</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Personnes</th>
                <th>Commentaire</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($reservations as $r): ?>
            <tr>
                <td><?php echo $r['plat_nom'] ? htmlspecialchars($r['plat_nom']) : 'Table seule'; ?></td>
                <td><?php echo $r['date_reservation']; ?></td>
                <td><?php echo $r['heure_reservation']; ?></td>
                <td><?php echo $r['nombre_personnes']; ?></td>
                <td><?php echo htmlspecialchars($r['commentaire']); ?></td>
                <td>
                    <?php
                        $statut = strtolower($r['statut']);
                        if ($statut == 'en attente') {
                            echo '<span class="badge badge-en-attente">En attente</span>';
                        } elseif ($statut == 'validée' || $statut == 'validee') {
                            echo '<span class="badge badge-validée">Validée</span>';
                        } elseif ($statut == 'refusée' || $statut == 'refusee') {
                            echo '<span class="badge badge-refusée">Refusée</span>';
                        } elseif ($statut == 'annulée' || $statut == 'annulee') {
                            echo '<span class="badge badge-annulée">Annulée</span>';
                        } else {
                            echo '<span class="badge badge-secondary">'.htmlspecialchars($r['statut']).'</span>';
                        }
                    ?>
                </td>
                <td class="action-btns">
                    <?php if ($statut == 'en attente'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="reservation_id" value="<?php echo $r['id']; ?>">
                            <button name="action" value="annuler" class="btn btn-secondary btn-sm" title="Annuler"><i class="fas fa-ban"></i></button>
                        </form>
                        <button class="btn btn-info btn-sm" onclick="toggleEditForm(<?php echo $r['id']; ?>)">Modifier</button>
                        <form method="post" class="edit-form mt-2" id="edit-form-<?php echo $r['id']; ?>" style="display:none;">
                            <input type="hidden" name="reservation_id" value="<?php echo $r['id']; ?>">
                            <input type="hidden" name="action" value="modifier">
                            <div class="form-row">
                                <div class="col">
                                    <input type="date" name="date_reservation" class="form-control" value="<?php echo $r['date_reservation']; ?>" required>
                                </div>
                                <div class="col">
                                    <input type="time" name="heure_reservation" class="form-control" value="<?php echo $r['heure_reservation']; ?>" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="nombre_personnes" class="form-control" min="1" value="<?php echo $r['nombre_personnes']; ?>" required>
                                </div>
                                <div class="col">
                                    <input type="text" name="commentaire" class="form-control" value="<?php echo htmlspecialchars($r['commentaire']); ?>">
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <span class="text-muted"><i class="fas fa-ban"></i> Aucune action</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function toggleEditForm(id) {
    var form = document.getElementById('edit-form-' + id);
    if (form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}
</script>
</body>
</html>