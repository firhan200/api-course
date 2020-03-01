<?php
class Product{
    // database connection and table name
    private $conn;
    private $table_name = "products";
 
    // object properties
    public $id;
    public $name;
    public $description;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    /* method to read all product */
    function read(){
        // read products will be here
        $query = "SELECT * FROM products ORDER BY id DESC";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();

        //check row
        $num = $stmt->rowCount();
        // check if more than 0 record found
        if($num>0){
            // products array
            $products_arr=array();
            $products_arr["products"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                $product_item = array(
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description)
                );
        
                array_push($products_arr["products"], $product_item);
            }

            // show products data in json format
            return $products_arr;
        }else{

        }
    }

    /* method to create product */
    function create($data){
        $response = array("message" => "");

        // make sure data is not empty
        if(
            !empty($data->name) &&
            !empty($data->description)
        ){
            // create the product
            $query = "INSERT INTO products(name,description) VALUES ('".$data->name."','".$data->description."')";
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            //execute
            if($stmt->execute()){
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                $response["message"] = "Product was created.";
            }
        
            // if unable to create the product, tell the user
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                $response["message"] = "Unable to create product.";
            }
        }
        
        // tell the user data is incomplete
        else{
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            $response["message"] = "Unable to create product. Data is incomplete.";
        }

        return $response;
    }

    /* method to update product */
    function update($data){
        $response = array("message" => "");

        // make sure data is not empty
        if(
            !empty($data->id) &&
            !empty($data->name) &&
            !empty($data->description)
        ){
            //check if product exist
            $queryCheck = "SELECT * FROM products WHERE id=".$data->id."";
            // prepare query statement
            $stmtCheck = $this->conn->prepare($queryCheck);
            // execute query
            $stmtCheck->execute();
            //check row
            $num = $stmtCheck->rowCount();
            if($num > 0){
                // update the product
                $query = "UPDATE products SET name='".$data->name."', description='".$data->description."' WHERE id=".$data->id."";
                // prepare query statement
                $stmt = $this->conn->prepare($query);
                //execute
                if($stmt->execute()){
                    // set response code - 201 created
                    http_response_code(201);
            
                    // tell the user
                    $response["message"] = "Product was updated.";
                }
            
                // if unable to create the product, tell the user
                else{
                    // set response code - 503 service unavailable
                    http_response_code(503);
            
                    // tell the user
                    $response["message"] = "Unable to update product.";
                }
            }else{
                // set response code - 400 bad request
                http_response_code(400);
            
                // tell the user
                $response["message"] = "Unable to update product. Product Not Found.";
            }
        }
        
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            $response["message"] = "Unable to update product. Data is incomplete.";
        }

        return $response;
    }

    /* method to delete product */
    function delete($data){
        $response = array("message" => "");

        // make sure data is not empty
        if(
            !empty($data->id)
        ){
            //check if product exist
            $queryCheck = "SELECT * FROM products WHERE id=".$data->id."";
            // prepare query statement
            $stmtCheck = $this->conn->prepare($queryCheck);
            // execute query
            $stmtCheck->execute();
            //check row
            $num = $stmtCheck->rowCount();
            if($num > 0){
                // update the product
                $query = "DELETE FROM products WHERE id=".$data->id."";
                // prepare query statement
                $stmt = $this->conn->prepare($query);
                //execute
                if($stmt->execute()){
                    // set response code - 201 created
                    http_response_code(201);
            
                    // tell the user
                    $response["message"] = "Product was deleted.";
                }
                // if unable to create the product, tell the user
                else{
                    // set response code - 503 service unavailable
                    http_response_code(503);
            
                    // tell the user
                    $response["message"] = "Unable to delete product.";
                }
            }else{
                // set response code - 400 bad request
                http_response_code(400);
            
                // tell the user
                $response["message"] = "Unable to delete product. Product Not Found.";
            }
        }
        
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            $response["message"] = "Unable to delete product. Data is incomplete.";
        }

        return $response;
    }
}
?>