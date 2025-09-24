<?php 
require 'dataconnection.php';

if (isset($_POST["search"])) {
    $ProductName = mysqli_real_escape_string($conn, $_POST["Product_Name"]);

    // Prepare the query to check existing product by name
    $checkExisting = mysqli_prepare($conn, "SELECT p.*, c.Category, s.Size, pd.Product_ID AS Deleted_Product_ID
        FROM product p 
        JOIN record_time r ON p.Product_ID = r.Product_ID
        JOIN product_category c ON p.PC_ID = c.PC_ID
        JOIN size s ON p.Size_ID = s.Size_ID
        LEFT JOIN product_delete pd ON p.Product_ID = pd.Product_ID
        WHERE p.Product_Name LIKE ?");
    
    if (!$checkExisting) {
        die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }
    
    $searchTerm = '%' . $ProductName . '%';
    mysqli_stmt_bind_param($checkExisting, "s", $searchTerm);
    mysqli_stmt_execute($checkExisting);
    $resultExisting = mysqli_stmt_get_result($checkExisting);
    
    if ($resultExisting->num_rows > 0) {
        $row = mysqli_fetch_assoc($resultExisting);
        $productDetails = [
            "ID" => $row["Product_ID"],
            "Name" => $row["Product_Name"],
            "Category" => $row["Category"],
            "Description" => $row["Product_Description"],
            "Quantity" => $row["Product_quantity_available"],
            "Price" => $row["Product_Price"],
            "Cost" => $row["Product_Cost"],
            "Size" => $row["Size"],
            "Status" => ($row["Product_Status"] == 1) ? "Active" : "Inactive"
        ];
        
        $_SESSION["Product_ID"] = $row["Product_ID"];
        
        if (!empty($row["Deleted_Product_ID"])) {
            echo "<script>
                alert('Product has already been deleted');
                window.location.href = 'product_delete.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Product name does not exist');
            window.location.href = 'product_delete.php';
        </script>";
        exit();
    }
}

if (isset($_POST["edit2"])) {
    $id = $_SESSION["Product_ID"];
    $Description = $_POST["description"];

    // Update product status to inactive
    $updateStatus = "UPDATE product SET Product_Status = 0 WHERE Product_ID = ?";
    $stmt = mysqli_prepare($conn, $updateStatus);
    mysqli_stmt_bind_param($stmt, "s", $id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Product status changed successfully');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Insert into product_delete table
    $insert = "INSERT INTO product_delete (Product_ID, Description, Date_of_delete) VALUES (?, ?, CURRENT_TIMESTAMP())";
    $stmt = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt, "ss", $id, $Description);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Product deleted successfully');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.html'; ?>
    <style>
        .flex-container {
            display: flex;
        }
        .flex-container > div {
            margin: 10px;
            padding-right: 50px;
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
        <!-- Spinner -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Content -->
        <div class="content">
            <!-- Navbar -->
            <?php include 'navbar.php'; ?>

            <div class="container-fluid pt-4 px-4">
                <div class="vh-100 bg-secondary rounded mx-0" style="padding:10px; overflow-y:auto;">
                    <div class="flex-container">
                        <div><h3>Product Delete</h3></div>
                        <div>
                            <form action="product_delete.php" method="post">
                                <input type="text" name="Product_Name" placeholder="Enter Name to delete" required>
                                <button type="submit" class="btn btn-primary" name="search">Search</button>
                            </form>
                        </div>
                    </div>

                    <?php if (isset($productDetails)) { ?>
                        <hr>
                        <form action="product_delete.php" method="post">
                            <input type="hidden" name="Product_Id" value="<?php echo $productDetails["ID"]; ?>">
                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                <thead>
                                    <tr class="text-white">
                                        <th scope="col">#</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Cost</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><?php echo $productDetails["ID"]; ?></td>
                                        <td><?php echo $productDetails["Name"]; ?></td>
                                        <td><?php echo $productDetails["Category"]; ?></td>
                                        <td><?php echo $productDetails["Description"]; ?></td>
                                        <td><?php echo $productDetails["Quantity"]; ?></td>
                                        <td><?php echo $productDetails["Price"]; ?></td>
                                        <td><?php echo $productDetails["Cost"]; ?></td>
                                        <td><?php echo $productDetails["Size"]; ?></td>
                                        <td><?php echo $productDetails["Status"]; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="text" name="description" class="form-control" placeholder="Description" required>
                            <button type="submit" name="edit2" class="btn btn-primary">Delete</button>
                        </form>
                    <?php } else { ?>
                        <hr>
                        <div class="table-responsive">
                            <!-- Show product delete list -->
                            <div>
                                <h5>Product Delete History</h5>
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
                                            <th scope="col">Date of delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT pd.*, p.Product_Name, c.Category, s.Size, p.Product_quantity_available, p.Product_Price, p.Product_Cost, p.Product_Description, p.Product_Image
                                                FROM product_delete pd
                                                JOIN product p ON pd.Product_ID = p.Product_ID
                                                JOIN product_category c ON p.PC_ID = c.PC_ID
                                                JOIN size s ON p.Size_ID = s.Size_ID
                                                ORDER BY pd.Product_ID DESC";
                                        $result = mysqli_query($conn, $sql);

                                        if ($result) {
                                            $num = 0;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $num++;
                                                echo "<tr>";
                                                echo "<td>{$num}</td>";
                                                echo "<td><img src='img/product/{$row['Product_Image']}' alt='image' width='100' height='100'></td>";
                                                echo "<td>{$row['Product_ID']}</td>";
                                                echo "<td>{$row['Product_Name']}</td>";
                                                echo "<td>{$row['Category']}</td>";
                                                echo "<td>{$row['Product_Description']}</td>";
                                                echo "<td>{$row['Product_quantity_available']}</td>";
                                                echo "<td>{$row['Product_Price']}</td>";
                                                echo "<td>{$row['Product_Cost']}</td>";
                                                echo "<td>{$row['Size']}</td>";
                                                echo "<td>{$row['Date_of_delete']}</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "Error executing the query: " . mysqli_error($conn);
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    <?php } ?>
                    <hr>
                    <button type="button" class="btn btn-primary" onclick="back()">Back</button>
                </div>
            </div>
        </div>  
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <?php require "js/Main_js.php"; ?>
    <script>
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Back button functionality
        function back() {
            window.history.back();
        }
    </script>
</body>
</html>
