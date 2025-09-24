<?php
include 'dataconnection.php';

if (isset($_POST['productId'], $_POST['newQuantity'])) {
    $productId = $_POST['productId'];
    $newQuantity = $_POST['newQuantity'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Get the current quantity in the cart
        $selectCartQuery = "SELECT Quantity FROM cart_item WHERE Product_ID = ?";
        $stmt = $conn->prepare($selectCartQuery);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($currentQuantity);
        $stmt->fetch();
        $stmt->close();

        // Calculate the difference in quantity
        $quantityDifference = $newQuantity - $currentQuantity;

        // Get the current available quantity of the product
        $selectProductQuery = "SELECT product_quantity_available FROM product WHERE Product_ID = ?";
        $stmt = $conn->prepare($selectProductQuery);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($productQuantityAvailable);
        $stmt->fetch();
        $stmt->close();

        // Calculate new product quantity available
        $newProductQuantityAvailable = $productQuantityAvailable - $quantityDifference;

        // Check if the new product quantity available is negative
        if ($newProductQuantityAvailable < 0) {
            // Rollback transaction
            $conn->rollback();
            echo json_encode([
                "success" => false,
                "message" => "Insufficient stock. The product is out of stock.",
                "productQuantityAvailable" => $productQuantityAvailable
            ]);
        } else {
            // Update the quantity in the cart
            $updateCartQuery = "UPDATE cart_item SET Quantity = ?, CI_Status = 0 WHERE Product_ID = ?";
            $stmt = $conn->prepare($updateCartQuery);
            $stmt->bind_param("ii", $newQuantity, $productId);
            $stmt->execute();
            $stmt->close();

            // Update the product quantity available
            $updateProductQuery = "UPDATE product SET product_quantity_available = ? WHERE Product_ID = ?";
            $stmt = $conn->prepare($updateProductQuery);
            $stmt->bind_param("ii", $newProductQuantityAvailable, $productId);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();

            echo json_encode([
                "success" => true,
                "message" => ($newProductQuantityAvailable === 0) ? "The product is now out of stock." : "",
                "productQuantityAvailable" => $newProductQuantityAvailable
            ]);
        }
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }

    // Close the database connection
    $conn->close();
}
