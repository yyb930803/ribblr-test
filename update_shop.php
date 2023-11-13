<?php

$host = "your_database_host";
$username = "your_database_username";
$password = "your_database_password";
$database = "your_database_name";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $newShopName = filter_input(INPUT_POST, "shop_name", FILTER_SANITIZE_STRING);

    if ($id === false || $newShopName === null || $newShopName === false) {
        $response = array("status" => "error", "message" => "Invalid input data.");
    } else {
        $sql = "UPDATE shops SET shop_name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("si", $newShopName, $id);
        
        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Shop name updated successfully.");
        } else {
            $response = array("status" => "error", "message" => "Error updating shop name: " . $stmt->error);
        }

        $stmt->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

$conn->close();

?>