# Identifiants — Cabinet Médical / Téléconsultation

> Ce document identifie la clé primaire de chaque table.
> Chaque identifiant correspond exactement à la PRIMARY KEY définie dans `databases/sql/database.sql`.

## 1. Patient

- **Table :** `patient`
- **Identifiant (PK) :** `id`
- **Type :** `INT AUTO_INCREMENT`
- **Définition SQL :** `id INT AUTO_INCREMENT PRIMARY KEY`

## 2. Spécialité

- **Table :** `specialite`
- **Identifiant (PK) :** `id`
- **Type :** `INT AUTO_INCREMENT`
- **Définition SQL :** `id INT AUTO_INCREMENT PRIMARY KEY`
- **Contrainte UNIQUE :** `nom VARCHAR(100) NOT NULL UNIQUE`
- **Champ ajouté par migration :** `image VARCHAR(255) NOT NULL` (voir `databases/migrations/001_add_image_to_specialite.sql`)

## 3. Consultation

- **Table :** `consultation`
- **Identifiant (PK) :** `id`
- **Type :** `INT AUTO_INCREMENT`
- **Définition SQL :** `id INT AUTO_INCREMENT PRIMARY KEY`
- **Clés étrangères :**
  - `patient_id` → `patient(id)`
  - `specialite_id` → `specialite(id)`

## 4. Ordonnance

- **Table :** `ordonnance`
- **Identifiant (PK) :** `id`
- **Type :** `INT AUTO_INCREMENT`
- **Définition SQL :** `id INT AUTO_INCREMENT PRIMARY KEY`
- **Clés étrangères :**
  - `consultation_id` → `consultation(id)`
  - `patient_id` → `patient(id)`

## Cohérence

```
identifiants.md  ↔  database.sql  ↔  dictionnaire_donnees.csv  ↔  dependances_fonctionnelles.md
```

Aucun identifiant alternatif n'est utilisé sans instruction explicite.
