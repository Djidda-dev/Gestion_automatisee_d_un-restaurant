<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un nouveau plat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Ajoutez vos styles personnalisés ici si nécessaire */
    </style>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade" id="ajoutPlatModal" tabindex="-1" role="dialog" aria-labelledby="ajoutPlatModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajoutPlatModalLabel">Ajouter un nouveau plat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire -->
                    <form id="formAjoutPlat" method="post" action="C_plat.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nom_plat">Nom du plat :</label>
                            <input type="text" class="form-control" id="nom_plat" name="plats" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="prix">Prix :</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="prix" name="prix" min="0.01" step="0.01" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image">Image :</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" required>
                                <label class="custom-file-label" for="image">Choisir un fichier</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" form="formAjoutPlat" class="btn btn-primary" name="valider">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ajoutPlatModal').modal('show');
        });
    </script>
</body>
</html>
