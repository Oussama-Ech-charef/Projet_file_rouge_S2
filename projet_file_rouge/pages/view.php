<?php
// pages/view.php — Detail Card : charge l'enregistrement sélectionné depuis MySQL via PDO try/catch(PDOException)
// Usage: view.php?entity=patient|consultation|specialite|ordonnance & id=PK
require_once __DIR__ . '/../config/database.php';

$entity = $_GET['entity'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$allowed = ['patient','patients','consultation','consultations','specialite','specialites','ordonnance','ordonnances'];
// normalisation
$map = [
    'patients'=>'patient','patient'=>'patient',
    'consultations'=>'consultation','consultation'=>'consultation',
    'specialites'=>'specialite','specialite'=>'specialite',
    'ordonnances'=>'ordonnance','ordonnance'=>'ordonnance',
];
$normalized = ($entity !== null && isset($map[$entity])) ? $map[$entity] : null;

$record = null;
$error = null;
$entityLabel = null;
$backEntity = null;

if (!$normalized || $id <= 0) {
    $error = 'Paramètres invalides.';
} elseif ($pdo === null) {
    $error = 'Service momentanément indisponible — veuillez réessayer plus tard.';
    $entityLabel = ucfirst($normalized);
    $backEntity = match($normalized){'patient'=>'patients','specialite'=>'specialites','consultation'=>'consultations','ordonnance'=>'ordonnances', default=>'patients'};
} else {
    try {
        if ($normalized === 'patient') {
            $entityLabel = 'Patient';
            $backEntity = 'patients';
            $stmt = $pdo->prepare("SELECT * FROM patient WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();
        } elseif ($normalized === 'specialite') {
            $entityLabel = 'Spécialité';
            $backEntity = 'specialites';
            $stmt = $pdo->prepare("SELECT * FROM specialite WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();
        } elseif ($normalized === 'consultation') {
            $entityLabel = 'Consultation';
            $backEntity = 'consultations';
            $stmt = $pdo->prepare("SELECT c.*, p.nom as p_nom, p.prenom as p_prenom, p.email as p_email, s.nom as s_nom, s.description as s_desc
                FROM consultation c
                LEFT JOIN patient p ON p.id = c.patient_id
                LEFT JOIN specialite s ON s.id = c.specialite_id
                WHERE c.id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();
        } elseif ($normalized === 'ordonnance') {
            $entityLabel = 'Ordonnance';
            $backEntity = 'ordonnances';
            $stmt = $pdo->prepare("SELECT o.*, p.nom as p_nom, p.prenom as p_prenom, c.motif as c_motif, c.date_consultation as c_date
                FROM ordonnance o
                LEFT JOIN patient p ON p.id = o.patient_id
                LEFT JOIN consultation c ON c.id = o.consultation_id
                WHERE o.id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch();
        }
    } catch (PDOException $e) {
        $error = 'Service momentanément indisponible — veuillez réessayer plus tard.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail — <?php echo htmlspecialchars($entityLabel ?? 'Record'); ?> — MediCab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard">
    <?php
    // sidebar needs entity param for active state
    $_GET['entity'] = $backEntity ?? 'patients';
    include __DIR__ . '/../includes/sidebar.php';
    ?>
    <div class="main">
        <div class="main-inner">
            <div class="page-head">
                <h1>Detail Card — <?php echo htmlspecialchars($entityLabel ?? 'Record'); ?></h1>
                <a class="btn btn-outline btn-sm" href="dashboard.php?entity=<?php echo htmlspecialchars($backEntity ?? 'patients'); ?>">← Retour à la table</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!$record): ?>
                <div class="detail-card">
                    <div class="detail-head"><strong>Record not found.</strong></div>
                    <div class="detail-body"><p style="color:var(--muted)">Aucun enregistrement trouvé pour <?php echo htmlspecialchars($entityLabel ?? $entity); ?> #<?php echo (int)$id; ?>.</p></div>
                </div>
            <?php else: ?>

                <div class="detail-card">
                    <div class="detail-head">
                        <strong><?php echo htmlspecialchars($entityLabel); ?> #<?php echo (int)$record['id']; ?></strong>
                        <div style="display:flex;gap:8px">
                            <a class="btn btn-outline btn-sm" href="dashboard.php?entity=<?php echo htmlspecialchars($backEntity); ?>&action=edit&id=<?php echo (int)$record['id']; ?>">Edit</a>
                            <a class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#fecaca" href="../actions/delete.php?entity=<?php echo htmlspecialchars($backEntity); ?>&id=<?php echo (int)$record['id']; ?>" onclick="return confirm('Supprimer ?')">Delete</a>
                        </div>
                    </div>
                    <div class="detail-body">
                        <?php if ($normalized === 'patient'): ?>
                            <div class="detail-item"><small>Nom</small><strong><?php echo htmlspecialchars($record['nom']); ?></strong></div>
                            <div class="detail-item"><small>Prénom</small><strong><?php echo htmlspecialchars($record['prenom']); ?></strong></div>
                            <div class="detail-item"><small>Email</small><strong><?php echo htmlspecialchars($record['email']); ?></strong></div>
                            <div class="detail-item"><small>Téléphone</small><strong><?php echo htmlspecialchars($record['telephone'] ?? '—'); ?></strong></div>
                            <div class="detail-item"><small>Date de naissance</small><strong><?php echo htmlspecialchars($record['date_naissance'] ?? '—'); ?></strong></div>
                            <div class="detail-item"><small>Adresse</small><strong><?php echo htmlspecialchars($record['adresse'] ?? '—'); ?></strong></div>
                            <div class="detail-item"><small>Créé le</small><strong><?php echo htmlspecialchars($record['created_at'] ?? '—'); ?></strong></div>
                            <div class="detail-item"><small>ID</small><strong><?php echo (int)$record['id']; ?></strong></div>

                        <?php elseif ($normalized === 'specialite'): ?>
                            <?php $img = $record['image']; if(strpos($img,'assets/')===0) $img='../'.$img; ?>
                            <div class="detail-media"><img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($record['nom']); ?>"></div>
                            <div class="detail-item"><small>Nom</small><strong><?php echo htmlspecialchars($record['nom']); ?></strong></div>
                            <div class="detail-item"><small>Image (DB)</small><strong><?php echo htmlspecialchars($record['image']); ?></strong></div>
                            <div class="detail-item full"><small>Description</small><strong><?php echo htmlspecialchars($record['description']); ?></strong></div>
                            <div class="detail-item"><small>ID</small><strong><?php echo (int)$record['id']; ?></strong></div>
                            <div class="detail-item"><small>Créé le</small><strong><?php echo htmlspecialchars($record['created_at'] ?? '—'); ?></strong></div>

                        <?php elseif ($normalized === 'consultation'): ?>
                            <div class="detail-item"><small>Patient</small><strong><?php echo htmlspecialchars(($record['p_prenom'] ?? '').' '.($record['p_nom'] ?? '')); ?> (ID <?php echo (int)$record['patient_id']; ?>)</strong></div>
                            <div class="detail-item"><small>Email patient</small><strong><?php echo htmlspecialchars($record['p_email'] ?? '—'); ?></strong></div>
                            <div class="detail-item"><small>Spécialité</small><strong><?php echo htmlspecialchars($record['s_nom'] ?? '—'); ?> (ID <?php echo (int)$record['specialite_id']; ?>)</strong></div>
                            <div class="detail-item"><small>Spécialité description</small><strong><?php echo htmlspecialchars($record['s_desc'] ?? '—'); ?></strong></div>
                            <div class="detail-item"><small>Date consultation</small><strong><?php echo htmlspecialchars($record['date_consultation']); ?></strong></div>
                            <div class="detail-item"><small>Statut</small><strong><?php echo htmlspecialchars($record['statut']); ?></strong></div>
                            <div class="detail-item full"><small>Motif</small><strong><?php echo htmlspecialchars($record['motif']); ?></strong></div>
                            <div class="detail-item"><small>ID</small><strong><?php echo (int)$record['id']; ?></strong></div>

                        <?php elseif ($normalized === 'ordonnance'): ?>
                            <div class="detail-item"><small>Patient</small><strong><?php echo htmlspecialchars(($record['p_prenom'] ?? '').' '.($record['p_nom'] ?? '')); ?> (ID <?php echo (int)$record['patient_id']; ?>)</strong></div>
                            <div class="detail-item"><small>Consultation</small><strong>#<?php echo (int)$record['consultation_id']; ?> — <?php echo htmlspecialchars($record['c_motif'] ?? '—'); ?> — <?php echo htmlspecialchars($record['c_date'] ?? ''); ?></strong></div>
                            <div class="detail-item"><small>Médicament</small><strong><?php echo htmlspecialchars($record['medicament']); ?></strong></div>
                            <div class="detail-item"><small>Posologie</small><strong><?php echo htmlspecialchars($record['posologie']); ?></strong></div>
                            <div class="detail-item"><small>Durée</small><strong><?php echo htmlspecialchars($record['duree']); ?></strong></div>
                            <div class="detail-item"><small>Date prescription</small><strong><?php echo htmlspecialchars($record['date_prescription']); ?></strong></div>
                            <div class="detail-item"><small>ID</small><strong><?php echo (int)$record['id']; ?></strong></div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
