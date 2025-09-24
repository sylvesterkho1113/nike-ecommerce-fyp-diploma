<?php 
    require 'dataconnection.php';

    // Assuming $conn is your database connection object

    if(isset($_POST['psearch'])) {
        $pname = $_POST['name'];
        $sql = "SELECT *, p.Product_ID AS Product_ID FROM product p 
        JOIN record_time r ON p.Product_ID = r.Product_ID 
        JOIN product_category pc ON p.PC_ID = pc.PC_ID
        JOIN size s ON p.Size_ID = s.Size_ID
        LEFT JOIN product_delete pd ON p.Product_ID = pd.Product_ID
        WHERE pd.Product_ID IS NULL
        AND p.Product_Name LIKE '$pname'
        OR pc.Category LIKE '$pname'
        ORDER BY p.Product_ID DESC";

        $psearch_list = mysqli_query($conn, $sql);
        if(!$psearch_list) {
            echo "Error executing the query: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.html'; ?>
    <style>
        thead {
            background-color: #343a40;
        }
        td {
            color: white;
        }
    </style>
    <script>
        function back() {
            window.location.href = "your_previous_page.php";
        }

        function editProduct(productID) {
            // Redirect to the edit product page with the product ID
            window.location.href = "edit_product.php?id=" + productID;
        }
    </script>
</head>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 d-flex align-items-center justify-content-center">
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

            <!-- Main Content Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-white">Product List</h3>
                        <form action="export_orders_to_excel.php?id=3" method="post">
                            <button type="submit" class="btn btn-success">Export to Excel</button>
                        </form>
                    </div>
                    <div class="mb-4">
                        <form action="product_list-page.php" method="post" class="d-flex">
                            <input type="text" name="name" placeholder="Search Product by name or category (Running, lifestyle,Basketball,Football)" class="form-control me-2">
                            <button type="submit" name="psearch" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <hr class="text-white">
                    <?php if(isset($_POST['psearch'])): ?>
                        <?php if(mysqli_num_rows($psearch_list) > 0): ?>
                            <div class="table-responsive">
                                <h5>Search Result</h5>
                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                    <thead>
                                        <tr class="text-white">
                                            <th scope="col">#</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Cost</th>
                                            <th scope="col">Size</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $num = 0;
                                            while ($row = mysqli_fetch_assoc($psearch_list)) {
                                                $num++;
                                                $image = $row['Product_Image'];
                                                echo "<tr>";
                                                echo "<td>{$num}</td>";
                                                echo "<td><img src='img/{$image}' alt='image' width='100' height='100'></td>";
                                                echo "<td>{$row['Product_ID']}</td>";
                                                echo "<td>{$row['Product_Name']}</td>";
                                                echo "<td>{$row['Category']}</td>";
                                                echo "<td>{$row['Product_Description']}</td>";
                                                echo "<td>{$row['Product_quantity_available']}</td>";
                                                echo "<td>{$row['Product_Price']}</td>";
                                                echo "<td>{$row['Product_Cost']}</td>";
                                                echo "<td>{$row['Size']}</td>";
                                                $status = ($row['Product_Status'] == 1) ? "Active" : "Inactive";
                                                echo "<td>{$status}</td>";
                                                echo "<td><button type='button' onclick='editProduct({$row['Product_ID']})' class='btn btn-primary'>Edit</button></td>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>No products found.</p>
                        <?php endif; ?>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Cost</th>
                                    <th scope="col">Size</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   $sql = "SELECT *, p.Product_ID AS Product_ID FROM product p 
                                   JOIN record_time r ON p.Product_ID = r.Product_ID 
                                   JOIN product_category pc ON p.PC_ID = pc.PC_ID
                                   JOIN size s ON p.Size_ID = s.Size_ID
                                   LEFT JOIN product_delete pd ON p.Product_ID = pd.Product_ID
                                   WHERE pd.Product_ID IS NULL
                                   ORDER BY p.Product_ID DESC";

                                    $result = mysqli_query($conn, $sql);

                                    if ($result) {
                                        $num = 0;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $num++;
                                            echo "<tr>";
                                            $image = $row['Product_Image'];
                                            echo "<td>{$num}</td>";
                                            echo "<td><img src='img/{$image}' alt='image' width='100' height='100'></td>";
                                            echo "<td>{$row['Product_ID']}</td>";
                                            echo "<td>{$row['Product_Name']}</td>";
                                            echo "<td>{$row['Category']}</td>";
                                            echo "<td>{$row['Product_Description']}</td>";
                                            echo "<td>{$row['Product_quantity_available']}</td>";
                                            echo "<td>{$row['Product_Price']}</td>";
                                            echo "<td>{$row['Product_Cost']}</td>";
                                            echo "<td>{$row['Size']}</td>";
                                            $status = ($row['Product_Status'] == 1) ? "Active" : "Inactive";
                                            echo "<td>{$status}</td>";
                                            echo "<td><button type='button' onclick='editProduct({$row['Product_ID']})' class='btn btn-primary'>Edit</button></td>";
                                            echo "</tr>";   
                                        }
                                    } else {
                                        echo "Error executing the query: " . mysqli_error($conn);
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                    <hr class="text-white">
                    <button type="button" class="btn btn-primary" onclick="back()">Back</button>
                </div>
            </div>
            <!-- Main Content End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    <!--Main Script-->
    <?php require "js/Main_js.php"?>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>
