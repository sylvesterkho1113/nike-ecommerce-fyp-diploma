<?php 
require 'dataconnection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
                        <div><h2>Order History</h2></div>
                    </div>
                    <hr style="color:white">

<!-- Display the search result -->

                    <!--Main content-->

                    <!-- Order History -->
                    <div class="table-responsive">
                        <div class="scrollable-table">
                            <table class="table text-start align-middle table-bordered table-hover mb-0" id="orderHistoryTable">
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
                                <tbody id="orderHistoryBody">
                                    <?php
                                    $limit = 10; // Number of rows to fetch per page
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $start = ($page - 1) * $limit;

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
                                                b.Invoice_Status = 'Delivered'
                                            GROUP BY b.Invoice_ID
                                            LIMIT $start, $limit";

                                    $result = mysqli_query($conn, $query);

                                    $num = $start;
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

                    <br>

                    <?php
                    // Check if there are more rows to display
                    $query = "SELECT COUNT(*) AS total FROM bill_master WHERE Invoice_Status = 'Delivered'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    $total_pages = ceil($row["total"] / $limit);
                    ?>

                    <!-- Load More Button -->
                    <?php if ($page < $total_pages): ?>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="loadMoreBtn">Load More</button>
                        </div>
                    <?php endif; ?>

                    <!-- Hidden input field to keep track of current page -->
                    <input type="hidden" id="currentPage" value="<?php echo $page; ?>">

                    <button type="button" class="btn btn-primary" onclick="history.back()">Back</button>
                </div>
            </div>
            <!-- Order List End -->
        </div>
        <!-- Content End -->
    </div>

    <!-- Main Script -->
    <?php require "js/Main_js.php"; ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loadMoreBtn').on('click', function() {
                var currentPage = parseInt($('#currentPage').val());
                var nextPage = currentPage + 1;
                window.location.href = "order_history.php?page=" + nextPage;
            });
        });
    </script>
</body>
</html>
