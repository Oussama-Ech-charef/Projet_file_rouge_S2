<?php
declare(strict_types=1);
require_once __DIR__ . '/JsonStorage.php';

class Consultation
{
    private JsonStorage $storage;
    public function __construct(string $file) { $this->storage=new JsonStorage($file); }
    public function all(): array { return $this->storage->all(); }
    public function find(int $id): ?array { foreach($this->all() as $r) if((int)$r['id_consultation']===$id)return $r; return null; }
    public function create(array $data): array
    {
        $r=$this->validate($data); $r['id_consultation']=$this->storage->nextId('id_consultation'); $rows=$this->all();$rows[]=$r;$this->storage->save($rows);return $r;
    }
    public function update(int $id,array $data): array
    {
        $r=$this->validate($data);$rows=$this->all();foreach($rows as $i=>$item)if((int)$item['id_consultation']===$id){$r['id_consultation']=$id;$rows[$i]=$r;$this->storage->save($rows);return $r;}throw new RuntimeException('Consultation introuvable.');
    }
    public function delete(int $id): void { $this->storage->save(array_values(array_filter($this->all(),static fn(array $r):bool=>(int)$r['id_consultation']!==$id))); }
    private function validate(array $d): array
    {
        $patient=(int)($d['id_patient']??0);$specialite=(int)($d['id_specialite']??0);
        if(!(new Patient(__DIR__.'/../../database/patients.json'))->find($patient))throw new InvalidArgumentException('Sélectionnez un patient existant.');
        if(!(new Specialite(__DIR__.'/../../database/specialites.json'))->find($specialite))throw new InvalidArgumentException('Sélectionnez une spécialité existante.');
        $date=trim((string)($d['date_heure']??''));$parsed=DateTime::createFromFormat('Y-m-d\TH:i',$date);
        if(!$parsed)throw new InvalidArgumentException('La date et l’heure sont obligatoires.');
        $motif=trim((string)($d['motif']??''));if($motif==='')throw new InvalidArgumentException('Le motif est obligatoire.');
        $type=(string)($d['type_consultation']??'presentiel');$status=(string)($d['statut']??'planifiee');
        if(!in_array($type,['presentiel','teleconsultation'],true)||!in_array($status,['planifiee','terminee','annulee'],true))throw new InvalidArgumentException('Le type ou le statut est invalide.');
        return ['id_patient'=>$patient,'id_specialite'=>$specialite,'date_heure'=>$parsed->format('Y-m-d\TH:i'),'motif'=>$motif,'diagnostic'=>trim((string)($d['diagnostic']??'')),'type_consultation'=>$type,'statut'=>$status];
    }
}
