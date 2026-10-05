<?php
// pages/dashboard.php â€” Dashboard avec Sidebar + Tables + Add/View/Edit/Delete
require_once __DIR__ . '/../config/database.php';

$entity = $_GET['entity'] ?? 'patients';
$allowed = ['patients','consultations','specialites','ordonnances'];
if (!in_array($entity, $allowed, true)) $entity = 'patients';

$action = $_GET['action'] ?? null; // add | edit
$editId = isset($_GET['id']) ? (int)$_GET['id'] : null;

$msg = $_GET['msg'] ?? null;
$err = $_GET['err'] ?? null;

// Helpers to fetch data â€” gÃ¨rent aussi le cas $pdo === null (fallback sans crash)
function fetchAllSafe($pdo, $sql, $params = []) {
    if ($pdo === null) {
        return ['__error' => 'Service momentanÃ©ment indisponible â€” veuillez rÃ©essayer plus tard.'];
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return ['__error' => 'Service momentanÃ©ment indisponible â€” veuillez rÃ©essayer plus tard.'];
    } catch (Throwable $e) {
        return ['__error' => 'Service momentanÃ©ment indisponible â€” veuillez rÃ©essayer plus tard.'];
    }
}
function fetchOneSafe($pdo, $sql, $params = []) {
    if ($pdo === null) {
        return null;
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    } catch (Throwable $e) {
        return null;
    }
}

// Data for tables
$patients = [];
$consultations = [];
$specialites = [];
$ordonnances = [];

