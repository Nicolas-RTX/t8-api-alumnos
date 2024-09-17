<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once 'config/Database.php';
include_once 'models/Alumno.php';

$database = new Database();
$db = $database->getConnection();

$alumno = new Alumno($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $alumno->id = $_GET['id'];
            if ($alumno->getById()) {
                $alumno_arr = array(
                    "id" => $alumno->id,
                    "dni" => $alumno->dni,
                    "nombre" => $alumno->nombre,
                    "apellido" => $alumno->apellido,
                    "curso" => $alumno->curso,
                    "division" => $alumno->division
                );
                echo json_encode($alumno_arr);
            } else {
                echo json_encode(array("message" => "Alumno no encontrado."));
            }
        } else {
            $stmt = $alumno->read();
            $num = $stmt->rowCount();

            if($num > 0) {
                $alumnos_arr = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $alumno_item = array(
                        "id" => $id,
                        "dni" => $dni,
                        "nombre" => $nombre,
                        "apellido" => $apellido,
                        "curso" => $curso,
                        "division" => $division
                    );
                    array_push($alumnos_arr, $alumno_item);
                }
                echo json_encode($alumnos_arr);
            } else {
                echo json_encode(array("message" => "No se encontraron alumnos."));
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->dni) && !empty($data->nombre) && !empty($data->apellido) && !empty($data->curso) && !empty($data->division)) {
            $alumno->dni = $data->dni;
            $alumno->nombre = $data->nombre;
            $alumno->apellido = $data->apellido;
            $alumno->curso = $data->curso;
            $alumno->division = $data->division;

            if ($alumno->create()) {
                echo json_encode(array("message" => "Alumno creado."));
            } else {
                echo json_encode(array("message" => "No se pudo crear el alumno."));
            }
        } else {
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $alumno->id = $data->id;
            $alumno->dni = $data->dni;
            $alumno->nombre = $data->nombre;
            $alumno->apellido = $data->apellido;
            $alumno->curso = $data->curso;
            $alumno->division = $data->division;

            if ($alumno->update()) {
                echo json_encode(array("message" => "Alumno actualizado."));
            } else {
                echo json_encode(array("message" => "No se pudo actualizar el alumno."));
            }
        } else {
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $alumno->id = $data->id;

            if ($alumno->delete()) {
                echo json_encode(array("message" => "Alumno eliminado."));
            } else {
                echo json_encode(array("message" => "No se pudo eliminar el alumno."));
            }
        } else {
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;

    default:
        echo json_encode(array("message" => "Método no soportado."));
        break;
}

?>