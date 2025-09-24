<?php

include 'dataconnection.php';
include 'header.php';

$Customer_ID = $_SESSION['Customer_ID'];

$query = "SELECT product.*, customer.Billing_Address_Line1, customer.Billing_Address_Line2, customer.Billing_Address_Line3, customer.Billing_Address_Line4, customer.Customer_Delivery_Address_1, customer.Customer_Delivery_Address_2, customer.Customer_Delivery_Address_3, cart_item.Quantity, cart.Total_Price, cart.Cart_ID, cart.Customer_ID
          FROM cart
          JOIN cart_item ON cart.Cart_ID = cart_item.Cart_ID
          JOIN product ON cart_item.Product_ID = product.Product_ID
          JOIN customer ON cart.Customer_ID = customer.Customer_ID
          WHERE cart.Customer_ID = $Customer_ID
          AND cart_item.CI_Status = 0
          ORDER BY product.Product_ID ASC";

$result = $conn->query($query);

$product_array = array();
$addresses = array();
$total_tax = 0.0;
$total_price = 0.0;
$fp = 0.0;

// Check for errors in the query
if (!$result) {
    die("Error in fetching product: " . $conn->error);
} else {
    // Fetch the product and address into arrays
    while ($row = $result->fetch_assoc()) {
        $product_array[] = $row;

        //calculate tox for each product and add to total tax
        $product_price = $row['Product_Price'];
        $qty = $row['Quantity'];
        $product_tax = $product_price * $qty * 0.06;
        $pprice = $product_price * $qty;
        $total_tax += $product_tax;
        $fp += $pprice;
        $final_price = $fp + $total_tax;

        // Assuming the same customer has the same delivery addresses across products
        if (empty($addresses)) {
            $billing_address = trim($row['Billing_Address_Line1'] . ' ' . $row['Billing_Address_Line2'] . ' ' . $row['Billing_Address_Line3'] . ' ' . $row['Billing_Address_Line4']);
            $addresses = [
                'Billing_Address' => $billing_address,
                'Customer_Delivery_Address_1' => $row['Customer_Delivery_Address_1'],
                'Customer_Delivery_Address_2' => $row['Customer_Delivery_Address_2'],
                'Customer_Delivery_Address_3' => $row['Customer_Delivery_Address_3']
            ];
        }
    }
}

// Close the initial query result
$result->close();

// Check if all address fields are null
if (empty($addresses['Billing_Address'])) {
    echo "<script>
            alert('Please insert your address first, Thank You.');
            window.location.href = 'my-account.php';
        </script>";
    exit; // Stop further execution to ensure the header redirect works
}

