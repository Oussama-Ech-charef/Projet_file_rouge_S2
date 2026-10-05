<?php
require_once __DIR__.'/common.php'; require_once __DIR__.'/../class/Patient.php'; require_once __DIR__.'/../class/Specialite.php'; require_once __DIR__.'/../class/Consultation.php'; require_once __DIR__.'/../class/Ordonnance.php';
$model=new Consultation(__DIR__.'/../../database/consultations.json');
respond(function(string $method,array $body)use($model){
    if($method==='GET')return isset($_GET['id'])?$model->find(requestId()):$model->all();
    if($method==='POST')return $model->create($body);
    if($method==='PUT')return $model->update(requestId(),$body);
    if($method==='DELETE'){$id=requestId();$rows=(new Ordonnance(__DIR__.'/../../database/ordonnances.json'))->all();deleteGuard((bool)array_filter($rows,static fn($r)=>(int)$r['id_consultation']===$id),'Cette consultation possède une ordonnance. Supprimez d’abord l’ordonnance.');$model->delete($id);return ['id_consultation'=>$id];}
    throw new InvalidArgumentException('Méthode non prise en charge.');
});
