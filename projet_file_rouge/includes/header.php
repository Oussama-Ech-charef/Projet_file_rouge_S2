<?php
// includes/header.php — Header public réutilisable
// Règle: Pas de lien Dashboard dans le Header public (spécification section 20/41)
?>
<header class="site-header">
    <div class="container header-inner">
        <a href="../pages/home.php" class="logo">
            <span class="logo-icon">✚</span>
            <span class="logo-text">MediCab</span>
        </a>
        <nav class="nav-links">
            <a href="../pages/home.php#specialites">Spécialités</a>
            <a href="../pages/home.php#apropos">À propos</a>
            <a href="../pages/home.php#contact">Contact</a>
        </nav>
        <a href="../pages/login.php" class="btn btn-primary btn-login">Connexion</a>
    </div>
</header>
