<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "OPTIONS") {
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  include_once '../../config/database.php';
  include_once '../objects/estado.php';
/**
 * IdEstado
 * Nombre
 * Descripcion
 * IdEstadoAnterior
 * IdEstadoSiguiente
 */

  $database = new Database();
  $db = $database->getConnection();
  $Estado = new Estados($db);
  $data = json_decode(file_get_contents("php://input"));
  
  if (empty($data->IdEstado)) {
    echo json_encode(
      array("message" => "EMPTY")
    );
  } else {
    $Estado->IdEstado = $data->IdEstado;
    $Estado->Nombre = $data->Nombre;
    $Estado->Descripcion = $data->Descripcion;
    $Estado->IdEstadoAnterior = $data->IdEstadoAnterior;
    $Estado->IdEstadoSiguiente = $data->IdEstadoSiguiente;

    if ($Estado->create()) {
      http_response_code(200);
      echo json_encode(
        array("message" => "Datos guardados exitosamente en Estado.")
      );
    } else {
      http_response_code(404);
      echo json_encode(
        array("message" => "No se guardaron correctamente los datos.")
      );
    }
  }
} else {
  http_response_code(404);
}