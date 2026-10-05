<?php
require_once __DIR__.'/common.php'; require_once __DIR__.'/../class/Consultation.php'; require_once __DIR__.'/../class/Ordonnance.php';
$model=new Ordonnance(__DIR__.'/../../database/ordonnances.json');
respond(function(string $method,array $body)use($model){
    if($method==='GET')return isset($_GET['id'])?$model->find(requestId()):$model->all();
    if($method==='POST')return $model->create($body);
    if($method==='PUT')return $model->update(requestId(),$body);
    if($method==='DELETE'){$id=requestId();$model->delete($id);return ['id_ordonnance'=>$id];}
    throw new InvalidArgumentException('Méthode non prise en charge.');
});
