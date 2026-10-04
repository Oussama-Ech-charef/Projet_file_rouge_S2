# AI PROMPT — Projet Fil Rouge / Réalisation
## Cabinet Médical / Téléconsultation
## Current Sprint — Clean Start Specification

> This document is the main specification for the AI coding agent.
> Read it completely before creating or modifying anything.

---

# 1. Project Identity

## 1.1 Project

**Cabinet Médical / Téléconsultation**

## 1.2 Project Type

This project is the student's:

- **Projet Fil Rouge**
- **Projet de fin d'année**

It is a long-term project that will evolve during the academic year.

The project is not completed in one Sprint.

Each Sprint adds new features, improvements, or technologies to the same project.

The final objective is a complete **Web + Mobile application** that can be presented at the end of the academic year.

---

# 2. Important Difference: Prototype vs Réalisation

## 2.1 Prototype

The Prototype is a separate learning activity.

Prototype work is based on:

- Live Coding
- Manual coding
- Writing the code by hand

The Prototype may change subject during the training and is not the project described in this document.

## 2.2 Réalisation / Projet Fil Rouge

The Réalisation is the student's long-term Projet Fil Rouge and final-year project.

The Réalisation is developed using **AI Prompting**.

The AI coding agent is responsible for creating and modifying the project code according to this specification and the student's later instructions.

The student must still understand the project, its structure, its requirements, and its implementation.

```text
Prototype
→ Live Coding
→ Manual Code

Réalisation / Projet Fil Rouge
→ AI Prompting
→ AI-generated implementation
→ Sprint by Sprint
→ Final Web + Mobile Project
```

---

# 3. Development Method

The project must evolve progressively.

```text
Projet Fil Rouge
        ↓
Sprint 1
        ↓
Current Version
        ↓
Next Sprint
        ↓
New Features / Improvements
        ↓
Next Sprint
        ↓
...
        ↓
Final Project
```

## Rules

- Do not treat the current Sprint as the final project.
- Do not restart the project unnecessarily in future Sprints.
- Preserve correct existing functionality.
- Extend the same project progressively.
- Do not implement future Sprint features unless explicitly requested.

---

# 4. Current Technology Level

The current Sprint uses only:

- HTML
- CSS
- JavaScript
- PHP
- MySQL

The implementation must use **native PHP**.

Use **PDO** for the MySQL connection.

Do not introduce the following in the current Sprint:

- Laravel
- React
- NativePHP
- Tailwind CSS
- Other advanced frameworks
- Unrequested libraries
- Unrequested APIs

These are future technologies and may be introduced later when they are actually studied and explicitly requested.

---

# 5. Code Quality

The implementation must be:

- Simple
- Clean
- Organized
- Maintainable
- Easy to understand
- Easy to extend
- Appropriate for the student's current level

Do not over-engineer the project.

Do not imitate a complete Laravel architecture with native PHP.

Do not create unnecessary abstractions, layers, or dependencies.

---

# 6. Main AI Agent Rules

## 6.1 No Invented Requirements

Do not invent:

- Pages
- Features
- Database tables
- Database fields
- Relationships
- Medical rules
- Technologies
- Libraries
- APIs
- Validation rules
- Navigation items
- Design sections

Only implement what is explicitly specified in this document or explicitly requested later.

## 6.2 Ask Before Important Assumptions

If an important requirement is missing or unclear, stop and ask for clarification.

Do not decide important product or database behavior automatically.

## 6.3 Step-by-Step Work

Implement only the requested step.

After completing the requested step:

1. Verify the result.
2. Report what was done.
3. Stop.

Do not automatically continue to another step.

---

# 7. Current Database Foundation

The current project contains four main entities:

1. **Patient**
2. **Consultation**
3. **Spécialité**
4. **Ordonnance**

These entities belong to the same application.

```text
Cabinet Médical / Téléconsultation
            │
    ┌───────┼────────┬──────────┐
    ↓       ↓        ↓          ↓
 Patient  Consultation  Spécialité  Ordonnance
```

Do not add another entity unless it is explicitly required later.

Do not invent fields or relationships.

---

# 8. Database Documentation

The current project must include the following files:

```text
databases/
├── sql/
│   └── database.sql
│
└── migrations/
```

And:

```text
dictionnaire_donnees.csv
dependances_fonctionnelles.md
identifiants.md
```

All database documentation must describe the same database.

---

# 9. Database SQL

## 9.1 SQL File

The main SQL file is:

```text
databases/sql/database.sql
```

It represents the complete current database state.

It must contain the current project database structure for:

- Patient
- Consultation
- Spécialité
- Ordonnance

