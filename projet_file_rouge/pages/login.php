<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — MediCab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="login-wrap">
    <div class="login-card">
        <h2>Connexion</h2>
        <p>Connectez-vous pour accéder à votre espace professionnel et gérer vos consultations en toute simplicité.</p>

        <!-- Login page: Email input + Password input + Login button -->
        <!-- Current behavior: Home → Login → Click Login → Dashboard (no real auth, no session) -->
        <form action="dashboard.php" method="GET" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" placeholder="vous@example.com">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input class="form-control" type="password" id="password" name="password" placeholder="••••••••">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </div>
            <p class="help">Accès sécurisé réservé au personnel autorisé.</p>
        </form>

        <div style="margin-top:14px;text-align:center">
            <a href="home.php" class="btn btn-ghost">← Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
