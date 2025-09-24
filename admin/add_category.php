<?php 
require 'dataconnection.php';

if(isset($_POST["add"])) {
    // Retrieve form data
    $category = isset($_POST["Category"]) ? trim($_POST["Category"]) : '';
   
    // Input validation
    if (empty($category)) {
        echo "<script>alert('Category Name cannot be empty')</script>";
    } else {
        // Check for existing product
        $checkExisting = mysqli_prepare($conn, "SELECT * FROM product_category WHERE Category = ?");
        if (!$checkExisting) {
            die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($checkExisting, "s", $category);
        mysqli_stmt_execute($checkExisting);
        $resultExisting = mysqli_stmt_get_result($checkExisting);

        if ($resultExisting->num_rows > 0) {
            echo "<script>alert('Category Name already exists')</script>";
        } else {
            // Insert product into database
            $add = mysqli_prepare($conn, "INSERT INTO product_category (Category) VALUES (?)");

            if (!$add) {
                die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($add, "s", $category);
            mysqli_stmt_execute($add);

            if (mysqli_stmt_affected_rows($add) > 0) {
                echo "<script>alert('Category added successfully')</script>";
            } else {
                echo "<script>alert('Error during add. MySQL Error: " . mysqli_error($conn) . "')</script>";
            }
        }
    }
}
?>

<style>
    thead {
        background-color: #343a40;
    }
    th, td, label {
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
                <h3 class="mb-4">New Category</h3>
                <form action="add_category.php" method="post">
                    <div class="mb-3 row">
                        <label for="categoryName" class="col-sm-2 col-form-label">Category Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Category Name" name="Category" required>
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

            <!-- Category List Table -->
            <div class="bg-secondary rounded p-4" style="margin: 15px;">
                <h3>Category List</h3>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-white">
                                <th scope="col">#</th>
                                <th scope="col">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM product_category";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                $num = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $num++;
                                    echo "<tr>";
                                    echo "<td>{$num}</td>";
                                    echo "<td>{$row['Category']}</td>";
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
            <!-- Category List Table -->
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
    </script>
</body>

</html>
