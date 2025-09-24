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
        AND Product_Name LIKE '%$pname%'
        ORDER BY p.Product_ID DESC";

        $psearch_list = mysqli_query($conn, $sql);
        if(!$psearch_list){
            echo "Error executing the query: " . mysqli_error($conn);
        }
    }
?>

<script>
    function edit_q_page(productID) {
        // Set the product ID to the session variable
        window.location.href = "product-q_page.php?id=" + productID;
    }
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.html'; ?>
    <!-- Include CSS stylesheets for improved mobile styling -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add additional CSS styles here */
        .container {
            padding: 20px;
        }

        .flex-container {
            margin-bottom: 20px;
        }

        thead{
            background-color: #343a40;
        }
        td{
            color: white;
        }
        .back-button {
            font-size: 18px;
            padding: 10px 20px;
        }

        .back-button:hover {
            background-color: #0056b3;
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

            <!-- Content Start -->
            <div class="bg-secondary rounded h-100 p-4" style="margin-left:17px; margin-top:15px;margin-right:17px">
                <div class="container">
                    <div class="flex-container d-flex flex-column mb-3">
                        <div> <h3 class="mb-3">Change Quantity</h3></div>
                        <div><form action="product-q.php" method="post" class="d-flex">
                                <input type="text" name="name" placeholder="Search Product by name" class="form-control me-2">
                                <button type="submit" name="psearch" class="btn btn-primary">Search</button>
                            </form></div>
                    </div>

                    <?php if(isset($_POST['psearch'])): ?>
                        <?php if(mysqli_num_rows($psearch_list) > 0): ?>
                            <hr>
                            <!-- Improved styling for better readability on mobile -->
                             <h5>SearchResult</h5>
                            <div class="table-responsive">
                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                    <!-- Table headers -->
                                    <thead>
                                        <tr>
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
                                            <th scope="col">Action</th> <!-- Added column for action -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $num = 0;
                                            while ($row = mysqli_fetch_assoc($psearch_list)) {
                                                $num++;
                                                echo "<tr>";
                                                $image = $row['Product_Image'];
                                                echo "<td>{$num}</td>";
                                                echo "<td><img src='img/product/{$image}' alt='image' width='100' height='100'></td>";
                                                echo "<td>{$row['Product_ID']}</td>";
                                                echo "<td>{$row['Product_Name']}</td>";
                                                echo "<td>{$row['Category']}</td>";
                                                echo "<td>{$row['Product_Description']}</td>";
                                                echo "<td>{$row['Product_quantity_available']}</td>";
                                                echo "<td>{$row['Product_Price']}</td>";
                                                echo "<td>{$row['Product_Cost'  ]}</td>";
                                                echo "<td>{$row['Size']}</td>";
                                                $status = ($row['Product_Status'] == 1) ? "Active" : "Inactive";
                                                echo "<td>{$status}</td>";
                                                echo "<td><button type='button' onclick='editpage({$row['Product_ID']})' class='btn btn-primary'>Edit</button></td>";
                                                echo "</tr>";   
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                    <?php else: ?>
                        <a class="not-found-text">Product not found</a>
                    <?php endif; ?>
                <?php else: ?>
                <h5>Product List</h5>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <!-- Table headers and content -->
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
                                <th scope="col">Action</th> <!-- Added column for action -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT *, p.Product_ID AS Product_ID FROM product p 
                                JOIN record_time r ON p.Product_ID = r.Product_ID 
                                JOIN product_category pc ON p.PC_ID = pc.PC_ID
                                JOIN size s ON p.Size_ID = s.Size_ID
                                WHERE p.Product_ID NOT IN (SELECT Product_ID FROM product_delete)
                                ORDER BY p.Product_ID DESC";
                                $result = mysqli_query($conn, $sql);
                                if ($result) {
                                    $num = 0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $num++;
                                        echo "<tr>";
                                        $image = $row['Product_Image'];
                                        echo "<td>{$num}</td>";
                                        echo "<td><img src='img/product/{$image}' alt='image' width='100' height='100'></td>";
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
                                        echo "<td><button type='button' onclick='edit_q_page({$row['Product_ID']})' class='btn btn-primary'>Edit</button></td>";
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
                <hr>
                <button type="button" class="btn btn-primary back-button" onclick="back()">Back</button>
            </div>
        </div>
        <!-- Content End-->


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
