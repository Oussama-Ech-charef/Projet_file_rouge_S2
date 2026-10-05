# Dépendances Fonctionnelles — Cabinet Médical / Téléconsultation

> Ce document décrit les dépendances fonctionnelles du modèle actuel.
> Chaque dépendance est de la forme `Identifiant → Attributs de la même entité`.
> Le document est cohérent avec `databases/sql/database.sql`, `dictionnaire_donnees.csv` et `identifiants.md`.

## 1. Patient

```
patient.id → patient.nom, patient.prenom, patient.email, patient.telephone, patient.date_naissance, patient.adresse, patient.created_at
```

- `id` détermine fonctionnellement tous les attributs du Patient.
- `email` est informatif mais n'est pas un déterminant fonctionnel principal dans ce modèle (un patient est identifié par `id`).

## 2. Spécialité (avec `image`)

```
specialite.id → specialite.nom, specialite.description, specialite.image, specialite.created_at
specialite.nom → specialite.id, specialite.description, specialite.image
```

- `id` est l'identifiant primaire : il détermine `nom`, `description`, `image`.
- `nom` est UNIQUE : il détermine également `id`, `description`, `image` (dépendance secondaire liée à la contrainte d'unicité).
- Le champ `image` (VARCHAR 255) fait partie des attributs dépendants de `id` : `specialite.id → specialite.image`.
- `image` stocke le chemin vers `assets/images/` (ex: `assets/images/Cardiologie.jpe`).

## 3. Consultation

```
consultation.id → consultation.patient_id, consultation.specialite_id, consultation.date_consultation, consultation.motif, consultation.statut, consultation.created_at
```

- `id` détermine tous les attributs de la consultation, y compris les clés étrangères.
- Dépendances d'inclusion (intégrité référentielle) :
  - `consultation.patient_id` → `patient.id`
  - `consultation.specialite_id` → `specialite.id`

## 4. Ordonnance

```
ordonnance.id → ordonnance.consultation_id, ordonnance.patient_id, ordonnance.medicament, ordonnance.posologie, ordonnance.duree, ordonnance.date_prescription, ordonnance.created_at
```

- `id` détermine tous les attributs de l'ordonnance.
- Dépendances d'inclusion :
  - `ordonnance.consultation_id` → `consultation.id`
  - `ordonnance.patient_id` → `patient.id`

## 5. Cohérence globale

```
database.sql
      ↕
dictionnaire_donnees.csv
      ↕
dependances_fonctionnelles.md
      ↕
identifiants.md
      ↕
Application PHP / MySQL
```

Toute modification de structure (ex: ajout de `specialite.image`) doit mettre à jour l'ensemble des documents.
