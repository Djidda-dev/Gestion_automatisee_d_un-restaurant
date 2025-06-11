<?php
session_start();
require_once("bdd.php");

// Sécurité admin
if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role_utilisateur'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Traitement des actions
if (isset($_POST['action']) && isset($_POST['reservation_id'])) {
    $id = $_POST['reservation_id'];
    if ($_POST['action'] === 'valider') {
        $bdd->prepare("UPDATE reservations SET statut='validée' WHERE id=?")->execute([$id]);
    } elseif ($_POST['action'] === 'refuser') {
        $bdd->prepare("UPDATE reservations SET statut='refusée' WHERE id=?")->execute([$id]);
    } elseif ($_POST['action'] === 'annuler') {
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

// Récupérer les réservations
$reservations = $bdd->query("
    SELECT r.*, u.email, p.nom AS plat_nom
    FROM reservations r
    JOIN utilisateurs u ON r.id_utilisateur = u.id
    LEFT JOIN plats p ON r.id_plat = p.id
    ORDER BY r.date_reservation DESC, r.heure_reservation DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des réservations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
<div class="container mx-auto py-10 px-4">
    <h2 class="text-3xl font-bold mb-8 text-gray-800 flex items-center gap-2"><i class="fas fa-calendar-alt"></i> Gestion des réservations</h2>
    <div class="overflow-x-auto shadow rounded-xl bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Plat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Heure</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Personnes</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Commentaire</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Statut</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach($reservations as $r): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($r['email']); ?></td>
                    <td class="px-6 py-4"><?php echo $r['plat_nom'] ? htmlspecialchars($r['plat_nom']) : 'Table seule'; ?></td>
                    <td class="px-6 py-4"><?php echo $r['date_reservation']; ?></td>
                    <td class="px-6 py-4"><?php echo $r['heure_reservation']; ?></td>
                    <td class="px-6 py-4 text-center"><?php echo $r['nombre_personnes']; ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($r['commentaire']); ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php
                            $statut = strtolower($r['statut']);
                            if ($statut == 'en attente') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-yellow-400 text-gray-900 text-xs font-semibold">En attente</span>';
                            } elseif ($statut == 'validée' || $statut == 'validee') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-green-500 text-white text-xs font-semibold">Validée</span>';
                            } elseif ($statut == 'refusée' || $statut == 'refusee') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-red-500 text-white text-xs font-semibold">Refusée</span>';
                            } elseif ($statut == 'annulée' || $statut == 'annulee') {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-gray-400 text-white text-xs font-semibold">Annulée</span>';
                            } else {
                                echo '<span class="inline-block px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-semibold">'.htmlspecialchars($r['statut']).'</span>';
                            }
                        ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($statut == 'en attente'): ?>
                            <form method="post" class="inline-block">
                                <input type="hidden" name="reservation_id" value="<?php echo $r['id']; ?>">
                                <button name="action" value="valider" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600 transition text-xs" title="Valider"><i class="fas fa-check"></i></button>
                                <button name="action" value="refuser" class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600 transition text-xs" title="Refuser"><i class="fas fa-times"></i></button>
                                <button name="action" value="annuler" class="px-3 py-1 rounded bg-gray-400 text-white hover:bg-gray-500 transition text-xs" title="Annuler"><i class="fas fa-ban"></i></button>
                            </form>
                            <button class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition text-xs ml-2" onclick="toggleEditForm(<?php echo $r['id']; ?>)">Modifier</button>
                            <form method="post" class="edit-form mt-2" id="edit-form-<?php echo $r['id']; ?>" style="display:none;">
                                <input type="hidden" name="reservation_id" value="<?php echo $r['id']; ?>">
                                <input type="hidden" name="action" value="modifier">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <input type="date" name="date_reservation" class="px-2 py-1 border rounded" value="<?php echo $r['date_reservation']; ?>" required>
                                    <input type="time" name="heure_reservation" class="px-2 py-1 border rounded" value="<?php echo $r['heure_reservation']; ?>" required>
                                    <input type="number" name="nombre_personnes" class="px-2 py-1 border rounded" min="1" value="<?php echo $r['nombre_personnes']; ?>" required>
                                    <input type="text" name="commentaire" class="px-2 py-1 border rounded" value="<?php echo htmlspecialchars($r['commentaire']); ?>">
                                    <button type="submit" class="px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600 transition text-xs">Enregistrer</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <span class="text-gray-400 text-xs"><i class="fas fa-ban"></i> Aucune action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-400">Aucune réservation trouvée.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
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