Do not create duplicate tables.

Do not create another database for the same project.

Do not invent database fields.

---

# 10. Database Migrations

Use:

```text
databases/migrations/
```

for explicit SQL schema changes.

The current required structural change is:

```text
Spécialité
+
image
```

The migration must add the `image` field to the existing `Spécialité` table.

Example organization:

```text
databases/
├── sql/
│   └── database.sql
│
└── migrations/
    └── 001_add_image_to_specialite.sql
```

The migration must not:

- Create a duplicate Spécialité table
- Delete existing data unnecessarily
- Change unrelated fields
- Change unrelated relationships

Keep migrations simple SQL files.

Do not create a complex migration framework.

---

# 11. PDO Database Connection

The database connection must be centralized in:

```text
config/database.php
```

Use:

- PDO
- `try`
- `catch`
- `PDOException`

Do not use `mysqli`.

Do not use another database connection method.

Do not create a separate database connection file for each page or action.

Keep one reusable database connection.

---

# 12. Data Dictionary

The file is:

```text
dictionnaire_donnees.csv
```

It must use exactly these columns:

```text
Interface | Name | Signification | Type | Obligatoire | Calculé
```

## Column Meaning

### Interface

The page, section, or project area where the data is used or displayed.

### Name

The exact field or database column name.

### Signification

A simple explanation of what the field represents.

### Type

The type or format of the data.

### Obligatoire

Indicates whether the field is required.

### Calculé

Indicates whether the value is calculated/generated or stored directly.

The Data Dictionary must document the approved fields of:

- Patient
- Consultation
- Spécialité
- Ordonnance

The `Spécialité.image` field must also be documented.

Do not invent fields.

---

# 13. Functional Dependencies

The file is:

```text
dependances_fonctionnelles.md
```

It must document the Functional Dependencies of the current entities.

The dependencies must match the approved database structure and identifiers.

General principle:

```text
Identifier → Attributes of the same entity
```

The dependency for `Spécialité` must include `image` when `image` is part of the specialty attributes.

Do not invent dependencies.

---

# 14. Table Identifiers

The file is:

```text
identifiants.md
```

It must identify the primary identifier of:

- Patient
- Consultation
- Spécialité
- Ordonnance

Each identifier must match the actual primary key in:

```text
databases/sql/database.sql
```

Do not create alternate identifiers without explicit instruction.

---

# 15. Database Consistency

These elements must remain consistent:

```text
database.sql
       ↕
dictionnaire_donnees.csv
       ↕
dependances_fonctionnelles.md
       ↕
identifiants.md
       ↕
PHP / MySQL Application
```

If the database structure changes, update every affected documentation file.

Do not modify only one source and leave the others inconsistent.

---

# 16. Project Structure

The project must use a simple and organized native PHP structure.

```text
Project/
│
├── config/
│   └── database.php
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
│
├── actions/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── js/
│   │   └── main.js
│   │
│   └── images/
│
├── databases/
│   ├── sql/
│   │   └── database.sql
│   │
│   └── migrations/
│
├── pages/
│   ├── home.php
│   ├── login.php
│   ├── dashboard.php
│   └── view.php
│
├── dictionnaire_donnees.csv
├── dependances_fonctionnelles.md
└── identifiants.md
```

The exact filenames may be adapted only when required by the existing project.

Do not create duplicate files.

Do not create all future files in advance.

---

# 17. Folder Responsibilities

## `config/`

Contains project configuration.

The database connection belongs here.

## `includes/`

Contains reusable shared elements:

- Header
- Footer
- Sidebar

Use shared includes when appropriate.

## `actions/`

Contains processing logic for:

- Add
- Edit / Update
- Delete
- Other explicitly required database operations

Keep processing logic separate from interface logic when practical.

## `assets/css/`

Contains CSS.

Use simple CSS and Flexbox where appropriate.

## `assets/js/`

Contains JavaScript required by the current interface.

Use JavaScript only when needed.

## `assets/images/`

Contains the existing project images.

Do not invent image filenames.

## `databases/`

Contains the current SQL database and SQL migrations.

## `pages/`

Contains the main application pages.

---

# 18. Current Sprint

The current Sprint builds the main Web interface of the project.

The main flow is:

```text
Home
  ↓
Login
  ↓
Dashboard
```

The current Sprint includes:

- Home
- Login
- Dashboard
- Sidebar
- Tables
- Add
- View
- Edit
- Delete
- Search
- Filter
- Spécialité Cards
- Detail Card
- SQL database
- SQL migration
- Data Dictionary
- Functional Dependencies
- Table Identifiers
- Modern visual design

---

# 19. Home Page