// Check if the form was submitted
$paymentSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected address value
    $selectedAddress = htmlspecialchars($_POST['address']); // Assuming 'address' is the name of your radio button group

    // Prepare and execute SQL statement to insert data into the bill_master table
    $stmt = $conn->prepare("INSERT INTO bill_master (Customer_ID, Total_Amount, Delivery_Address, Invoice_Status) VALUES (?, ?, ?, 'Pending')");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $customerId = $product_array[0]['Customer_ID']; // Assuming you have the Customer_ID available in the product_array
    $totalAmount = $product_array[0]['Total_Price']; // Assuming you have the Total_Price available in the product_array
    $stmt->bind_param("ids", $customerId, $totalAmount, $selectedAddress);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Retrieve the Invoice_ID generated from the bill_master insertion
        $invoiceId = $stmt->insert_id;
        $stmt->close();

        // Prepare and execute SQL statement to insert data into the bill_master_transaction table
        $stmt = $conn->prepare("INSERT INTO bill_master_transaction (Invoice_ID, Product_ID, Quantity) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("iii", $invoiceId, $productId, $quantity);

        $stmt1 = $conn->prepare("UPDATE product SET sale_quantity = sale_quantity + ? WHERE Product_ID = ?");
        if(!$stmt1){
            die("Prepare failed: " . $conn->error);
        }
        $stmt1->bind_param('ii', $quantity, $productId);

        // Insert data into bill_master_transaction for each product in the cart
        foreach ($product_array as $item) {
            $productId = $item['Product_ID'];
            $quantity = $item['Quantity'];
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            if (!$stmt1->execute()){
                die("Execute failed: " . $stmt1->error);
            }
        }

        $stmt->close();
        $stmt1->close();

        // Delete corresponding data from the cart and cart_item tables
        $cartId = $product_array[0]['Cart_ID']; // Assuming you have the Cart_ID available in the product_array
        if (!$conn->query("DELETE FROM cart WHERE Cart_ID = $cartId")) {
            die("Delete cart failed: " . $conn->error);
        }
        if (!$conn->query("DELETE FROM cart_item WHERE Cart_ID = $cartId")) {
            die("Delete cart items failed: " . $conn->error);
        }

        $paymentSuccess = true;
    } else {
        die("Error inserting data into bill_master: " . $stmt->error);
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Payment</title>
    <link rel="stylesheet" href="checkout.css">
    <style>
        form {
            width: 95%;
            margin: 25px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 800px;
        }
        h5 {
            padding-bottom: 15px;
        }
        .address-container {
            margin-bottom: 12px;
            width: 100%;
        }
        .address-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            width: 45%;
        }
        .address-item input[type="radio"] {
            margin-right: 10px;
        }
        .address-item label {
            flex: 1;
            margin-right: 10px;
        }
        .address-item button {
            padding: 5px 10px;
            border: 1px solid grey;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            font-weight: bold;
        }
        .submit {
            text-align: center;
        }
        .edit-address-btn {
            display: block;
            width: 100%;
            padding: 10px;
            border: 1px solid grey !important;
            border-radius: 8px !important;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin: 0 auto;
            border-top-right-radius: 0px !important;
            border-top-left-radius: 0px !important;
        }
        .edit-address-btn:hover {
            background-color: #0056b3;
            font-weight: bold;
            color: #fff;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .content-wrapper {
            display: flex;
            justify-content: space-between;
        }
       
        .card-details {
            width: 85%;
        }

        .detail{
            width: 100%;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 18px !important;
        }
        .item-name {
            flex: 2;
            font-size: 16px;
        }
        .item-price,
        .item-quantity {
            flex: 1;
            text-align: right;
            font-size: 14px;
            color: #555;
        }
        .order-total,
        .detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            font-size: 18px;
            border-top: 2px solid #ccc;
            margin-top: 10px;
            width: 95%;
            margin-left: 20px;
        }
        .total-label {
            flex: 2;
        }
        .total-price {
            flex: 1;
            text-align: right;
            color: #000;
        }
        @keyframes paymentSuccess {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .payment-success {
            animation: paymentSuccess 0.5s ease;
            background-color: #4CAF50;
            color: white;
        }

        .order{
            max-width: 75%;
            margin-left: 40%;
            margin-top: -40%;
        }

        .submit{
            align-items: center;
            margin-bottom: 0px;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <h5>Delivery Address</h5>

        <div class="address-container">
            <label for="address">Select Address:</label><br>
            <?php foreach ($addresses as $key => $address): ?>
                <?php if (!empty($address)): ?>
                    <div class="address-item">
                        <input type="radio" id="<?= $key ?>" name="address" value="<?= htmlspecialchars($address) ?>" <?php if($key === 'Billing_Address') echo 'checked'; ?> required>
                        <label for="<?= $key ?>"><?= htmlspecialchars($address) ?></label>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <hr>

        <div class="card-details">
            <div class="card">
                <a href="https://www.shift4shop.com/images/credit-card-logos/cc-lg-4.png">
                    <img src="https://www.shift4shop.com/images/credit-card-logos/cc-lg-4.png" alt="Credit Card Logos" title="Credit Card Logos" width="250" height="auto">
                </a>

                <hr style="border: 1px solid #ccc; margin: 0 15px;">

                <div class="card-detail">
                    <p>Card Number</p>
                    <div class="c-number" id="c-number">
                        <input id="number" class="cc-number" name="cc-number" placeholder="Card number" maxlength="19" required>
                        <i class="fa-solid fa-credit-card" style="margin: 0;"></i>
                    </div>

                    <div class="c-detail">
                        <div>
                            <p>Expiry Date</p>
                            <input id="e-date" class="cc-exp" name="cc-exp" placeholder="MM/YY" required maxlength="5">
                        </div>

                        <div>
                            <p>CVV</p>
                            <div class="cvv-box" id="cvv-box">
                                <input id="cvv" class="cc-cvv" name="cc-cvv" placeholder="cvv" required maxlength="3">
                                <i class="fa-solid fa-circle-question" title="3 digit on the back of the card" style="cursor: pointer"></i>
                            </div>
                        </div>
                    </div>

                    <div class="email">
                        <p>Email</p>
                        <input type="email" name="email" placeholder="example@email.com" id="email" required>
                    </div>
                </div>
                <div class="submit">
                    <button type="submit" name="submit" class="edit-address-btn">Payment</button>
                </div>
            </div>

            <div class="order">
                <div class="details">
                    <h3>Order Details</h3>
                    <?php if (!empty($product_array)): ?>
                        <?php foreach ($product_array as $product): ?>
                            <div class="order-item">
                                <span class="item-name" style="font-size: 18px;"><?= htmlspecialchars($product['Product_Name']) ?></span>
                                <span class="item-quantity" style="font-size: 18px;"><?= htmlspecialchars($product['Quantity']) ?> x <?= number_format(htmlspecialchars($product['Product_Price']),2) ?></span>
                                <span class="item-price" style="font-size: 18px;"><?= number_format(htmlspecialchars($product['Quantity']) * htmlspecialchars($product['Product_Price']), 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="order-item">
                            <span class="total-label">Tax (6%):</span>
                            <span class="tax-amount"><?= htmlspecialchars(number_format($total_tax, 2)) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="order-total">
                    <span class="total-label">Total Payment:</span>
                    <span class="total-price"><?= htmlspecialchars(number_format($final_price, 2)) ?></span>
                </div>
            </div>
        </div>
    </form>

    <?php if ($paymentSuccess): ?>
        <script>
            alert("Payment successful! Your product have been processing");
            window.location.href = "home.php";
        </script>
    <?php endif; ?>

    <script>
        // Script to manage modal display
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script src="card.js"></script>
</body>
</html>
