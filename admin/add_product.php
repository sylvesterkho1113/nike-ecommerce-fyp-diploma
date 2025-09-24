<?php 
require 'dataconnection.php';

if(isset($_POST["add"]))
{
    // Retrieve form data
    $pName = isset($_POST["Product_Name"]) ? trim($_POST["Product_Name"]) : '';
    $pCategory = isset($_POST["Product_Category"]) ? trim($_POST["Product_Category"]) : '';
    $pDescription = isset($_POST["Product_Description"]) ? trim($_POST["Product_Description"]) : '';
    $pQuantity = isset($_POST["Product_Quantity"]) ? trim($_POST["Product_Quantity"]) : '';
    $pPrice = isset($_POST["Product_Price"]) ? trim($_POST["Product_Price"]) : '';
    $pCost = isset($_POST["Product_Cost"]) ? trim($_POST["Product_Cost"]) : '';
    $pSize = isset($_POST["Product_size"]) ? trim($_POST["Product_size"]) : '';

    // Check if image file is uploaded
    if (isset($_FILES["pImage"]) && $_FILES["pImage"]["error"] == UPLOAD_ERR_OK) {
        $filename = $_FILES["pImage"]["name"];
        $tempname = $_FILES["pImage"]["tmp_name"];
        $folder = "img/product/".$filename;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($tempname, $folder)) {
            die("Failed to upload image.");
        }
    } else {
        die("Image not uploaded.");
    }


    
        // Insert product into database
        $add = mysqli_prepare($conn, "INSERT INTO product (Size_ID, Product_Name, Product_Description, Product_quantity_available, Product_Price, Product_Cost, PC_ID, Product_Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$add) {
            die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($add, "ssssssss", $pSize, $pName, $pDescription, $pQuantity, $pPrice, $pCost, $pCategory, $filename);
        mysqli_stmt_execute($add);

        if (mysqli_stmt_affected_rows($add) > 0) {
            echo "<script>alert('Product add Success')</script>";

            // Retrieve the last inserted Product_ID
            $pID = mysqli_insert_id($conn);

            // Insert record time
            $record_time_query = mysqli_prepare($conn, "INSERT INTO record_time (Product_ID, record_time) VALUES (?, NOW())");
            mysqli_stmt_bind_param($record_time_query, "i", $pID);
            mysqli_stmt_execute($record_time_query);

        } else {
            echo "<script>alert('Error during add. MySQL Error: " . mysqli_error($conn) . "')</script>";
        }
    
    mysqli_stmt_close($add);
    mysqli_stmt_close($record_time_query);
    }
?>

<style>
    thead{
        background-color: #343a40;
    }
    th, td, label{
        color: white;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.html' ?>

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

            <!-- Add Product -->
            <div class="bg-secondary rounded p-4" style="margin: 15px;">
                <h3 class="mb-4">New Product Details</h3>
                <form action="add_product.php" method="post" enctype="multipart/form-data" id="addProductForm">
                    <div class="mb-3 row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Product Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Product Name" name="Product_Name" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Product Category</label>
                        <div class="col-sm-10">
                            <?php
                            $sql = "SELECT * FROM product_category";
                            $result = mysqli_query($conn, $sql);

                            echo "<select class='form-select' name='Product_Category' required>";
                            echo "<option selected disabled>Select Category</option>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['PC_ID'] . "'>" . $row['Category'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Product Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Product Description" name="Product_Description" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Product Quantity Available</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" placeholder="Product Quantity Available" min="0" name="Product_Quantity" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Product Price</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" placeholder="Product Price" min="0" name="Product_Price" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Product Cost</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" placeholder="Product Cost" min="0" name="Product_Cost" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Product Size</label>
                        <div class="col-sm-10">
                            <?php
                            $sql = "SELECT * FROM size";
                            $result = mysqli_query($conn, $sql);

                            echo "<select class='form-select' name='Product_size' required>";
                            echo "<option selected disabled>Select Size</option>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($row['gender'] == 0)
                                    $gender = "Male";
                                else if ($row['gender'] == 1)
                                    $gender = "Female";
                                else if ($row['gender'] == 2)
                                    $gender = "Kids";
                                echo "<option value='" . $row['Size_ID'] . "'>" . $gender . " (Size " . $row["Size_ID"] . ")" . "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pImage" class="col-sm-2 col-form-label">Product Image</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" accept=".jpeg,.jpg,.png" name="pImage" id="pImage" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary" name="add">Add</button>
                            <button type="button" class="btn btn-primary" onclick="back()">Back</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Add Product -->

            <!-- Latest Products Table -->
            <div class="bg-secondary rounded p-4" style="margin: 15px;">
                <h3>Lastest Product</h3>
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
                            ORDER BY p.Product_ID DESC
                            LIMIT 10";
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
            <!-- Latest Products Table -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- Main Script -->
    <?php require "js/Main_js.php" ?>
    <!-- Clean resubmit -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    
        document.getElementById('addProductForm').addEventListener('submit', function(event) {
            var imageInput = document.getElementById('pImage');
            if (!imageInput.files || imageInput.files.length === 0) {
                event.preventDefault(); // Prevent form submission
                alert('Please select an image for the product.'); // Show alert
            }
        });
    </script>
</body>
</html>