if ($entity === 'patients' || $action === 'edit') {
    $patients = fetchAllSafe($pdo, "SELECT * FROM patient ORDER BY id DESC");
}
if ($entity === 'consultations') {
    $consultations = fetchAllSafe($pdo, "SELECT c.*, p.nom as p_nom, p.prenom as p_prenom, s.nom as s_nom
        FROM consultation c
        LEFT JOIN patient p ON p.id = c.patient_id
        LEFT JOIN specialite s ON s.id = c.specialite_id
        ORDER BY c.id DESC");
}
if ($entity === 'specialites' || $entity === 'consultations' || $entity === 'patients' && $action) {
    // need specialites for dropdowns and for specialites table
    $specialitesList = fetchAllSafe($pdo, "SELECT * FROM specialite ORDER BY id ASC");
    if ($entity === 'specialites') $specialites = $specialitesList;
    else $specialites = $specialitesList;
}
if ($entity === 'ordonnances') {
    $ordonnances = fetchAllSafe($pdo, "SELECT o.*, p.nom as p_nom, p.prenom as p_prenom, c.motif as c_motif
        FROM ordonnance o
        LEFT JOIN patient p ON p.id = o.patient_id
        LEFT JOIN consultation c ON c.id = o.consultation_id
        ORDER BY o.id DESC");
}
if ($entity === 'patients' && $entity !== 'specialites') {
    // also need specialites for dropdown? not needed
}
if ($entity === 'specialites') {
    // already
}
if ($entity === 'consultations' || $entity === 'ordonnances') {
    // need patients for dropdowns
    $allPatients = fetchAllSafe($pdo, "SELECT id, nom, prenom FROM patient ORDER BY nom");
    $allConsultations = fetchAllSafe($pdo, "SELECT id, motif, date_consultation FROM consultation ORDER BY id DESC");
}

// For edit form, fetch record
$editRecord = null;
if ($action === 'edit' && $editId) {
    if ($entity === 'patients') $editRecord = fetchOneSafe($pdo, "SELECT * FROM patient WHERE id = ?", [$editId]);
    if ($entity === 'specialites') $editRecord = fetchOneSafe($pdo, "SELECT * FROM specialite WHERE id = ?", [$editId]);
    if ($entity === 'consultations') $editRecord = fetchOneSafe($pdo, "SELECT * FROM consultation WHERE id = ?", [$editId]);
    if ($entity === 'ordonnances') $editRecord = fetchOneSafe($pdo, "SELECT * FROM ordonnance WHERE id = ?", [$editId]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard â€” MediCab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main">
        <div class="main-inner">

            <div class="page-head">
                <h1>
                    <?php
                    echo match($entity){
                        'patients' => 'Patients',
                        'consultations' => 'Consultations',
                        'specialites' => 'SpÃ©cialitÃ©s',
                        'ordonnances' => 'Ordonnances',
                        default => 'Dashboard'
                    };
                    ?>
                </h1>
                <div style="display:flex;gap:8px">
                    <a href="dashboard.php?entity=<?php echo htmlspecialchars($entity); ?>&action=add" class="btn btn-primary btn-sm">+ Ajouter</a>
                    <a href="home.php" class="btn btn-outline btn-sm">Voir le site</a>
                </div>
            </div>

            <?php if ($msg): ?><div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
            <?php if ($err): ?><div class="alert alert-error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

            <!-- ADD FORM -->
            <?php if ($action === 'add'): ?>
                <div class="card-table" style="padding:18px;margin-bottom:16px">
                    <h3 style="margin-bottom:12px">Ajouter â€” <?php echo htmlspecialchars(ucfirst($entity)); ?></h3>

                    <?php if ($entity === 'patients'): ?>
                        <form method="POST" action="../actions/add.php?entity=patients">
                            <div class="form-grid">
                                <div class="form-group"><label>Nom</label><input class="form-control" name="nom" required></div>
                                <div class="form-group"><label>PrÃ©nom</label><input class="form-control" name="prenom" required></div>
                                <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" required></div>
                                <div class="form-group"><label>TÃ©lÃ©phone</label><input class="form-control" name="telephone"></div>
                                <div class="form-group"><label>Date de naissance</label><input class="form-control" type="date" name="date_naissance"></div>
                                <div class="form-group"><label>Adresse</label><input class="form-control" name="adresse"></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=patients">Annuler</a>
                            </div>
                        </form>
                    <?php elseif ($entity === 'specialites'): ?>
                        <form method="POST" action="../actions/add.php?entity=specialites">
                            <div class="form-grid">
                                <div class="form-group"><label>Nom</label><input class="form-control" name="nom" placeholder="Cardiologie" required></div>
                                <div class="form-group"><label>Image (chemin)</label><input class="form-control" name="image" placeholder="assets/images/Cardiologie.jpe" required><small style="color:var(--muted)">Fichiers fournis: Cardiologie.jpe, Dermatologie.jpe, PÃ©diatrie.jpe, GynÃ©cologie.jpe, Ophtalmologie.jpe, Medecine_generale.jpe, hero.jpe</small></div>
                                <div class="form-group full"><label>Description</label><textarea class="form-control" name="description" rows="3" required></textarea></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=specialites">Annuler</a>
                            </div>
                        </form>
                    <?php elseif ($entity === 'consultations'): ?>
                        <form method="POST" action="../actions/add.php?entity=consultations">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Patient</label>
                                    <select class="form-control" name="patient_id" required>
                                        <option value="">â€” Choisir â€”</option>
                                        <?php foreach (($allPatients ?? []) as $p): if(isset($p['__error'])) continue; ?>
                                            <option value="<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['prenom'].' '.$p['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>SpÃ©cialitÃ©</label>
                                    <select class="form-control" name="specialite_id" required>
                                        <option value="">â€” Choisir â€”</option>
                                        <?php foreach (($specialites ?? $specialitesList ?? []) as $s): if(isset($s['__error'])) continue; ?>
                                            <option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars($s['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group"><label>Date consultation</label><input class="form-control" type="datetime-local" name="date_consultation" required></div>
                                <div class="form-group"><label>Statut</label><select class="form-control" name="statut"><option value="prevue">prÃ©vue</option><option value="terminee">terminÃ©e</option><option value="annulee">annulÃ©e</option></select></div>
                                <div class="form-group full"><label>Motif</label><input class="form-control" name="motif" required></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=consultations">Annuler</a>
                            </div>
                        </form>
                    <?php elseif ($entity === 'ordonnances'): ?>
                        <form method="POST" action="../actions/add.php?entity=ordonnances">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Patient</label>
                                    <select class="form-control" name="patient_id" required>
                                        <option value="">â€” Choisir â€”</option>
                                        <?php foreach (($allPatients ?? []) as $p): if(isset($p['__error'])) continue; ?>
                                            <option value="<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['prenom'].' '.$p['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Consultation</label>
                                    <select class="form-control" name="consultation_id" required>
                                        <option value="">â€” Choisir â€”</option>
                                        <?php foreach (($allConsultations ?? []) as $c): if(isset($c['__error'])) continue; ?>
                                            <option value="<?php echo (int)$c['id']; ?>">#<?php echo (int)$c['id']; ?> â€” <?php echo htmlspecialchars($c['motif']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group"><label>MÃ©dicament</label><input class="form-control" name="medicament" required></div>
                                <div class="form-group"><label>Posologie</label><input class="form-control" name="posologie" required></div>
                                <div class="form-group"><label>DurÃ©e</label><input class="form-control" name="duree" placeholder="7 jours" required></div>
                                <div class="form-group"><label>Date prescription</label><input class="form-control" type="date" name="date_prescription" required></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=ordonnances">Annuler</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- EDIT FORM -->
            <?php if ($action === 'edit' && $editRecord): ?>
                <div class="card-table" style="padding:18px;margin-bottom:16px">
                    <h3 style="margin-bottom:12px">Modifier â€” <?php echo htmlspecialchars(ucfirst($entity)); ?> #<?php echo (int)$editRecord['id']; ?></h3>

                    <?php if ($entity === 'patients'): ?>
                        <form method="POST" action="../actions/edit.php?entity=patients&id=<?php echo (int)$editRecord['id']; ?>">
                            <div class="form-grid">
                                <div class="form-group"><label>Nom</label><input class="form-control" name="nom" value="<?php echo htmlspecialchars($editRecord['nom']); ?>" required></div>
                                <div class="form-group"><label>PrÃ©nom</label><input class="form-control" name="prenom" value="<?php echo htmlspecialchars($editRecord['prenom']); ?>" required></div>
                                <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($editRecord['email']); ?>" required></div>
                                <div class="form-group"><label>TÃ©lÃ©phone</label><input class="form-control" name="telephone" value="<?php echo htmlspecialchars($editRecord['telephone'] ?? ''); ?>"></div>
                                <div class="form-group"><label>Date de naissance</label><input class="form-control" type="date" name="date_naissance" value="<?php echo htmlspecialchars($editRecord['date_naissance'] ?? ''); ?>"></div>
                                <div class="form-group"><label>Adresse</label><input class="form-control" name="adresse" value="<?php echo htmlspecialchars($editRecord['adresse'] ?? ''); ?>"></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Mettre Ã  jour</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=patients">Annuler</a>
                            </div>
                        </form>
                    <?php elseif ($entity === 'specialites'): ?>
                        <form method="POST" action="../actions/edit.php?entity=specialites&id=<?php echo (int)$editRecord['id']; ?>">
                            <div class="form-grid">
                                <div class="form-group"><label>Nom</label><input class="form-control" name="nom" value="<?php echo htmlspecialchars($editRecord['nom']); ?>" required></div>
                                <div class="form-group"><label>Image</label><input class="form-control" name="image" value="<?php echo htmlspecialchars($editRecord['image']); ?>" required></div>
                                <div class="form-group full"><label>Description</label><textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($editRecord['description']); ?></textarea></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Mettre Ã  jour</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=specialites">Annuler</a>
                            </div>
                        </form>
                    <?php elseif ($entity === 'consultations'): ?>
                        <form method="POST" action="../actions/edit.php?entity=consultations&id=<?php echo (int)$editRecord['id']; ?>">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Patient</label>
                                    <select class="form-control" name="patient_id" required>
                                        <?php foreach (($allPatients ?? []) as $p): if(isset($p['__error'])) continue; ?>
                                            <option value="<?php echo (int)$p['id']; ?>" <?php echo (int)$p['id']===(int)$editRecord['patient_id']?'selected':''; ?>><?php echo htmlspecialchars($p['prenom'].' '.$p['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>SpÃ©cialitÃ©</label>
                                    <select class="form-control" name="specialite_id" required>
                                        <?php foreach (($specialites ?? $specialitesList ?? []) as $s): if(isset($s['__error'])) continue; ?>
                                            <option value="<?php echo (int)$s['id']; ?>" <?php echo (int)$s['id']===(int)$editRecord['specialite_id']?'selected':''; ?>><?php echo htmlspecialchars($s['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group"><label>Date consultation</label><input class="form-control" type="datetime-local" name="date_consultation" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($editRecord['date_consultation']))); ?>" required></div>
                                <div class="form-group"><label>Statut</label><select class="form-control" name="statut"><option value="prevue" <?php echo $editRecord['statut']==='prevue'?'selected':'';?>>prÃ©vue</option><option value="terminee" <?php echo $editRecord['statut']==='terminee'?'selected':'';?>>terminÃ©e</option><option value="annulee" <?php echo $editRecord['statut']==='annulee'?'selected':'';?>>annulÃ©e</option></select></div>
                                <div class="form-group full"><label>Motif</label><input class="form-control" name="motif" value="<?php echo htmlspecialchars($editRecord['motif']); ?>" required></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Mettre Ã  jour</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=consultations">Annuler</a>
                            </div>
                        </form>
                    <?php elseif ($entity === 'ordonnances'): ?>
                        <form method="POST" action="../actions/edit.php?entity=ordonnances&id=<?php echo (int)$editRecord['id']; ?>">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Patient</label>
                                    <select class="form-control" name="patient_id" required>
                                        <?php foreach (($allPatients ?? []) as $p): if(isset($p['__error'])) continue; ?>
                                            <option value="<?php echo (int)$p['id']; ?>" <?php echo (int)$p['id']===(int)$editRecord['patient_id']?'selected':''; ?>><?php echo htmlspecialchars($p['prenom'].' '.$p['nom']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Consultation</label>
                                    <select class="form-control" name="consultation_id" required>
                                        <?php foreach (($allConsultations ?? []) as $c): if(isset($c['__error'])) continue; ?>
                                            <option value="<?php echo (int)$c['id']; ?>" <?php echo (int)$c['id']===(int)$editRecord['consultation_id']?'selected':''; ?>>#<?php echo (int)$c['id']; ?> â€” <?php echo htmlspecialchars($c['motif']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group"><label>MÃ©dicament</label><input class="form-control" name="medicament" value="<?php echo htmlspecialchars($editRecord['medicament']); ?>" required></div>
                                <div class="form-group"><label>Posologie</label><input class="form-control" name="posologie" value="<?php echo htmlspecialchars($editRecord['posologie']); ?>" required></div>
                                <div class="form-group"><label>DurÃ©e</label><input class="form-control" name="duree" value="<?php echo htmlspecialchars($editRecord['duree']); ?>" required></div>
                                <div class="form-group"><label>Date prescription</label><input class="form-control" type="date" name="date_prescription" value="<?php echo htmlspecialchars($editRecord['date_prescription']); ?>" required></div>
                            </div>
                            <div style="margin-top:12px;display:flex;gap:8px">
                                <button class="btn btn-primary" type="submit">Mettre Ã  jour</button>
                                <a class="btn btn-outline" href="dashboard.php?entity=ordonnances">Annuler</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php elseif ($action === 'edit' && !$editRecord): ?>
                <div class="alert alert-error">Record not found.</div>
            <?php endif; ?>

            <!-- TABLES -->
            <div class="card-table">
                <div class="table-wrap">
                    <?php if ($entity === 'patients'): ?>
                        <table>
                            <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>TÃ©lÃ©phone</th><th>Naissance</th><th>View</th><th>Edit</th><th>Delete</th></tr></thead>
                            <tbody>
                            <?php if (isset($patients['__error'])): ?>
                                <tr><td colspan="8" class="empty"><?php echo htmlspecialchars($patients['__error']); ?></td></tr>
                            <?php elseif (empty($patients)): ?>
                                <tr><td colspan="8" class="empty">Aucun patient.</td></tr>
                            <?php else: foreach ($patients as $r): ?>
                                <tr>
                                    <td><?php echo (int)$r['id']; ?></td>
                                    <td><?php echo htmlspecialchars($r['prenom'].' '.$r['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($r['email']); ?></td>
                                    <td><?php echo htmlspecialchars($r['telephone'] ?? 'â€”'); ?></td>
                                    <td><?php echo htmlspecialchars($r['date_naissance'] ?? 'â€”'); ?></td>
                                    <td><a class="btn btn-outline btn-sm" href="view.php?entity=patient&id=<?php echo (int)$r['id']; ?>">View</a></td>
                                    <td><a class="btn btn-outline btn-sm" href="dashboard.php?entity=patients&action=edit&id=<?php echo (int)$r['id']; ?>">Edit</a></td>
                                    <td><a class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#fecaca" href="../actions/delete.php?entity=patients&id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Supprimer ce patient ?')">Delete</a></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>

                    <?php elseif ($entity === 'consultations'): ?>
                        <table>
                            <thead><tr><th>ID</th><th>Patient</th><th>SpÃ©cialitÃ©</th><th>Date</th><th>Motif</th><th>Statut</th><th>View</th><th>Edit</th><th>Delete</th></tr></thead>
                            <tbody>
                            <?php if (isset($consultations['__error'])): ?>
                                <tr><td colspan="9" class="empty"><?php echo htmlspecialchars($consultations['__error']); ?></td></tr>
                            <?php elseif (empty($consultations)): ?>
                                <tr><td colspan="9" class="empty">Aucune consultation.</td></tr>
                            <?php else: foreach ($consultations as $r): ?>
                                <tr>
                                    <td><?php echo (int)$r['id']; ?></td>
                                    <td><?php echo htmlspecialchars(($r['p_prenom'] ?? '').' '.($r['p_nom'] ?? '')); ?></td>
                                    <td><span class="tag"><?php echo htmlspecialchars($r['s_nom'] ?? 'â€”'); ?></span></td>
                                    <td><?php echo htmlspecialchars($r['date_consultation']); ?></td>
                                    <td><?php echo htmlspecialchars($r['motif']); ?></td>
                                    <td><?php echo htmlspecialchars($r['statut']); ?></td>
                                    <td><a class="btn btn-outline btn-sm" href="view.php?entity=consultation&id=<?php echo (int)$r['id']; ?>">View</a></td>
                                    <td><a class="btn btn-outline btn-sm" href="dashboard.php?entity=consultations&action=edit&id=<?php echo (int)$r['id']; ?>">Edit</a></td>
                                    <td><a class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#fecaca" href="../actions/delete.php?entity=consultations&id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Supprimer cette consultation ?')">Delete</a></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>

                    <?php elseif ($entity === 'specialites'): ?>
                        <table>
                            <thead><tr><th>ID</th><th>Image</th><th>Nom</th><th>Description</th><th>View</th><th>Edit</th><th>Delete</th></tr></thead>
                            <tbody>
                            <?php if (isset($specialites['__error'])): ?>
                                <tr><td colspan="7" class="empty"><?php echo htmlspecialchars($specialites['__error']); ?></td></tr>
                            <?php elseif (empty($specialites)): ?>
                                <tr><td colspan="7" class="empty">Aucune spÃ©cialitÃ©.</td></tr>
                            <?php else: foreach ($specialites as $r): ?>
                                <?php $img = $r['image']; if(strpos($img,'assets/')===0) $img='../'.$img; ?>
                                <tr>
                                    <td><?php echo (int)$r['id']; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($img); ?>" alt="" style="width:56px;height:42px;object-fit:cover;border-radius:8px;border:1px solid var(--border)"></td>
                                    <td><strong><?php echo htmlspecialchars($r['nom']); ?></strong></td>
                                    <td style="max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo htmlspecialchars($r['description']); ?></td>
                                    <td><a class="btn btn-outline btn-sm" href="view.php?entity=specialite&id=<?php echo (int)$r['id']; ?>">View</a></td>
                                    <td><a class="btn btn-outline btn-sm" href="dashboard.php?entity=specialites&action=edit&id=<?php echo (int)$r['id']; ?>">Edit</a></td>
                                    <td><a class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#fecaca" href="../actions/delete.php?entity=specialites&id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Supprimer cette spÃ©cialitÃ© ?')">Delete</a></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>

                    <?php elseif ($entity === 'ordonnances'): ?>
                        <table>
                            <thead><tr><th>ID</th><th>Patient</th><th>Consultation</th><th>MÃ©dicament</th><th>Posologie</th><th>DurÃ©e</th><th>Date</th><th>View</th><th>Edit</th><th>Delete</th></tr></thead>
                            <tbody>
                            <?php if (isset($ordonnances['__error'])): ?>
                                <tr><td colspan="10" class="empty"><?php echo htmlspecialchars($ordonnances['__error']); ?></td></tr>
                            <?php elseif (empty($ordonnances)): ?>
                                <tr><td colspan="10" class="empty">Aucune ordonnance.</td></tr>
                            <?php else: foreach ($ordonnances as $r): ?>
                                <tr>
                                    <td><?php echo (int)$r['id']; ?></td>
                                    <td><?php echo htmlspecialchars(($r['p_prenom'] ?? '').' '.($r['p_nom'] ?? '')); ?></td>
                                    <td>#<?php echo (int)$r['consultation_id']; ?> â€” <?php echo htmlspecialchars($r['c_motif'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($r['medicament']); ?></td>
                                    <td><?php echo htmlspecialchars($r['posologie']); ?></td>
                                    <td><?php echo htmlspecialchars($r['duree']); ?></td>
                                    <td><?php echo htmlspecialchars($r['date_prescription']); ?></td>
                                    <td><a class="btn btn-outline btn-sm" href="view.php?entity=ordonnance&id=<?php echo (int)$r['id']; ?>">View</a></td>
                                    <td><a class="btn btn-outline btn-sm" href="dashboard.php?entity=ordonnances&action=edit&id=<?php echo (int)$r['id']; ?>">Edit</a></td>
                                    <td><a class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#fecaca" href="../actions/delete.php?entity=ordonnances&id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Supprimer cette ordonnance ?')">Delete</a></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <p style="margin-top:14px;color:var(--muted);font-size:13px">Gestion sÃ©curisÃ©e et centralisÃ©e de vos dossiers mÃ©dicaux et de vos consultations.</p>

        </div>
    </div>
</div>
</body>
</html>


