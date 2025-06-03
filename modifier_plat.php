<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un plat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Ajoutez vos styles personnalisés ici si nécessaire */
    </style>
</head>
<body>
    <?php
    require_once("bdd.php"); // Assurez-vous que le fichier bdd.php est inclus
    if(isset($_POST['plat_id'])) {
        $plat_id = $_POST['plat_id'];
        $sql = "SELECT * FROM plats WHERE id = :id";
        $requete = $bdd->prepare($sql);
        $requete->bindParam(':id', $plat_id);
        if($requete->execute()) {
            $plat = $requete->fetch();
    ?>
    <!-- Formulaire de modification de plat -->
    <div class="container mt-5">
        <h2 class="mb-4">Modifier le plat "<?php echo $plat['nom']; ?>"</h2>
        <form action="modifier_plat_action" method="post" enctype="multipart/form-data">
            <input type="hidden" name="plat_id" value="<?php echo $plat['id']; ?>">
            <div class="form-group">
                <label for="nom_plat">Nom du plat :</label>
                <input type="text" class="form-control" id="nom_plat" name="nom_plat" value="<?php echo $plat['nom']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="2" required><?php echo $plat['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="prix" name="prix" min="0.01" step="0.01" value="<?php echo $plat['prix']; ?>" required>
                    <div class="input-group-append">
                        <span class="input-group-text">FCFA</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="image">Image :</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image">
                    <label class="custom-file-label" for="image">Choisir un fichier</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="valider">Enregistrer les modifications</button>
        </form>
    </div>
    <?php
        } else {
            echo "Erreur lors de la récupération des données du plat à modifier.";
        }
    } else {
        echo "Identifiant du plat non fourni.";
    }
    ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
