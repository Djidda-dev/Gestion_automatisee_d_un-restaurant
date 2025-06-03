<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Coordonnées</title>
    <link rel="stylesheet" href="styles.css"> <!-- Votre fichier CSS personnalisé -->
    <style>
        /* Ajoutez vos styles personnalisés ici si nécessaire */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .coord-item {
            margin-bottom: 20px;
        }
        .coord-item:last-child {
            margin-bottom: 0;
        }
        .coord-label {
            font-weight: bold;
        }
        .coord-value {
            margin-left: 10px;
        }
        .coord-link {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .coord-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Nos Coordonnées</h1>
    <div class="coord-item">
        <span class="coord-label">Adresse :</span>
        <span class="coord-value">123 Rue Togoville, Lomé, Togo</span>
    </div>
    <div class="coord-item">
        <span class="coord-label">Quartier:</span>
        <span class="coord-value">Nyékonakpoè au niveau de Carrefour des eperviers</span>
    </div>
    <div class="coord-item">
        <span class="coord-label">Téléphone :</span>
        <span class="coord-value">+228 91304780</span>
    </div>
    <div class="coord-item">
        <span class="coord-label">Email :</span>
        <span class="coord-value">adoum@gmail.com</span>
    </div>
    <div class="coord-item">
        <span class="coord-label">Site Web :</span>
        <a href="http://www.RestaurantGuildja.com" class="coord-value coord-link">www.RestaurantGuildja.com</a>
    </div>
</div>

</body>
</html>