The Home page is the public entry page of the Web application.

Its exact structure is:

```text
Home
│
├── Header
├── Hero Section
├── Search + Filter
└── Spécialité Cards
```

Do not add unrelated sections.

---

# 20. Home Header

The Header must contain:

```text
Logo
Navigation Links
Login Button
```

## Important Rule

The public Home Header must **not** contain a Dashboard link.

The public Header must not show:

```text
Dashboard
```

The Login button is the entry point to the Login page.

---

# 21. Home Hero Section

The Hero must be large and visually important.

Use a modern composition:

```text
Hero
├── Text Content
└── Large Medical Image
```

The Hero must contain:

- Project title
- Short description
- One clear call-to-action
- One large medical image

The Hero image must come from the existing:

```text
assets/images/
```

The agent must inspect the existing images and use the intended Hero image.

Do not generate a new Hero image.

Do not invent image filenames.

Do not use a placeholder instead of the provided Hero image.

The Hero must feel:

- Modern
- Premium
- Clean
- Professional
- Medical
- Spacious

Do not keep the old basic/old-fashioned visual style.

---

# 22. Home Search + Filter

The Home must contain:

- Search
- Filter

These controls work with the public Home content.

The public Home content is:

**Spécialité**

## Search

Search specialty data.

Example:

```text
Cardiologie
```

The visible specialty cards must respond to the search text.

## Filter

Provide a simple filter using only criteria supported by the approved project data.

Do not invent a filter criterion.

Do not add complex filtering logic.

If a valid filter criterion cannot be determined from the approved data, ask before deciding.

---

# 23. Home Specialty Cards

The Home Cards are fixed to:

**Spécialité**

The Home must display six Specialty Cards.

The six specialties are:

1. Cardiologie
2. Dermatologie
3. Pédiatrie
4. Gynécologie
5. Ophtalmologie
6. Médecine générale

Each specialty must have its own image.

---

# 24. Seven Existing Images

The project already contains seven images inside:

```text
assets/images/
```

Use:

```text
1 image → Hero
6 images → Specialty Cards
```

The agent must inspect the folder before assigning image paths.

Use the real existing image filenames.

Do not:

- Invent filenames
- Generate new images
- Replace the supplied images
- Use unrelated placeholder images

If the mapping between a provided image and a specialty is genuinely unclear, ask before deciding.

---

# 25. Specialty Image Database Field

The `Spécialité` table must contain:

```text
image
```

The `image` field stores the path or filename of the image associated with the specialty.

The actual image file remains inside:

```text
assets/images/
```

Do not store binary image data in MySQL.

Do not create a separate image table.

Do not create a second image field for the same purpose.

---

# 26. Specialty Image Mapping

Map the six existing specialty images to:

```text
Cardiologie
Dermatologie
Pédiatrie
Gynécologie
Ophtalmologie
Médecine générale
```

The mapping must use the actual filenames found in:

```text
assets/images/
```

Store the corresponding path or filename in the `Spécialité.image` database field.

The Home Card must read the image path from the database.

Required flow:

```text
Spécialité record
      ↓
image field
      ↓
Stored path / filename
      ↓
assets/images/
      ↓
Home Specialty Card
```

Do not hard-code a different image directly inside each card when the database already provides the image path.

---

# 27. Specialty Card Content

Each Home Specialty Card should contain:

```text
Image
Specialty Name
Description
```

Use only approved fields from the `Spécialité` database definition.

Do not invent additional fields.

Do not create database fields only for visual purposes.

The cards must be:

- Modern
- Premium
- Clean
- Professional
- Responsive
- Visually consistent

Use appropriate image cropping, spacing, typography, border radius, and subtle shadows.

Do not add:

- Edit
- Delete
- Admin actions

to public Home Cards.

---

# 28. Login Page

The Login page is a simple UI and navigation page.

It must contain:

```text
Email Input
Password Input
Login Button
```

The current flow is:

```text
Home
  ↓
Login
  ↓
Click Login
  ↓
Dashboard
```

## Important Restriction

The current Sprint does not require real authentication.

Do not implement:

- Credential verification
- Password verification
- Database authentication
- Sessions
- Authorization
- Registration
- Password recovery
- Logout system
- Authentication middleware

The Login button must be able to navigate to the Dashboard even when the inputs are empty.

This is a UI/navigation prototype only.

---

# 29. Dashboard

The Dashboard is the management area.

Its exact structure is:

```text
Dashboard
│
├── Sidebar
└── Main Content
     └── Tables
```

The Dashboard must be based on Tables.

Do not replace the main Dashboard content with statistic cards.

---

