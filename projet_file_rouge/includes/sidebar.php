<?php
// includes/sidebar.php — Sidebar réutilisable pour le Dashboard
// Sections: Dashboard, Patients, Consultations, Spécialités, Ordonnances
$entity = $_GET['entity'] ?? 'patients';
$active = $entity;
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="logo-icon">✚</span>
        <span class="sidebar-title">MediCab</span>
        <span class="sidebar-subtitle">Dashboard</span>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php?entity=patients" class="sidebar-link <?php echo $active === 'patients' ? 'active' : ''; ?>">Patients</a>
        <a href="dashboard.php?entity=consultations" class="sidebar-link <?php echo $active === 'consultations' ? 'active' : ''; ?>">Consultations</a>
        <a href="dashboard.php?entity=specialites" class="sidebar-link <?php echo $active === 'specialites' ? 'active' : ''; ?>">Spécialités</a>
        <a href="dashboard.php?entity=ordonnances" class="sidebar-link <?php echo $active === 'ordonnances' ? 'active' : ''; ?>">Ordonnances</a>
        <div class="sidebar-divider"></div>
        <a href="home.php" class="sidebar-link sidebar-link-muted">← Retour à l'accueil</a>
    </nav>
    <div class="sidebar-footer">
        <small>Cabinet Médical<br/>Téléconsultation</small>
    </div>
</aside>
