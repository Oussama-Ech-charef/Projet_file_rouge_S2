<?php
declare(strict_types=1);
require_once __DIR__ . '/JsonStorage.php';

class Patient
{
    private JsonStorage $storage;
    public function __construct(string $file) { $this->storage = new JsonStorage($file); }
    public function all(): array { return $this->storage->all(); }
    public function find(int $id): ?array { foreach ($this->all() as $row) if ((int)$row['id_patient'] === $id) return $row; return null; }
    public function create(array $data): array
    {
        $row = $this->validate($data);
        $row['id_patient'] = $this->storage->nextId('id_patient');
        $rows = $this->all(); $rows[] = $row; $this->storage->save($rows); return $row;
    }
    public function update(int $id, array $data): array
    {
        $row = $this->validate($data); $rows = $this->all();
        foreach ($rows as $i => $item) if ((int)$item['id_patient'] === $id) { $row['id_patient'] = $id; $rows[$i] = $row; $this->storage->save($rows); return $row; }
        throw new RuntimeException('Patient introuvable.');
    }
    public function delete(int $id): void
    {
        $rows = array_values(array_filter($this->all(), static fn(array $r): bool => (int)$r['id_patient'] !== $id));
        $this->storage->save($rows);
    }
    private function validate(array $d): array
    {
        $nom = trim((string)($d['nom'] ?? '')); $prenom = trim((string)($d['prenom'] ?? ''));
        if ($nom === '' || $prenom === '') throw new InvalidArgumentException('Le nom et le prénom sont obligatoires.');
        $date = trim((string)($d['date_naissance'] ?? ''));
        if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) throw new InvalidArgumentException('La date de naissance est invalide.');
        $email = trim((string)($d['email'] ?? ''));
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('L’adresse e-mail est invalide.');
        return ['nom'=>$nom, 'prenom'=>$prenom, 'date_naissance'=>$date, 'telephone'=>trim((string)($d['telephone'] ?? '')), 'email'=>$email];
    }
}
