<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->id)
){
    //check if product exist
    $queryCheck = "SELECT * FROM products WHERE id=".$data->id."";
    // prepare query statement
    $stmtCheck = $db->prepare($queryCheck);
    // execute query
    $stmtCheck->execute();
    //check row
    $num = $stmtCheck->rowCount();
    if($num > 0){
        // update the product
        $query = "DELETE FROM products WHERE id=".$data->id."";
        // prepare query statement
        $stmt = $db->prepare($query);
        //execute
        if($stmt->execute()){
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Product was deleted."));
        }
        // if unable to create the product, tell the user
        else{
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to delete product."));
        }
    }else{
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to delete product. Product Not Found."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete product. Data is incomplete."));
}
?>