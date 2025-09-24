<?php
include 'dataconnection.php';
include 'header.php';

if (isset($_SESSION['Customer_ID'])){
    $Customer_ID = $_SESSION['Customer_ID'];

    $query = "SELECT *
        FROM cart
        JOIN cart_item ON cart.Cart_ID = cart_item.Cart_ID
        JOIN product ON cart_item.Product_ID = product.Product_ID
        JOIN customer ON cart.Customer_ID = customer.Customer_ID
        WHERE cart.Customer_ID = $Customer_ID
        AND cart_item.CI_Status = 0
        ORDER BY product.product_ID ASC";

$result = $conn->query($query);

$product_array = array();

//Check for errors in the query
if(!$result){
    $error_message = "Error in fetching product: " .$conn->error;
}
else{
    //fetch the product into an array
    while($row = $result->fetch_assoc()) {
        $product_array[] = $row;
    }
}

//close the database connection
$conn -> close();
}
else{
    ?>
    <script>
        alert("Please login first!!!!");
        window.location.href = "login.php";
    </script>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Your Cart</title>
    <!-- link jquery script -->
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous">
    </script>

    <!-- Link CSS and javaScript cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- internal css -->
    <style>
        #btnEmpty{
            background-color: #0D6EFD;
            color: aliceblue;
            font-size: 18px;
            border-radius: 8px;
            padding: 10px 15px;
            position: absolute;
            right: 12%;
        }

        #btncheckout{
            background-color: #0D6EFD;
            color: aliceblue;
            font-size: 18px;
            border-radius: 8px;
            padding: 10px 15px;
            position: absolute;
            right: 3%;
        }

        #btnEmpty:hover{
            background-color: #1f33c9;
        }

        #btncheckout:hover{
            background-color: #87B106;
        }

        h1{
            position: relative;
        }

        h1::after{
            content: '';/*  Required for ::after to work */
            display: block;
            width: 7%;
            height: 3px;
            background-color: #0F0F0F;
            margin: 10px auto;
            opacity: 0;
            animation: fadeIn 2s ease forwards;
        }

        @keyframes fadeIn {
            from{
                opacity: 0;
            }
            to{
                opacity: 1;
            }
        }

        span.dec.qtybtn,
        span.inc.qtybtn{
            align-items: center;
            text-align: center;
            width: 50px;
            height: 40px;
            border: 1px solid #A3A3A3;
            padding: 0 15px;
            float: left;
        }

        span.dec.qtybtn{
            border-right: none;
            margin-left: 16%;
        }

        span.inc.qtybtn{
            border-left: none;
        }

        .disabled{
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div id="shopping-cart" style="margin-bottom: 50px;">
        <h1 style="text-align: center; padding-top: 30px; padding-bottom: 30px">Shopping Cart</h1>
        
        
        <table style="width: 95%; margin: 50px auto;">
            <tbody style="border-collapse: collapse; width: 100%; border: 1px solid black;">
                <tr style="border-collapse: collapse; width: 100%; border: 1px solid black; background-color: #87B106; font-size: 25px; color: black">
                    <th style="border: 1px solid black; text-align: center;"><strong>Product Image</strong></th>
                    <th style="border: 1px solid black; text-align: center; width: 20%"><strong>Product Name</strong></th>
                    <th style="border: 1px solid black; text-align: center; width: 10%;"><strong>Unit Price</strong></th>
                    <th style="border: 1px solid black; text-align: center; width: 10%;"><strong>Quantity</strong></th>
                    <th style="border: 1px solid black; text-align: center; width: 10%;"><strong>Total Price</strong></th>
                    <th style="border: 1px solid black; text-align: center; width: 5%;"><strong>Remove</strong></th>
                </tr>

                <?php
                foreach($product_array as $item) {
                ?>
                    <div class="data">
                        <tr style="border-collapse: collapse; width: 100%; border: 1px solid black; text-align: center;">
                            <td style="border: 1px solid black; width: 25%;"><img src="<?php echo "admin/img/product/". $item["Product_Image"]; ?>" class="cart-list-image" alt="" style="height: 220px; width: 250px; margin-top: 15px; margin-bottom: 15px;"></td> <!-- Corrected key name -->
                            <td style="border: 1px solid black; font-size: 24px;"><?php echo $item["Product_Name"]; ?></td>
                            <td style="border: 1px solid black; font-size: 24px;" class="price"><?php echo "RM " . $item["Product_Price"]; ?></td>
                            <td class="pro-quantity" style="text-align: center; font-size: 24px; vertical-align: middle; align-items: center;">
                                <div class="pro-qty" style="display: inline-flex">
                                    <input type="number" value="<?php echo $item["Quantity"]; ?>" style="width: 33px; height: 40px; text-align: center; background-color: #F0F0F0; border-left: none; border-right: none; float: left; border-top: 1px solid #A3A3A3; border-bottom: 1px solid #A3A3A3;" data-product-id=<?php echo $item["Product_ID"]; ?> data-product-quantity-available="<?php echo $item["Product_quantity_available"]; ?>" class="quantity-input" required min="1" readonly>
                                </div>
                            </td>
                            <td style="border: 1px solid black; text-align: center; font-size: 24px;" id="item-total-price"></td>
                            <td style="border: 1px solid black; text-align: center; font-size: 24px;"><i class="fa-solid fa-trash" style="color: #a7a4a8;" id="btnRemoveAction" data-product-id="<?php echo $item["Product_ID"]; ?>"></i></td>
                        </tr>
                    </div>
                            
                    <?php
                }
                ?>

                <div id="summary">
                    <tr style="font-size: 24px; height: 20%;">
                        <td colspan="3" style="border-right: 1px solid black; text-align: right; padding-right: 10px;"><strong>Total : </strong></td>
                        <td style="border: 1px solid black; text-align: center;" id="total-quantity"></td>
                        <td style="border: 1px solid black; text-align: center;" id="total-price"></td>
                    </tr>
                </div>
            </tbody>
        </table>

        <div class="btn" style="display: inline-flex">
            <a href="checkout.php" id="btncheckout">Checkout</a>
            <a href="product.php" id="btnEmpty">Continue Shopping</a>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            calculateTotals();

            $("span").click(function() {
                var productId = $(this).closest("tr").find(".quantity-input").data("product-id");
                var newQuantity = parseInt($(this).closest("tr").find(".quantity-input").val());
                var quantityInput = $(this).closest("tr").find(".quantity-input");
                var currentRow = $(this).closest("tr");
                var decreaseSpan = $(this).closest("tr").find(".dec");
                var increaseSpan = $(this).closest("tr").find(".inc");

                $.ajax({
                    url: "update_cart.php",
                    type: "POST",
                    data: {
                        productId: productId,
                        newQuantity: newQuantity
                    },
                    success: function(response) {
                        var responseObject = JSON.parse(response);

                        if (!responseObject.success) {
                            alert(responseObject.message);
                            return;
                        }

                        var productQuantityAvailable = responseObject.productQuantityAvailable;
                        quantityInput.data("product-quantity-available", productQuantityAvailable);

                        if (newQuantity <= 1) {
                            decreaseSpan.addClass("disabled");
                            alert("Note: The minimum for one product in your cart is one.");
                        } else {
                            decreaseSpan.removeClass("disabled");
                        }

                        if (newQuantity >= 9 || productQuantityAvailable <= 0) {
                            increaseSpan.addClass("disabled");
                            if (newQuantity === 9) {
                                alert("Note: The maximum one product in your cart is nine.");
                            } else if (productQuantityAvailable === 0) {
                                alert("Note: We are sorry, the stock is empty!! Cannot be add anymore.");
                            }
                        } else {
                            increaseSpan.removeClass("disabled");
                        }

                        calculateTotals();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("An error occurred while updating the cart. Please try again.");
                    }
                });
            });

            //hide the row while user remove
            $(".fa-trash#btnRemoveAction").click(function(){
                // Get the productId
                var productId = $(this).closest("tr").find(".quantity-input").data("product-id");
                //store a reference to the current row
                var currentRow = $(this).closest("tr");

                //send AJAX request to hide_cart.php
                $.ajax({
                    url: "hide_cart.php",
                    type: "POST",
                    data: {
                        productId: productId
                   },
                   success: function(response){
                        currentRow.hide();
                        calculateTotals();
                        location.reload(true);
                   },
                   error: function(xhr, status, error){
                    console.error(xhr.responseText);
                   }
                });
            });

            $(".quantity-input").each(function() {
                var quantityInput = $(this);
                var productQuantityAvailable = parseInt(quantityInput.data("product-quantity-available"));
                var currentQuantity = parseInt(quantityInput.val());
                var decreaseSpan = quantityInput.closest("tr").find(".dec");
                var increaseSpan = quantityInput.closest("tr").find(".inc");

                if (currentQuantity <= 1) {
                    decreaseSpan.addClass("disabled");
                }

                if (currentQuantity >= 9 || productQuantityAvailable <= 0) {
                    increaseSpan.addClass("disabled");
                }
            });
        });

        function calculateTotals() {
            var totalQuantity = 0;
            var totalPrice = 0;

            $(".quantity-input").each(function() {
                totalQuantity += parseInt($(this).val());
            });

            $(".price").each(function(index) {
                var quantity = parseInt($(this).closest("tr").find(".quantity-input").val());
                var price = parseFloat($(this).text().replace("RM ", ""));
                totalPrice += price * quantity;
                $(this).closest("tr").find("#item-total-price").text("RM " + (price * quantity).toFixed(2));
            });

            $("#total-quantity").text(totalQuantity);
            $("#total-price").text("RM " + totalPrice.toFixed(2));

            $.ajax({
                url: "update_total_price.php",
                type: "POST",
                data: {
                    totalPrice: totalPrice
                },
                success: function(response) {
                    console.log("Total price updated successfully: " + response);
                },
                error: function(xhr, status, error) {
                    console.error("Error updating total price: " + xhr.responseText);
                }
            });
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
