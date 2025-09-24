<?php 
require 'dataconnection.php';

$pImage = ""; // Initialize $pImage variable

// Code for retrieving existing product details and image
if (isset($_GET['id'])) {
    $productID = (int) $_GET['id'];
    $_SESSION['pid'] = $productID;

    $checkExisting = mysqli_prepare($conn, "SELECT * FROM product p JOIN size s ON p.Size_ID = s.Size_ID WHERE Product_ID = ?");
    if (!$checkExisting) {
        die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($checkExisting, "i", $productID);
    mysqli_stmt_execute($checkExisting);
    $resultExisting = mysqli_stmt_get_result($checkExisting);

    if ($resultExisting->num_rows > 0) {
        $row = mysqli_fetch_assoc($resultExisting);
        $pName = $row["Product_Name"];
        $pCategory = $row["PC_ID"];
        $pDescription = $row["Product_Description"];
        $pPrice = $row["Product_Price"];
        $pCost = $row["Product_Cost"];
        $pSize = $row["Size"];
        $pImage = $row["Product_Image"];
        $_SESSION['product_img'] = $pImage;
        $new = $row["New_arrive"];
    } else {
        echo "<script>alert('Product ID does not exist');</script>";
        header("Location: product_list-page.php");
        exit();
    }
}

// Code for updating product details
if (isset($_POST["update"])) {
    $Product_ID = $_SESSION['pid'];
    $pName = isset($_POST["pName"]) ? trim($_POST["pName"]) : '';
    $pCategory = isset($_POST["pCategory"]) ? trim($_POST["pCategory"]) : '';
    $pDescription = isset($_POST["pDescription"]) ? trim($_POST["pDescription"]) : '';
    $pQuantity = isset($_POST["pQuantity"]) ? trim($_POST["pQuantity"]) : '';
    $pPrice = isset($_POST["pPrice"]) ? trim($_POST["pPrice"]) : '';
    $pCost = isset($_POST["pCost"]) ? trim($_POST["pCost"]) : '';
    $pSize = isset($_POST["pSize"]) ? trim($_POST["pSize"]) : '';
    $new = isset($_POST["new"]) ? 1 : 0;

    if ($_FILES["pImage"]["error"] == UPLOAD_ERR_OK) {
        $filename = $_FILES["pImage"]["name"];
        $tempname = $_FILES["pImage"]["tmp_name"];
        $folder = $filename;
        // Move uploaded file to desired directory
        move_uploaded_file($tempname, "uploads/$folder");
    } else {
        $filename = $_SESSION['product_img'];
    }
    
        $update = mysqli_prepare($conn, "UPDATE product SET Product_Name = ?, PC_ID = ?, Product_Description = ?, Product_Price = ?, Product_Cost = ?, Size_ID = ?, Product_Image = ?, New_arrive = ? WHERE Product_ID = ?");
        if (!$update) {
            die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($update, "ssssssssi", $pName, $pCategory, $pDescription, $pPrice, $pCost, $pSize, $filename, $new, $Product_ID);
        
        if (mysqli_stmt_execute($update)) {
            if (mysqli_stmt_affected_rows($update) > 0) {
                echo "<script>alert('Product Updated Successfully');</script>";
                header("Location: product_list-page.php");
                exit();
            } else {
                echo "<script>alert('No rows were affected. Product Update Failed');</script>";
                header("Location: product_list-page.php");
            }
        } else {
            echo "<script>alert('Error executing update query: " . mysqli_error($conn) . "');</script>";
            header("Location: product_list-page.php");
        }
    
}
?>


<!DOCTYPE html>
<style>
    label{
        color:white;
    }
    </style>
<html lang="en">
   <?php include 'head.html'?>
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
            <div>
                <div class="bg-secondary rounded h-100 p-4" style="margin-left:17px; margin-top:15px;margin-right:17px">
                    <div>
                        <h6 class="mb-4">Edit Product Details</h6>                    
                    </div>
                    <div>
                        <?php if(isset($checkExisting)){ ?>
                        <form action="edit_product.php" method="post" enctype="multipart/form-data">
                            <div>
                                <h6 class="mb-4">Product <?php echo "$productID" ?></h6>
                                <!-- Hidden input field to store Product ID -->
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" value="<?php echo $pName; ?>" required placeholder="Product Name" name="pName">
                                    <label for="floatingPassword">Product Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <?php
                                        $sql = "SELECT * FROM product_category";
                                        $result = mysqli_query($conn, $sql);
                                        echo "<select class='form-select' name='pCategory' required>";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($row['PC_ID'] == $pCategory) ? 'selected' : '';
                                            echo "<option value='" . $row['PC_ID'] . "' $selected>" . $row['Category'] . "</option>";
                                        }
                                        echo "</select>";
                                    ?>
                                    <label for="floatingPassword">Product Category</label>
                                </div>
                                <div class="form-floating mb-3">  
                                    <input type="text" class="form-control" value="<?php echo $pDescription; ?>" required placeholder="Product Description" name="pDescription">
                                    <label for="floatingPassword">Product Description</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" min="0" value="<?php echo $pPrice; ?>" required placeholder="Product Price" name="pPrice">
                                    <label for="floatingPassword">Product Price</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" min="0" value="<?php echo $pCost; ?>" required placeholder="Product Cost" name="pCost">
                                    <label for="floatingInput">Product Cost</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <?php
                                        $sql = "SELECT * FROM size";
                                        $result = mysqli_query($conn, $sql);
                                       
                                        echo "<select class='form-select' name='pSize' required>";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($row['Size_ID'] == $pSize) ? 'selected' : '';
                                            echo "<option value='" . $row['Size_ID'] . "' $selected>" . $row['Size'] . "</option>";
                                        }
                                        echo "</select>";
                                        echo "<label style='color:white' for='pSize'>Product Size</label>";
                                    ?>
                                </div>
                                <div>
                                    <label for="new">New Arrived</label>
                                    <input type="checkbox" name="new" <?php if($new == 1){echo "checked";}?>>
                                </div>
                                <br>
                                <div class="form-floating mb-3" >
                                    <input type="file" class="form-control" accept=".jpeg,.jpg,.png" name="pImage" >
                                    <label  style="color:black"for="pImage" class="form-label">Product Image</label>
                                </div>
                                <img src="img/product/<?php echo $pImage ?>" alt="Current Image" width="200" height="200">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary" name="update">Update</button>  
                            <button type="button" class="btn btn-primary" onclick="back()">Back</button>                              
                        </form>
                        <?php }?>
                    </div>
                </div>
            </div>
            <!-- Add Product -->    
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!--Main Script-->
    <?php require "js/Main_js.php"?>
    <!--clean resubmit-->
</body>
</html>
