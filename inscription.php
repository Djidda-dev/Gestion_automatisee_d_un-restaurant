<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .signup-form {
            width: 300px;
            margin: 0 auto;
            padding: 30px 0;
            text-align: center;
        }

        .signup-form input[type="email"],
        .signup-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        .signup-form input[type="submit"] {
            padding: 10px 20px;
            background: #333;
            color: #fff;
            border: none;
        }

        .signup-form input[type="submit"]:hover {
            background: #444;
        }
    </style>
</head>
<body>
    <div class="signup-form">
        <h1>Inscription</h1>
        <form action="traitement_inscription.php" method="post">
            <input type="email" name="email" class="form-control" placeholder="Adresse Mail" required>
            <input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required>
            <input type="password" name="confirm_mdp" class="form-control" placeholder="Confirmer le mot de passe" required>
            <input type="submit" name="valider" class="btn btn-primary" value="S'inscrire">
        </form>
    </div>

    <!-- Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
