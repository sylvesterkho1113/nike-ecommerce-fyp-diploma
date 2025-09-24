<?php 
require 'dataconnection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['editpage'])) {
    header("Location: edit_order.php");
    exit();
}

if (isset($_POST["search"])) {
    $Invoice_ID = trim($_POST["Invoice_ID"] ?? '');
    
    if ($Invoice_ID !== '') {   
        $query = "SELECT 
                    b.Invoice_ID, c.Customer_Username, b.Invoice_Date, 
                    b.Total_Amount, b.Delivery_Address, b.Invoice_Status
                FROM `bill_master` b
                JOIN bill_master_transaction r ON b.Invoice_ID = r.Invoice_ID 
                JOIN product p ON r.Product_ID = p.Product_ID 
                JOIN customer c ON b.Customer_ID = c.Customer_ID
                WHERE b.Invoice_ID = ? AND b.Invoice_Status != 'Delivered'
                GROUP BY b.Invoice_ID";

        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $Invoice_ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                if ($result->num_rows > 0) {
                    $row = mysqli_fetch_assoc($result);
                    // Assign variables for displaying the search result
                    $ID = $row['Invoice_ID'];
                    $Customer_Username = $row['Customer_Username'];
                    $Order_Date = $row['Invoice_Date'];
                    $Total_Price = $row['Total_Amount'];
                    $Shipping_Address = $row['Delivery_Address'];
                    $Order_Status = $row['Invoice_Status'];
                } else {
                    echo "<script>alert('No orders found for the provided Invoice ID');</script>";
                }
            }
        } else {
            die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }
}
}

if (isset($_POST["edit"])) {
    foreach ($_POST['Status'] as $ID => $Status) {
        // Check if both ID and Status are not empty
        $ID = trim($ID);
        $Status = trim($Status);
        
        if ($ID !== '' && $Status !== '') {
            // Update the order status in the database
            $query = "UPDATE `bill_master` SET Invoice_status = ? WHERE Invoice_ID = ?";
            
            if ($stmt = mysqli_prepare($conn, $query)) {
                mysqli_stmt_bind_param($stmt, "ss", $Status, $ID);
                mysqli_stmt_execute($stmt);
                
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo "<script>alert('Order Updated Successfully');</script>";
                } else {
                    echo "<script>alert('Order Update Failed');</script>";
                }
            } else {
                die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
            }
        } else {
            echo "<script>alert('Please select a status to update.');</script>";
        }
    }
    // Redirect after processing the form submission
    header("Location: order.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.html'; ?>
    <style>
        .flex-container {
            display: flex;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .flex-container > div {
            margin: 0 10px;
        }

        .container-fluid {
            padding: 10px;
        }

        .table-responsive {
            overflow-x: auto;
            scrollbar-color: white gray;
            scrollbar-width: thin;
        }

        .scrollable-table {
            max-height: 370px;
            overflow-y: auto;
            scrollbar-color: white gray;
            scrollbar-width: thin;
        }

        thead {
            background-color: #343a40;
        }

        td {
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <?php include 'sidebar.php'; ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <?php include 'navbar.php'; ?>
            <!-- Navbar End -->

            <!-- Order List Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded p-4">
                    <div class="flex-container">
                        <div><h2>Order</h2></div>
                        <div>
                            <form action="order.php" method="post">
                                <input type="text" name="Invoice_ID" placeholder="Change status, enter ID">
                                <button type="submit" class="btn btn-primary" name="search">Search</button>
                            </form>
                        </div>
                        <div>
                            <form action="order.php" method="post">
                                <button name="editpage" class="btn btn-primary">Multi Edit</button>
                            </form>
                        </div>
                    </div>
                    <hr style="color:white">
                    <?php if (isset($ID)){ ?>
                       
<!-- Display the search result -->
                    <div class="row">
                        <div class="col">
                            <h4>Search Result</h4>
                            <form action="order.php" method="post">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Order Date</th>
                                        <th>Total Price</th>
                                        <th>Shipping Address</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $ID; ?></td>
                                        <td><?php echo $Customer_Username; ?></td>
                                        <td><?php echo $Order_Date; ?></td>
                                        <td><?php echo $Total_Price; ?></td>
                                        <td><?php echo $Shipping_Address; ?></td>
                                        <td>
                                            <!-- Dropdown for selecting order status -->
                                            <div class='form-floating'>
                                                <select class='form-select form-select-sm' name='Status[<?php echo $ID; ?>]'>
                                                    <option value='Pending' <?php echo ($Order_Status == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                                    <option value='Ordered' <?php echo ($Order_Status == 'Ordered' ? 'selected' : ''); ?>>Ordered</option>
                                                    <option value='Preparing' <?php echo ($Order_Status == 'Preparing' ? 'selected' : ''); ?>>Preparing</option>
                                                    <option value='In transit' <?php echo ($Order_Status == 'In transit' ? 'selected' : ''); ?>>In transit</option>
                                                    <option value='Delivered' <?php echo ($Order_Status == 'Delivered' ? 'selected' : ''); ?>>Delivered</option>
                                                </select>
                                                <label style='color:white' for='floatingPassword'>Status</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <input type="hidden" name="Invoice_ID" value="<?php echo $ID; ?>">
                                <button type="submit" class="btn btn-primary" name="edit">Update</button>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <?php } else { ?>



                    <!--Main content-->
                    <!-- Order List -->
                    <div class="table-responsive">
                        <div class="scrollable-table">
                            <h4>Order List</h4>
                            <table class="table text-start align-middle table-bordered table-hover mb-0" id="orderListTable">
                                <thead>
                                    <tr class="text-white">
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Order Date</th>
                                        <th>Total Price</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="orderListBody">
                                    <?php
                                    $query = "SELECT 
                                    b.Invoice_ID, c.Customer_Username, b.Invoice_Date, 
                                    b.Total_Amount, b.Delivery_Address, b.Invoice_Status
                                FROM 
                                    bill_master b
                                JOIN 
                                    bill_master_transaction r ON b.Invoice_ID = r.Invoice_ID 
                                JOIN 
                                    product p ON r.Product_ID = p.Product_ID 
                                JOIN 
                                    customer c ON b.Customer_ID = c.Customer_ID
                                WHERE 
                                    b.Invoice_Status != 'Delivered'
                                    GROUP BY b.Invoice_ID";

                                    $result = mysqli_query($conn, $query);
                                    $num = 0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $num++;
                                        echo "<tr>";
                                        echo "<td>$num</td>";
                                        echo "<td>{$row['Invoice_ID']}</td>";
                                        echo "<td>{$row['Customer_Username']}</td>";
                                        echo "<td>{$row['Invoice_Date']}</td>";
                                        echo "<td>{$row['Total_Amount']}</td>";
                                        echo "<td>{$row['Delivery_Address']}</td>";
                                        echo "<td>{$row['Invoice_Status']}</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <br>
                           
                            
                        </div>
                    </div>
                    <hr>
                    <?php } ?>

                    <button type="button" class="btn btn-primary" onclick="history.back()">Back</button>
                </div>
            </div>
            <!-- Order List End -->
        </div>
        <!-- Content End -->
    </div>

    <!-- Main Script -->
    <?php require "js/Main_js.php"; ?>
</body>
</html>
