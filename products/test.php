<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST|GET|PUT|DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//init product instance
$product = new Product($db);

//GET, POST, PUT, DELETE
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch($requestMethod){
    case "GET":
        //read products
        $products = $product->read();

        // set response code - 200 OK
        http_response_code(200);

        echo json_encode($products);
        break;
    case "POST":
        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        //read products
        $response = $product->create($data);

        // set response code - 200 OK
        http_response_code(200);

        echo json_encode($response);

        break;
    case "PUT":
        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        //read products
        $response = $product->update($data);

        // set response code - 200 OK
        http_response_code(200);

        echo json_encode($response);

        break;
    case "DELETE":
        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        //read products
        $response = $product->delete($data);

        // set response code - 200 OK
        http_response_code(200);

        echo json_encode($response);

        break;
}

?>