<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
ini_set('display_errors', '1');
include_once '../config.php';
include_once '../db.php';
include_once 'players/players.php';

//$db = new DB($DB_HOST, $DB_USERNAME, $DB_PASS, $DATABASE_NAME);

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
header('Content-type: application/json');
// echo json_encode($request);
switch ($method) {
    case 'PUT':
        //do_something_with_put($request);
        break;
    case 'POST':
        $db = new PLAYERS($DB_HOST, $DB_USERNAME, $DB_PASS, $DATABASE_NAME);
        $entityBody = json_decode(file_get_contents('php://input'), true);
        $obj = $entityBody["params"];
        $do = $entityBody["do"];
        $result = new stdClass();
        if ($do === 'add') {
            $paramsArr = array("player_name" => $obj["player_name"], "player_dob" => $obj["player_dob"], "player_place" => $obj["player_place"], "player_pic" => $obj["player_pic"], "player_arm" => $obj["player_arm"], "player_type" => $obj["player_type"], "player_phone" => $obj["player_phone"], "player_address" => $obj["player_address"]);
            $results = $db->insertPlayer($PLAYER_TBL, $paramsArr, 'ssssiiss');
            $result->error = $results->error;
            if (!$results->error) {
                $result->insertedId = $results->result;
            } else {
                $result->message = $results->message;
            }
            echo json_encode($result);
        } else {
            if (!$entityBody["params"]["id"]) {
                $result->error = true;
                $result->message = 'Invalid player id';
                echo json_encode($result);
            } else {
                try {
                    $paramsArr = array("player_name" => $obj["player_name"], "player_dob" => $obj["player_dob"], "player_place" => $obj["player_place"], "player_pic" => $obj["player_pic"], "player_arm" => $obj["player_arm"], "player_type" => $obj["player_type"], "player_phone" => $obj["player_phone"], "player_address" => $obj["player_address"]);
                    $updateQuery = $db->updatePlayer($PLAYER_TBL, $paramsArr, 'ssssiiss', 'player_id = ' . $entityBody["params"]["id"]);
                    echo json_encode($updateQuery);
                } catch (Exception $e) {
                    $result->error = true;
                    $result->message = strip_tags($e->getMessage());
                    echo json_encode($result);
                }

            }

        }

        break;
    case 'GET':
        // echo json_encode($request[0]);
        if ($request[0] === "") {
            //fetch all recors
            $fetchALlUser = new PLAYERS($DB_HOST, $DB_USERNAME, $DB_PASS, $DATABASE_NAME);
            $results = $fetchALlUser->getAllPlayers($PLAYER_TBL);
            echo json_encode($results);
        } else {
            // fetch particular record
            $fetchALlUser = new PLAYERS($DB_HOST, $DB_USERNAME, $DB_PASS, $DATABASE_NAME);
            $results = $fetchALlUser->getPlayerByID($PLAYER_TBL, (int) $request[0]);
            echo json_encode($results);
        }

        break;
    case 'DELETE':
        $entityBody = json_decode(file_get_contents('php://input'), true);
        $deleteQry = new PLAYERS($DB_HOST, $DB_USERNAME, $DB_PASS, $DATABASE_NAME);
        $results = $deleteQry->deletePlayer($PLAYER_TBL, $entityBody["player_id"]);
        echo json_encode($results);
    default:
        // handle_error($request);
        break;
}

exit;