# 30. Dashboard Sidebar

The Sidebar must provide navigation to the current management sections:

```text
Dashboard
Patients
Consultations
Spécialités
Ordonnances
```

Only create links to sections that actually exist.

Do not add unrelated links.

The Sidebar must be reusable.

---

# 31. Dashboard Tables

Each management section uses a Table.

Each row represents one database record.

General structure:

```text
ID | Name / Title | ... | View | Edit | Delete
```

The exact data columns depend on the selected entity.

Use only approved database fields.

Do not invent columns.

---

# 32. CRUD

The Dashboard must use the complete CRUD concept:

```text
C = Create → Add
R = Read   → View
U = Update → Edit
D = Delete → Delete
```

The required actions are:

```text
Add
View
Edit
Delete
```

CRUD applies to the current project management entities according to their approved database definitions.

---

# 33. Add

The `Add` operation creates a new record.

The Add form must use the approved fields of the selected entity.

Example:

```text
Patients
  ↓
Add Patient
  ↓
Form
  ↓
Database
```

After a successful Add operation:

```text
Add
 ↓
Success
 ↓
Return to the related Table
```

Do not invent form fields.

---

# 34. Edit

The `Edit` operation modifies one existing record.

The flow is:

```text
Table
  ↓
Edit
  ↓
Edit Form
  ↓
Update
  ↓
Return to Table
```

The Edit form must load the existing values of the selected record.

Use the existing record identifier.

Do not update another record.

---

# 35. Delete

The `Delete` operation removes one selected record.

The flow is:

```text
Table
  ↓
Delete
  ↓
Selected Record Removed
  ↓
Updated Table
```

Delete only the selected record.

Do not implement:

- Bulk delete
- Automatic deletion of multiple records
- Database/table deletion

unless explicitly requested later.

---

# 36. View

The `View` action displays one selected record.

The flow is:

```text
Dashboard
  ↓
Management Table
  ↓
Select one row
  ↓
View
  ↓
Detail Card
```

The selected record must be identified using the existing database primary key.

---

# 37. Detail Card

The Detail Card displays the information of the selected record.

The data must come from MySQL.

The displayed values must correspond exactly to the selected record.

Do not display:

- Fake data
- Static unrelated data
- Another record's data

If the record does not exist, display a simple message:

```text
Record not found.
```

Provide a simple way to return to the related Table.

---

# 38. Difference Between Home Cards and Detail Card

These are different concepts.

## Home Cards

```text
Home
  ↓
Spécialité
  ↓
Public Specialty Card
```

Purpose:

Public presentation of medical specialties.

## Detail Card

```text
Dashboard
  ↓
Table
  ↓
View
  ↓
Selected Record
  ↓
Detail Card
```

Purpose:

Detailed information about one selected database record.

Do not confuse these two card types.

---

# 39. Public Data and Privacy

The Home page is public.

Do not display private:

- Patient information
- Prescription information
- Medical records

The public Home Cards are for:

**Spécialité**

Patient, consultation details involving personal information, and prescription information belong to the appropriate management/detail context.

---

# 40. Actions and Page Separation

Use a simple separation:

```text
Page
  ↓
Interface / Form / Table
  ↓
Action
  ↓
Database
```

Pages are responsible mainly for interface/display.

Actions are responsible for processing.

The database is responsible for storing data.

Do not put all processing and interface logic into one huge file when a simple separation is possible.

Do not create a complex MVC architecture.

---

# 41. Shared Components

Use:

```text
includes/
├── header.php
├── footer.php
└── sidebar.php
```

## Header

Used by the relevant public pages.

The public Header must contain:

```text
Logo
Navigation Links
Login Button
```

No Dashboard link in the public Header.

## Footer

Use a simple shared Footer where needed.

## Sidebar

Use the shared Sidebar inside Dashboard-related pages.

---

# 42. Current Sprint File Rules

Create only the files needed for the current Sprint.

Before creating a file:

1. Inspect the existing project.
2. Check whether a file with the same responsibility already exists.
3. Reuse it when appropriate.
4. Create a new file only when it is genuinely required.

Do not create duplicate:

- Home pages
- Login pages
- Dashboard pages
- Database connections
- CSS files
- JavaScript files
- Tables
- Actions

---

# 43. Existing Project Rule

If an existing project already exists, inspect it first.

Do not delete or recreate the entire project unnecessarily.

Preserve correct functionality.

Modify only what is required by the current specification.

However, if starting from a completely empty project, create the structure defined in this document.

---

# 44. Current Step: Clean Start

When this specification is given as a clean-start implementation request, build the current Sprint from the beginning using this exact specification.

