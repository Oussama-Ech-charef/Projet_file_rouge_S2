<?php
declare(strict_types=1);

class JsonStorage
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
        $directory = dirname($file);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        if (!file_exists($file)) {
            file_put_contents($file, "[]", LOCK_EX);
        }
    }

    public function all(): array
    {
        $content = file_get_contents($this->file);
        $data = json_decode($content === false ? '[]' : $content, true);
        return is_array($data) ? $data : [];
    }

    public function save(array $rows): void
    {
        $json = json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false || file_put_contents($this->file, $json, LOCK_EX) === false) {
            throw new RuntimeException('Impossible d’enregistrer les données.');
        }
    }

    public function nextId(string $field): int
    {
        $ids = array_map(static fn(array $row): int => (int)($row[$field] ?? 0), $this->all());
        return ($ids ? max($ids) : 0) + 1;
    }
}
