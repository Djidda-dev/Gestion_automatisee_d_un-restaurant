<!DOCTYPE html>
<html>
<head>
    <title>Passer une commande - Cantine Scolaire</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .order-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-form h1 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="order-form">
                    <h1>Passer une commande</h1>
                    <!-- <form id="orderForm" action="traitement_commande.php" method="post"> -->
                        <div class="form-group">
                            <label for="menu">Sélectionnez un plat :</label>
                            <select class="form-control" name="menu" id="menu" required>
                                <option value="">Choisir un plat...</option>
                                <?php
                                // Inclure le fichier de connexion à la base de données
                                require_once("bdd.php");

                                // Requête pour obtenir la liste des plats disponibles
                                $sql = "SELECT * FROM plats";
                                $resultat = $bdd->query($sql);

                                // Affichage des options pour chaque plat
                                while($plat = $resultat->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='".$plat['id']."'>".$plat['nom']." - ".$plat['prix']." €</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité :</label>
                            <input type="number" class="form-control" name="quantite" id="quantite" min="1" value="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Passer la commande</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for form validation -->
    <script>
        $(document).ready(function() {
            // JavaScript for form validation
            $('#orderForm').submit(function(event) {
                var menu = $('#menu').val();
                var quantite = $('#quantite').val();

                if (menu == '' || quantite == '' || quantite <= 0) {
                    alert('Veuillez sélectionner un plat et spécifier une quantité valide.');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