The first implementation must establish:

```text
Project structure
+
Database
+
Database documentation
+
PDO connection
+
Home
+
Login
+
Dashboard
+
CRUD
+
View / Detail Card
+
Image integration
+
Modern Home redesign
```

Do not add anything outside this scope.

---

# 45. Current Home Visual Direction

The Home must not look old, generic, or like a basic school template.

The target visual direction is:

```text
Modern
Premium
Clean
Professional
Healthcare
Responsive
```

## Header

- Clean layout
- Logo clearly visible
- Public navigation links
- Login button
- No Dashboard link

## Hero

- Large
- Strong visual impact
- Large medical image
- Clear text
- Modern spacing
- Professional healthcare appearance

## Search + Filter

- Clean controls
- Easy to understand
- Proper spacing
- Consistent with the rest of the page

## Specialty Cards

- Six cards
- One image per specialty
- Strong image presentation
- Clear specialty name
- Short description
- Consistent visual style

Do not use an outdated visual style.

---

# 46. Current Sprint — Exact Acceptance Criteria

## Project

```text
✓ Same Projet Fil Rouge
✓ Current Sprint only
✓ Native PHP
✓ HTML
✓ CSS
✓ JavaScript
✓ MySQL
✓ PDO
```

## Database

```text
✓ Patient
✓ Consultation
✓ Spécialité
✓ Ordonnance
✓ Spécialité.image
✓ database.sql
✓ migration for Spécialité.image
✓ PDO try/catch(PDOException)
```

## Documentation

```text
✓ dictionnaire_donnees.csv
✓ Interface | Name | Signification | Type | Obligatoire | Calculé
✓ dependances_fonctionnelles.md
✓ identifiants.md
✓ Documentation consistent with SQL
```

## Home

```text
✓ Header
✓ Logo
✓ Navigation Links
✓ Login Button
✓ No Dashboard link in public Header
✓ Large Hero
✓ Provided Hero image
✓ Search
✓ Filter
✓ Six Specialty Cards
✓ Six provided specialty images
✓ Images loaded through database paths
✓ Modern redesign
✓ Responsive layout
```

## Login

```text
✓ Email Input
✓ Password Input
✓ Login Button
✓ Navigation to Dashboard
✓ No real authentication
```

## Dashboard

```text
✓ Sidebar
✓ Patients
✓ Consultations
✓ Spécialités
✓ Ordonnances
✓ Tables
✓ Add
✓ View
✓ Edit
✓ Delete
```

## View

```text
✓ Selected record identified correctly
✓ Data loaded from MySQL
✓ Detail Card
✓ Correct selected record
✓ Record not found message
✓ Return to related Table
```

## Restrictions

```text
✓ No Laravel
✓ No React
✓ No NativePHP
✓ No Tailwind CSS
✓ No mobile code
✓ No real authentication
✓ No unrequested entities
✓ No unrequested fields
✓ No unrequested pages
✓ No complex architecture
✓ No unrequested libraries
```

---

# 47. Final Verification

Before declaring the current Sprint complete:

1. Inspect the final project structure.
2. Check all required pages.
3. Check the database.
4. Check the migration.
5. Check the Data Dictionary.
6. Check Functional Dependencies.
7. Check Identifiers.
8. Check Home.
9. Check Login.
10. Check Dashboard.
11. Check CRUD.
12. Check View / Detail Card.
13. Check all seven images.
14. Check database image paths.
15. Check that the public Header has no Dashboard link.
16. Check that no forbidden technology was added.
17. Check that no unrequested feature was added.

Do not claim a test was performed if it was not actually performed.

---

# 48. Final Report

After completing the requested implementation, report:

## Implemented

What was implemented.

## Modified Files

Which files were created or modified.

## Database Changes

What database changes were made and which migration was created.

## Verification

What was actually checked.

## Notes

Only real issues, limitations, or decisions requiring attention.

Keep the report concise and factual.

---

# 49. Stop Rule

After completing the requested current Sprint:

```text
Implement
   ↓
Verify
   ↓
Report
   ↓
STOP
```

Do not continue automatically to another Sprint.

Do not add future features.

Wait for the next explicit instruction.

---

# 50. Long-Term Vision

The final project may evolve progressively toward:

```text
HTML + CSS + JavaScript + PHP + MySQL
                    ↓
             Web Application
                    ↓
              More Sprints
                    ↓
             Tailwind CSS
                    ↓
                Laravel
                    ↓
              Backend / API
                    ↓
             Mobile Stage
                    ↓
        Final Web + Mobile Project
```

This is future scope.

Do not implement the future architecture during the current Sprint unless explicitly requested.
