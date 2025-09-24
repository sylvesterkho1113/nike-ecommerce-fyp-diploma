<?php
// Check if productId is provided in the POST request
if(isset($_POST['productId'])) {
    include 'dataconnection.php';

    // Sanitize the productId to prevent SQL injection
    $productId = intval($_POST['productId']);

    // Perform the query to get the current product quantity
    $getQuantityQuery = "SELECT Quantity FROM cart_item WHERE Product_ID = ?";
    $stmt1 = $conn->prepare($getQuantityQuery);

    // Check if the statement preparation was successful
    if($stmt1) {
        $stmt1->bind_param("i", $productId);

        if($stmt1->execute()) {
            // If the query is successful, fetch the result
            $stmt1->bind_result($currentQuantity);
            $stmt1->fetch();

            // Close the statement
            $stmt1->close();

            session_start();
            $Customer_ID = $_SESSION['Customer_ID'];
            
            // Perform the query to hide the product in the cart
            $hideProductQuery = "DELETE cart_item
                                FROM cart_item
                                JOIN cart ON cart_item.Cart_ID = cart.Cart_ID
                                WHERE cart_item.Product_ID = ?
                                AND cart.Customer_ID = ?";
            $stmt2 = $conn->prepare($hideProductQuery);

            // Check if the statement preparation was successful
            if($stmt2) {
                $stmt2->bind_param("ii", $productId, $Customer_ID);

                if($stmt2->execute()) {
                    // If the query is successful, send back a success response
                    echo "Product successfully delete";

                    // Increment the product quantity
                    $newQuantity = $currentQuantity;

                    // Update the product quantity in the database
                    $updateQuantityQuery = "UPDATE product SET Product_quantity_available = Product_quantity_available + ? WHERE Product_ID = ?";
                    $updateStmt = $conn->prepare($updateQuantityQuery);

                    if($updateStmt) {
                        $updateStmt->bind_param("ii", $newQuantity, $productId);

                        if($updateStmt->execute()) {
                            // If the query is successful, send back a success response
                            echo "Product quantity updated successfully";
                        }
                        else {
                            // If there's an error in the query execution, send back an error response
                            echo "Error updating product quantity";
                        }

                        // Close the statement
                        $updateStmt->close();
                    } else {
                        // If statement preparation failed, send back an error response
                        echo "Error preparing statement";
                    }
                } else {
                    echo "Error hiding product";
                }

                $stmt2->close();
            } else {
                // If statement preparation failed, send back an error response
                echo "Error preparing statement";
            }
        }
        else {
            // If there's an error in the query execution, send back an error response
            echo "Error fetching current quantity";
        }
    } else {
        // If statement preparation failed, send back an error response
        echo "Error preparing statement";
    }

    // Close the database connection
    $conn->close();
} else {
    // If productId is not provided, send back an error response
    echo "Product ID not provided";
}
