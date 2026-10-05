<?php
// actions/add.php — Create (Add) pour les 4 entités
// Utilise PDO + try/catch(PDOException) — pas de mysqli
require_once __DIR__ . '/../config/database.php';

$entity = $_GET['entity'] ?? null;
$allowed = ['patients','consultations','specialites','ordonnances'];
if (!in_array($entity, $allowed, true)) {
    header('Location: ../pages/dashboard.php?err=' . urlencode('Entité invalide'));
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
            throw new Exception('Champs obligatoires manquants (nom, prénom, email).');
        }

        $stmt = $pdo->prepare("INSERT INTO patient (nom, prenom, email, telephone, date_naissance, adresse) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nom, $prenom, $email, $telephone ?: null, $date_naissance, $adresse ?: null]);

        header('Location: ../pages/dashboard.php?entity=patients&msg=' . urlencode('Patient ajouté avec succès.'));
        exit;
    }

    if ($entity === 'specialites') {
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '');

        if ($nom === '' || $description === '' || $image === '') {
            throw new Exception('Champs obligatoires manquants (nom, description, image).');
        }

        $stmt = $pdo->prepare("INSERT INTO specialite (nom, description, image) VALUES (?,?,?)");
        $stmt->execute([$nom, $description, $image]);

        header('Location: ../pages/dashboard.php?entity=specialites&msg=' . urlencode('Spécialité ajoutée avec succès.'));
        exit;
    }

    if ($entity === 'consultations') {
        $patient_id = (int)($_POST['patient_id'] ?? 0);
        $specialite_id = (int)($_POST['specialite_id'] ?? 0);
        $date_consultation = trim($_POST['date_consultation'] ?? '');
        $motif = trim($_POST['motif'] ?? '');
        $statut = trim($_POST['statut'] ?? 'prevue');

        if (!$patient_id || !$specialite_id || $date_consultation === '' || $motif === '') {
            throw new Exception('Champs obligatoires manquants pour la consultation.');
        }
        // datetime-local fournit 2026-09-20T14:30 -> convertir en 2026-09-20 14:30:00
        $date_consultation = str_replace('T', ' ', $date_consultation);
        if (strlen($date_consultation) === 16) $date_consultation .= ':00';

        $stmt = $pdo->prepare("INSERT INTO consultation (patient_id, specialite_id, date_consultation, motif, statut) VALUES (?,?,?,?,?)");
        $stmt->execute([$patient_id, $specialite_id, $date_consultation, $motif, $statut]);

        header('Location: ../pages/dashboard.php?entity=consultations&msg=' . urlencode('Consultation ajoutée avec succès.'));
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
            throw new Exception('Champs obligatoires manquants pour l’ordonnance.');
        }

        $stmt = $pdo->prepare("INSERT INTO ordonnance (patient_id, consultation_id, medicament, posologie, duree, date_prescription) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$patient_id, $consultation_id, $medicament, $posologie, $duree, $date_prescription]);

        header('Location: ../pages/dashboard.php?entity=ordonnances&msg=' . urlencode('Ordonnance ajoutée avec succès.'));
        exit;
    }

} catch (PDOException $e) {
    $msg = 'Service momentanément indisponible — veuillez réessayer plus tard.';
    header('Location: ../pages/dashboard.php?entity=' . urlencode($entity) . '&err=' . urlencode($msg));
    exit;
} catch (Exception $e) {
    header('Location: ../pages/dashboard.php?entity=' . urlencode($entity) . '&err=' . urlencode($e->getMessage()));
    exit;
}

header('Location: ../pages/dashboard.php?entity=' . urlencode($entity) . '&err=' . urlencode('Requête invalide'));
exit;
