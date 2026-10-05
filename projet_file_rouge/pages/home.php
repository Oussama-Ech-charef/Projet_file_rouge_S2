<?php
// pages/home.php — Home publique avec Header, Hero, Search+Filter, 6 Specialty Cards via DB
require_once __DIR__ . '/../config/database.php';

$specialites = [];
$errorMsg = $pdo_error ?? null;
$fallback = [
    ['id'=>1,'nom'=>'Cardiologie','description'=>'Diagnostic et traitement des maladies du coeur et du systeme cardiovasculaire.','image'=>'assets/images/Cardiologie.jpe'],
    ['id'=>2,'nom'=>'Dermatologie','description'=>'Prise en charge des maladies de la peau, des cheveux et des ongles.','image'=>'assets/images/Dermatologie.jpe'],
    ['id'=>3,'nom'=>'Pédiatrie','description'=>'Soins medicaux dedies aux nourrissons, enfants et adolescents.','image'=>'assets/images/Pédiatrie.jpe'],
    ['id'=>4,'nom'=>'Gynécologie','description'=>'Suivi de la sante de la femme et accompagnement de la grossesse.','image'=>'assets/images/Gynécologie.jpe'],
    ['id'=>5,'nom'=>'Ophtalmologie','description'=>'Diagnostic et traitement des troubles de la vision et des yeux.','image'=>'assets/images/Ophtalmologie.jpe'],
    ['id'=>6,'nom'=>'Médecine générale','description'=>'Consultations de premiere ligne et suivi global de la sante.','image'=>'assets/images/Medecine_generale.jpe'],
];
if ($pdo === null) {
    $errorMsg = $pdo_error ?? 'Service momentanément indisponible';
    $specialites = $fallback;
} else {
    try {
        $stmt = $pdo->query("SELECT id, nom, description, image FROM specialite ORDER BY id ASC");
        $specialites = $stmt->fetchAll();
        if (empty($specialites)) $specialites = $fallback;
    } catch (PDOException $e) {
        $errorMsg = $e->getMessage();
        $specialites = $fallback;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCab — Cabinet Médical / Téléconsultation</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main>
    <!-- Hero Section: Large Hero + Provided Hero image + title + description + CTA -->
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-text">
                    <div class="hero-kicker">● Téléconsultation disponible 7j/7</div>
                    <h1>Cabinet Médical<br><span>Téléconsultation</span> moderne</h1>
                    <p class="hero-desc">
                        Prenez rendez-vous en ligne, consultez nos spécialistes et gérez vos ordonnances en toute simplicité. Un parcours de soins premium, clean et professionnel.
                    </p>
                    <div class="hero-actions">
                        <a href="#specialites" class="btn btn-primary">Explorer les spécialités</a>
                        <a href="login.php" class="btn btn-outline">Accéder au Dashboard</a>
                    </div>
                </div>
                <div class="hero-media">
                    <!-- Hero image fournie : assets/images/hero.jpe -->
                    <img src="../assets/images/hero.jpe" alt="Cabinet médical — Hero">
                    <div class="hero-badge">
                        <span class="dot"></span>
                        <div>
                            <strong>1200+ consultations</strong><br>
                            <span style="color:var(--muted)">ce mois-ci</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search + Filter -->
    <section class="container" id="specialites">
        <h2 class="section-title">Nos spécialités</h2>
        <p class="section-sub">Découvrez nos spécialités médicales et prenez rendez-vous avec nos praticiens qualifiés.</p>

        <div class="toolbar">
            <label class="search" for="searchInput">
                <span>⌕</span>
                <input type="text" id="searchInput" placeholder="Rechercher une spécialité (ex: Cardiologie)">
            </label>
            <label class="filter">
                <span>Filtrer</span>
                <select id="filterSelect">
                    <option value="all">Toutes les spécialités</option>
                    <option value="Cardiologie">Cardiologie</option>
                    <option value="Dermatologie">Dermatologie</option>
                    <option value="Pédiatrie">Pédiatrie</option>
                    <option value="Gynécologie">Gynécologie</option>
                    <option value="Ophtalmologie">Ophtalmologie</option>
                    <option value="Médecine générale">Médecine générale</option>
                </select>
            </label>
        </div>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error" style="margin-bottom:12px">Nos spécialités sont affichées à partir de nos données de référence.</div>
        <?php endif; ?>

        <!-- Specialty Cards: 6, image via database value -->
        <div class="cards">
            <?php foreach ($specialites as $sp): ?>
                <?php
                    // Image chargée via la valeur stockée en DB (pas de mapping hard-codé séparé)
                    // Le champ `image` contient déjà le chemin relatif vers assets/images/
                    $dbImage = $sp['image'];
                    // Depuis pages/, on préfixe avec ../ si le chemin commence par assets/
                    $imgSrc = $dbImage;
                    if (strpos($dbImage, 'assets/') === 0) {
                        $imgSrc = '../' . $dbImage;
                    }
                    // Sécurité UTF-8 pour les noms avec accents
                ?>
                <article class="card" data-name="<?php echo htmlspecialchars($sp['nom'], ENT_QUOTES, 'UTF-8'); ?>" data-desc="<?php echo htmlspecialchars($sp['description'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="card-media">
                        <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sp['nom'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($sp['nom'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="card-desc"><?php echo htmlspecialchars($sp['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="card-meta">
                            <span class="badge">Spécialité</span>
                            <span>#<?php echo (int)$sp['id']; ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div id="noResults" class="empty" style="display:none">Aucune spécialité ne correspond à votre recherche.</div>

        <div id="apropos" style="margin-top:28px;background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px;box-shadow:var(--shadow)">
            <h3 style="margin-bottom:8px">À propos</h3>
            <p style="color:var(--muted)">MediCab est un cabinet médical moderne dédié à votre santé. Nos praticiens assurent un accompagnement personnalisé en présentiel et en téléconsultation, avec un suivi de qualité et des soins adaptés à chaque patient.</p>
        </div>
        <div id="contact" style="margin-top:14px;background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px;box-shadow:var(--shadow)">
            <h3 style="margin-bottom:8px">Contact</h3>
            <p style="color:var(--muted)">Contactez-nous : contact@medicab.local — 01 23 45 67 89 — 12 Rue de la Santé, 75014 Paris</p>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script src="../assets/js/main.js"></script>
</body>
</html>
