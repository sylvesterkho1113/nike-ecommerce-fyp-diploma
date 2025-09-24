<?php require 'dataconnection.php' ;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head.html' ?>
</head>
<style>
        thead{
            background-color: #343a40;
        
        }td{
            color:white
        }
    </style>
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
        <?php include 'sidebar.php' ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <?php include 'navbar.php' ?>
            <!-- Navbar End -->

            <!-- recent sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="vh-100 bg-secondary rounded mx-0" style="padding:10px 10px 10px 10px; overflow-y:auto; scrollbar-color:white gray; scrollbar-width:thin;">
                <div style="display:flex;justify-content:space-between">
                    <h3>Recent Sales All</h3>
                    <form action="export_orders_to_excel.php?id=1"method="post">
                        <button type="submit" class="btn btn-success">Export to Excel</button>
                    </form>
                </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "
                                SELECT b.Invoice_ID, c.Customer_Username, i.Quantity, b.Total_Amount, b.Invoice_Status, b.Invoice_Date
                                FROM bill_master b
                                JOIN bill_master_transaction i ON b.Invoice_ID = i.Invoice_ID
                                JOIN product p ON i.Product_ID = p.Product_ID
                                JOIN customer c ON b.Customer_ID = c.Customer_ID
                                GROUP BY b.Invoice_ID
                                ORDER BY b.Invoice_Date DESC";

                                $result = mysqli_query($conn, $sql);

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        echo "<tr>";
                                        echo "<td>" . $row['Invoice_ID'] . "</td>";
                                        echo "<td>" . $row['Customer_Username'] . "</td>";
                                        echo "<td>" . $row['Total_Amount'] . "</td>";
                                        echo "<td>" . $row['Invoice_Status'] . "</td>";
                                        echo "<td>" . $row['Invoice_Date'] . "</td>";
                                        echo "<td><button type='button' onclick='salesreport({$row['Invoice_ID']})' class='btn btn-primary'>View</button></td>";
                                        echo "</form>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "Error executing the query: " . mysqli_error($conn);
                                }
                                ?>
                            </tbody>
                        </table>
                        <hr>
                        <button type="button" class="btn btn-primary" onclick="back()">Back</button>
                    </div>
                </div>
            </div>
            <!-- recent sales End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- Main Script -->
    <?php require "js/Main_js.php" ?>
</body>

</html>
