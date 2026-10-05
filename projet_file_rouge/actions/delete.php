<?php
// actions/delete.php — Delete pour les 4 entités
// PDO + try/catch(PDOException)
// Supprime uniquement l'enregistrement sélectionné
require_once __DIR__ . '/../config/database.php';

$entity = $_GET['entity'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$map = [
    'patients' => ['table' => 'patient', 'entity' => 'patients'],
    'patient' => ['table' => 'patient', 'entity' => 'patients'],
    'consultations' => ['table' => 'consultation', 'entity' => 'consultations'],
    'consultation' => ['table' => 'consultation', 'entity' => 'consultations'],
    'specialites' => ['table' => 'specialite', 'entity' => 'specialites'],
    'specialite' => ['table' => 'specialite', 'entity' => 'specialites'],
    'ordonnances' => ['table' => 'ordonnance', 'entity' => 'ordonnances'],
    'ordonnance' => ['table' => 'ordonnance', 'entity' => 'ordonnances'],
];

if (!isset($map[$entity]) || $id <= 0) {
    header('Location: ../pages/dashboard.php?err=' . urlencode('Paramètres invalides pour suppression.'));
    exit;
}

$table = $map[$entity]['table'];
$redirectEntity = $map[$entity]['entity'];

if ($pdo === null) {
    header('Location: ../pages/dashboard.php?entity=' . urlencode($redirectEntity) . '&err=' . urlencode('Service momentanément indisponible — veuillez réessayer plus tard.'));
    exit;
}
// Whitelist déjà validée via map, donc pas d'injection sur le nom de table (contrôle strict)
try {
    $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        header('Location: ../pages/dashboard.php?entity=' . urlencode($redirectEntity) . '&err=' . urlencode('Record not found.'));
        exit;
    }

    header('Location: ../pages/dashboard.php?entity=' . urlencode($redirectEntity) . '&msg=' . urlencode('Suppression réussie.'));
    exit;
} catch (PDOException $e) {
    // Gestion d'erreur FK : par exemple spécialité liée à une consultation (RESTRICT)
    $msg = 'Service momentanément indisponible — veuillez réessayer plus tard.';
    header('Location: ../pages/dashboard.php?entity=' . urlencode($redirectEntity) . '&err=' . urlencode($msg));
    exit;
}
