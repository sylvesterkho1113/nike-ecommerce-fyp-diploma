<?php
include 'dataconnection.php';

if(isset($_POST['totalPrice'])){
    $totalPrice = $_POST['totalPrice'];
    // Later will bring the customerID session、
    session_start();
    $customerID = $_SESSION['Customer_ID'];

    // Update the total price in the database
    $stmt = $conn->prepare("UPDATE cart SET Total_Price = ? WHERE Customer_ID = ?");
    $stmt->bind_param("ii", $totalPrice, $customerID);

    if($stmt->execute()){
        echo "Total price updated successfully";
    } else {
        echo "Error: ".$stmt->error;
    }

    $stmt->close();
}

$conn->close();
