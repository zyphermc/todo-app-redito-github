<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    require("vendor/autoload.php");

    //Database config
    $db = new MysqliDb("localhost", "root", "root", "tasks");
    $table = "user_tasks";

    $payload = json_decode(file_get_contents("php://input"));
    $result = Array();
    $action = "";

    if (isset($_GET["action"])) {
        $action = $_GET["action"];
    }

    if ($action == "create" && $payload != null) { 
        $data = Array (
            "task_description" => $payload->task_description,
            "task_status" => $payload->task_status
        );

        $id = $db -> insert ($table, $data);

        if ($id){
            $result['id'] = "$id";
        }else{
            $result['err'] = $db->getLastError();
        }
    } 

    if ($action == "read") {
        $cols = Array("id", "task_description", "task_status");

        $result["tasks"] = $db->get($table, null, $cols);
    }

    if ($action == "update" && $payload != null) {
        $data = Array( 
            "id" => $payload->id,
            "task_description" => $payload->task_description,
            "task_status" => $payload->task_status
        );  

        $db->where ("id", $payload->id);

        if ($db->update ($table, $data)){
            echo $db->count . ' records were updated';
        } else{
            echo 'update failed: ' . $db->getLastError();
        }  
    }

    if ($action == "delete" && $payload != null) {
        $db -> where ("id", $payload->id);
        $db -> delete ($table);
    }

    echo json_encode($result);
?>