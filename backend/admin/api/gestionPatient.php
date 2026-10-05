<?php
require_once __DIR__.'/common.php'; require_once __DIR__.'/../class/Patient.php'; require_once __DIR__.'/../class/Consultation.php';
$model=new Patient(__DIR__.'/../../database/patients.json');
respond(function(string $method,array $body)use($model){
    if($method==='GET')return isset($_GET['id'])?$model->find(requestId()):$model->all();
    if($method==='POST')return $model->create($body);
    if($method==='PUT')return $model->update(requestId(),$body);
    if($method==='DELETE'){$id=requestId();$consultations=(new Consultation(__DIR__.'/../../database/consultations.json'))->all();deleteGuard((bool)array_filter($consultations,static fn($r)=>(int)$r['id_patient']===$id),'Ce patient a des consultations. Supprimez-les avant de supprimer le patient.');$model->delete($id);return ['id_patient'=>$id];}
    throw new InvalidArgumentException('Méthode non prise en charge.');
});
