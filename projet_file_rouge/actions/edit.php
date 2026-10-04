<?php
// actions/edit.php — Update (Edit) pour les 4 entités
// PDO + try/catch(PDOException)
require_once __DIR__ . '/../config/database.php';

$entity = $_GET['entity'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$allowed = ['patients','consultations','specialites','ordonnances'];

if (!in_array($entity, $allowed, true) || $id <= 0) {
    header('Location: ../pages/dashboard.php?err=' . urlencode('Paramètres invalides pour édition.'));
    exit;
}

if ($pdo === null) {
    header('Location: ../pages/dashboard.php?entity=' . urlencode($entity) . '&err=' . urlencode('Service momentanément indisponible — veuillez réessayer plus tard.'));
    exit;
}
try {
    if ($entity === 'patients') {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $date_naissance = !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null;
        $adresse = trim($_POST['adresse'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '') {
            throw new Exception('Champs obligatoires manquants.');
        }

        $stmt = $pdo->prepare("UPDATE patient SET nom=?, prenom=?, email=?, telephone=?, date_naissance=?, adresse=? WHERE id=?");
        $stmt->execute([$nom, $prenom, $email, $telephone ?: null, $date_naissance, $adresse ?: null, $id]);

        header('Location: ../pages/dashboard.php?entity=patients&msg=' . urlencode('Patient modifié avec succès.'));
        exit;
    }

    if ($entity === 'specialites') {
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');

        if ($nom === '' || $description === '' || $image === '') {
            throw new Exception('Champs obligatoires manquants.');
        }

        $stmt = $pdo->prepare("UPDATE specialite SET nom=?, description=?, image=? WHERE id=?");
        $stmt->execute([$nom, $description, $image, $id]);

        header('Location: ../pages/dashboard.php?entity=specialites&msg=' . urlencode('Spécialité modifiée avec succès.'));
        exit;
    }

    if ($entity === 'consultations') {
        $patient_id = (int)($_POST['patient_id'] ?? 0);
        $specialite_id = (int)($_POST['specialite_id'] ?? 0);
        $date_consultation = trim($_POST['date_consultation'] ?? '');
        $motif = trim($_POST['motif'] ?? '');
        $statut = trim($_POST['statut'] ?? 'prevue');

        if (!$patient_id || !$specialite_id || $date_consultation === '' || $motif === '') {
            throw new Exception('Champs obligatoires manquants.');
        }
        $date_consultation = str_replace('T', ' ', $date_consultation);
        if (strlen($date_consultation) === 16) $date_consultation .= ':00';

        $stmt = $pdo->prepare("UPDATE consultation SET patient_id=?, specialite_id=?, date_consultation=?, motif=?, statut=? WHERE id=?");
        $stmt->execute([$patient_id, $specialite_id, $date_consultation, $motif, $statut, $id]);

        header('Location: ../pages/dashboard.php?entity=consultations&msg=' . urlencode('Consultation modifiée avec succès.'));
        exit;
    }

    if ($entity === 'ordonnances') {
        $patient_id = (int)($_POST['patient_id'] ?? 0);
        $consultation_id = (int)($_POST['consultation_id'] ?? 0);
        $medicament = trim($_POST['medicament'] ?? '');
        $posologie = trim($_POST['posologie'] ?? '');
        $duree = trim($_POST['duree'] ?? '');
        $date_prescription = trim($_POST['date_prescription'] ?? '');

        if (!$patient_id || !$consultation_id || $medicament === '' || $posologie === '' || $duree === '' || $date_prescription === '') {
            throw new Exception('Champs obligatoires manquants.');
        }

        $stmt = $pdo->prepare("UPDATE ordonnance SET patient_id=?, consultation_id=?, medicament=?, posologie=?, duree=?, date_prescription=? WHERE id=?");
        $stmt->execute([$patient_id, $consultation_id, $medicament, $posologie, $duree, $date_prescription, $id]);

        header('Location: ../pages/dashboard.php?entity=ordonnances&msg=' . urlencode('Ordonnance modifiée avec succès.'));
        exit;
    }

} catch (PDOException $e) {
    header('Location: ../pages/dashboard.php?entity=' . urlencode($entity) . '&err=' . urlencode('Service momentanément indisponible — veuillez réessayer plus tard.'));
    exit;
} catch (Exception $e) {
    header('Location: ../pages/dashboard.php?entity=' . urlencode($entity) . '&err=' . urlencode($e->getMessage()));
    exit;
}
