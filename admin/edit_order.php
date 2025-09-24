<?php 
    require 'dataconnection.php';

    if(isset($_POST["edit2"])) {
        // Loop through the posted statuses and update each order accordingly
        foreach($_POST["Status"] as $orderID => $status) {
            $update = mysqli_prepare($conn, "UPDATE bill_master SET Invoice_Status = ? WHERE Invoice_ID = ?");
            if (!$update) {
                die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($update, "ss", $status, $orderID);
            mysqli_stmt_execute($update);
            
            if(mysqli_stmt_affected_rows($update) > 0) {
                echo "<script>alert('Order $orderID Updated Successfully')</script>";
            } else {
                echo "<script>alert('Order $orderID Update Failed')</script>";
            }
        }

        header("Location: order.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.html' ?>
    <style>
        /* Custom CSS styles */
        .container-fluid {
            padding-top: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .form-floating {
            margin-bottom: 0;
        }
        .btn-edit {
            margin-left: 5px;
        }
        td {
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Spinner -->
        <div id="spinner" class="bg-dark position-fixed w-100 h-100 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php' ?>

        <!-- Content -->
        <div class="content">
            <!-- Navbar -->
            <?php include 'navbar.php' ?>

            <div class="container-fluid pt-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Edit Orders</h3>
                    </div>
                    <div class="card-body" style="background-color:black">
                        <!-- Table for displaying all orders -->
                        <div class="table-responsive" style="background-color:gray">
                            <form action="edit_order.php" method="post">
                                <table class="table table-bordered table-hover mb-0">
                                    <!-- Table header -->
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Total Price</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <!-- Table body -->
                                    <tbody>
                                        <?php
                                            // Fetch all orders
                                            $query = "SELECT 
                                                b.Invoice_ID,  c.Customer_Username, b.Invoice_Date, 
                                                r.Quantity, b.Total_Amount, b.Delivery_Address, b.Invoice_Status
                                            FROM `bill_master` b
                                            JOIN bill_master_transaction r ON b.Invoice_ID = r.Invoice_ID 
                                            JOIN customer c ON b.Customer_ID = c.Customer_ID
                                            WHERE b.Invoice_Status != 'Delivered'
                                            GROUP BY b.Invoice_ID";

                                            $result = mysqli_query($conn, $query);

                                            if ($result) {
                                                $num = 0;
                                                // Display each order as a table row
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $num++;
                                                    // Store the current order's status in the selected statuses array
                                                    $selectedStatus = isset($_POST["Status"][$row['Invoice_ID']]) ? $_POST["Status"][$row['Invoice_ID']] : $row['Invoice_Status'];
                                                    // Display each order as a table row
                                                    echo "<tr>";        
                                                    echo "<td>{$num}</td>";           
                                                    echo "<td>" . htmlspecialchars($row['Invoice_ID']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Customer_Username']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Invoice_Date']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Total_Amount']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['Delivery_Address']) . "</td>";
                                                    echo "<td>
                                                                <div class='form-floating'>
                                                                    <select class='form-select form-select-sm' name='Status[" . htmlspecialchars($row['Invoice_ID']) . "]'>
                                                                        <option value='Pending'" . ($selectedStatus == 'Pending' ? ' selected' : '') . ">Pending</option>
                                                                        <option value='Ordered'" . ($selectedStatus == 'Ordered' ? ' selected' : '') . ">Ordered</option>
                                                                        <option value='Preparing'" . ($selectedStatus == 'Preparing' ? ' selected' : '') . ">Preparing</option>
                                                                        <option value='In transit'" . ($selectedStatus == 'In transit' ? ' selected' : '') . ">In transit</option>
                                                                        <option value='Delivered'" . ($selectedStatus == 'Delivered' ? ' selected' : '') . ">Delivered</option>
                                                                    </select>
                                                                    <label style='color:white' for='floatingStatus'>Status</label>
                                                                </div>
                                                            </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "Error executing the query: " . mysqli_error($conn);
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                
                        </div>
                        <br>
                                <button type="submit" class="btn btn-primary" name="edit2">Update</button>
                            </form>
                        <hr>
                        
                                <button type="button" class="btn btn-primary" onclick="back()">Back</button>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!--Main Script-->
    <?php require "js/Main_js.php"?>
    <script>
        function back() {
            window.history.back();
        }

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
