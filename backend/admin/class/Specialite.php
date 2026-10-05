<?php
declare(strict_types=1);
require_once __DIR__ . '/JsonStorage.php';

class Specialite
{
    private JsonStorage $storage;
    public function __construct(string $file) { $this->storage = new JsonStorage($file); }
    public function all(): array { return $this->storage->all(); }
    public function find(int $id): ?array { foreach ($this->all() as $row) if ((int)$row['id_specialite'] === $id) return $row; return null; }
    public function create(array $data): array
    {
        $row = $this->validate($data); $row['id_specialite'] = $this->storage->nextId('id_specialite');
        $rows=$this->all(); $rows[]=$row; $this->storage->save($rows); return $row;
    }
    public function update(int $id, array $data): array
    {
        $row=$this->validate($data); $rows=$this->all();
        foreach($rows as $i=>$item) if((int)$item['id_specialite']===$id){$row['id_specialite']=$id;$rows[$i]=$row;$this->storage->save($rows);return $row;}
        throw new RuntimeException('Spécialité introuvable.');
    }
    public function delete(int $id): void { $this->storage->save(array_values(array_filter($this->all(),static fn(array $r):bool=>(int)$r['id_specialite']!==$id))); }
    private function validate(array $d): array
    {
        $name=trim((string)($d['nom_specialite']??'')); if($name==='') throw new InvalidArgumentException('Le nom de la spécialité est obligatoire.');
        return ['nom_specialite'=>$name,'description'=>trim((string)($d['description']??''))];
    }
}
