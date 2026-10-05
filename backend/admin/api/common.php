<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function respond(callable $action): void
{
    try {
        $method=$_SERVER['REQUEST_METHOD'];
        $body=json_decode(file_get_contents('php://input'),true);
        if(in_array($method,['POST','PUT'],true)&&!is_array($body))throw new InvalidArgumentException('Les données reçues sont invalides.');
        $result=$action($method,is_array($body)?$body:[]);
        echo json_encode(['success'=>true,'data'=>$result],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    } catch (InvalidArgumentException $e) {
        http_response_code(422); echo json_encode(['success'=>false,'message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);
    } catch (RuntimeException $e) {
        http_response_code(409); echo json_encode(['success'=>false,'message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        http_response_code(500); echo json_encode(['success'=>false,'message'=>'Une erreur est survenue. Vérifiez que les fichiers JSON sont accessibles en écriture.'],JSON_UNESCAPED_UNICODE);
    }
}

function requestId(): int
{
    $id=(int)($_GET['id']??0); if($id<1)throw new InvalidArgumentException('Identifiant invalide.'); return $id;
}

function deleteGuard(bool $inUse,string $message): void
{
    if($inUse)throw new RuntimeException($message);
}
