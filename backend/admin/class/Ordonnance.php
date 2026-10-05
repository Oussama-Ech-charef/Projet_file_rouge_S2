<?php
declare(strict_types=1);
require_once __DIR__ . '/JsonStorage.php';

class Ordonnance
{
    private JsonStorage $storage;
    public function __construct(string $file) { $this->storage=new JsonStorage($file); }
    public function all(): array { return $this->storage->all(); }
    public function find(int $id): ?array { foreach($this->all() as $r)if((int)$r['id_ordonnance']===$id)return $r;return null; }
    public function create(array $data): array
    {
        $r=$this->validate($data);$r['id_ordonnance']=$this->storage->nextId('id_ordonnance');$rows=$this->all();$rows[]=$r;$this->storage->save($rows);return $r;
    }
    public function update(int $id,array $data): array
    {
        $r=$this->validate($data,$id);$rows=$this->all();foreach($rows as $i=>$item)if((int)$item['id_ordonnance']===$id){$r['id_ordonnance']=$id;$rows[$i]=$r;$this->storage->save($rows);return $r;}throw new RuntimeException('Ordonnance introuvable.');
    }
    public function delete(int $id): void { $this->storage->save(array_values(array_filter($this->all(),static fn(array $r):bool=>(int)$r['id_ordonnance']!==$id))); }
    private function validate(array $d,?int $current=null): array
    {
        $consult=(int)($d['id_consultation']??0);if(!(new Consultation(__DIR__.'/../../database/consultations.json'))->find($consult))throw new InvalidArgumentException('Sélectionnez une consultation existante.');
        foreach($this->all() as $r)if((int)$r['id_consultation']===$consult&&(int)$r['id_ordonnance']!==$current)throw new InvalidArgumentException('Cette consultation possède déjà une ordonnance.');
        $date=trim((string)($d['date_creation']??''));if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date))throw new InvalidArgumentException('La date de création est obligatoire.');
        $content=trim((string)($d['contenu']??''));if($content==='')throw new InvalidArgumentException('Le contenu est obligatoire.');
        return ['id_consultation'=>$consult,'date_creation'=>$date,'contenu'=>$content,'recommandations'=>trim((string)($d['recommandations']??''))];
    }
}
