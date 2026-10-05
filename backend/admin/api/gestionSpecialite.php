<?php
require_once __DIR__.'/common.php'; require_once __DIR__.'/../class/Specialite.php'; require_once __DIR__.'/../class/Consultation.php';
$model=new Specialite(__DIR__.'/../../database/specialites.json');
respond(function(string $method,array $body)use($model){
    if($method==='GET')return isset($_GET['id'])?$model->find(requestId()):$model->all();
    if($method==='POST')return $model->create($body);
    if($method==='PUT')return $model->update(requestId(),$body);
    if($method==='DELETE'){$id=requestId();$rows=(new Consultation(__DIR__.'/../../database/consultations.json'))->all();deleteGuard((bool)array_filter($rows,static fn($r)=>(int)$r['id_specialite']===$id),'Cette spécialité est utilisée par des consultations. Supprimez-les avant de supprimer la spécialité.');$model->delete($id);return ['id_specialite'=>$id];}
    throw new InvalidArgumentException('Méthode non prise en charge.');
});
