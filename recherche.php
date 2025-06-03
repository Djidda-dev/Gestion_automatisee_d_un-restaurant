<?php
require_once("bdd.php");

if(isset($_GET['q'])) {
    $recherche = $_GET['q'];
    // Requête pour rechercher les plats correspondants dans la base de données
    $requete_recherche = "SELECT * FROM plats WHERE nom LIKE :recherche OR description LIKE :recherche";
    $stmt_recherche = $bdd->prepare($requete_recherche);
    $stmt_recherche->bindValue(':recherche', "%$recherche%", PDO::PARAM_STR);
    $stmt_recherche->execute();
    $resultats = $stmt_recherche->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h3 class="mt-5 mb-4 text-center">Résultats de la recherche pour "<?php echo htmlspecialchars($_GET['q']); ?>"</h3>
    <div class="row">
        <?php if(isset($resultats) && !empty($resultats)) { ?>
            <?php foreach ($resultats as $plat) { ?>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $plat['nom']; ?></h5>
                            <p class="card-text"><?php echo $plat['description']; ?></p>
                            <p class="card-text"><strong>Prix: <?php echo $plat['prix']; ?> FCFA</strong></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-lg-12">
                <p>Aucun résultat trouvé.</p>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>





