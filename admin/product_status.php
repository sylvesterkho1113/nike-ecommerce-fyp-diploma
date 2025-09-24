<?php
require 'dataconnection.php'; // Include your database connection file

if(isset($_POST['editpage'])) {
    header("Location: product_status-edit.php");
    exit(); // Stop further execution
}

if(isset($_POST['search'])) {
    $pname = $_POST['name'];
    
    $checkExisting = mysqli_prepare($conn, "SELECT *, p.Product_ID as Product_ID FROM product p 
        JOIN record_time r ON p.Product_ID = r.Product_ID
        JOIN product_category c ON p.PC_ID = c.PC_ID
        JOIN size s ON p.Size_ID = s.Size_ID
        LEFT JOIN product_delete pd ON p.Product_ID = pd.Product_ID
        WHERE p.Product_Name = ? AND pd.Product_ID IS NULL");
    
    if (!$checkExisting) {
        die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($checkExisting, "s", $pname);
    mysqli_stmt_execute($checkExisting);
    $resultExisting = mysqli_stmt_get_result($checkExisting);
    $resultnull = true;

    if ($resultExisting && $resultExisting->num_rows > 0) {
        $row = mysqli_fetch_assoc($resultExisting);
        $Name = $row["Product_Name"];
        $id = $row["Product_ID"];
        $_SESSION['status_Product_ID'] = $id;
        $pCategory = $row["Category"];
        $pDescription = $row["Product_Description"];
        $pQuantity = $row["Product_quantity_available"];
        $pPrice = $row["Product_Price"];
        $pCost = $row["Product_Cost"];
        $Size = $row["Size"];
        $Status = $row["Product_Status"];
        $resultnull = false;
    }
}

if(isset($_POST['edit'])) {
    $status = $_POST['Status'];
    $id = $_POST['Product_Id'];
    if($status == NULL || $id == NULL) {
        echo "<script>alert('Please fill in all fields')</script>";
        exit();
    }
    $update = "UPDATE product SET Product_Status = ? WHERE Product_ID = ?";
    
    $stmt = mysqli_prepare($conn, $update);
    if (!$stmt) {
        die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ii", $status, $id);
    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Product $id Updated Successfully')</script>";
    } else {
        echo "<script>alert('Product $id Update Failed: " . mysqli_stmt_error($stmt) . "')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.html'; ?>
    <link rel="stylesheet" href="styles.css">
    <style>
        thead {
            background-color: #343a40;
        }
        td {
            color: white;
        }
        /* Custom styles */
        .flex-container {
            display: flex;
            justify-content: space-between;
        }
        @media (max-width: 576px) {
            .flex-container {
                flex-direction: column;
                align-items: center;
            }
            .flex-container > div {
                margin-bottom: 10px;
                padding-right: 0;
            }
        }
        @media (min-width: 576px) {
            .flex-container > div {
                flex: 1; /* Equal width for all flex items */
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative d-flex p-0" style="background-color: black;">
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
            <?php include 'navbar.php'; ?>
            <div class="container-fluid pt-4 px-4">
                <!-- <div class="vh-100 bg-secondary rounded mx-0 p-3"> -->
                    <div class="flex-container" style="justify-content:space-between">
                        <div>
                            <h3>Product Status Change</h3>
                        </div>
                        <div>
                            <form action="product_status.php" method="post">
                                <input type="text" name="name" placeholder="Enter name to edit">
                                <button type="submit" class="btn btn-primary" name="search">Search</button>
                            </form>
                        </div>
                        <div>
                            <form action="product_status.php" method="post">
                                <button type="submit" class="btn btn-primary" name="editpage">Multi Edit</button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <?php if(isset($checkExisting)): ?>
                            <hr>
                            <?php if($resultnull): ?>
                                <h3>Product ID does not exist</h3>
                            <?php else: ?>
                                <h3>Search Result</h3>
                                <form action="product_status.php" method="post">
                                    <input type="hidden" name="Product_Id" value="<?php echo $_SESSION['status_Product_ID']; ?>">
                                    <table class="table text-start align-middle table-bordered table-hover mb-3">
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
                                                <td><?php echo $id; ?></td>
                                                <td><?php echo $Name; ?></td>
                                                <td><?php echo $pCategory; ?></td>
                                                <td><?php echo $pDescription; ?></td>
                                                <td><?php echo $pQuantity; ?></td>
                                                <td><?php echo $pPrice; ?></td>
                                                <td><?php echo $pCost; ?></td>
                                                <td><?php echo $Size; ?></td>
                                                <td>
                                                    <div class='form-floating mb-3'>
                                                        <select class='form-select' id='floatingSelect' name='Status' aria-label='Floating label select example'>
                                                            <option value='1' <?php echo ($Status == '1') ? 'selected' : ''; ?>>Active</option>
                                                            <option value='0' <?php echo ($Status == '0') ? 'selected' : ''; ?>>Inactive</option>
                                                        </select>
                                                        <label for='floatingSelect'>Status</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="submit" name="edit" class="btn btn-primary">Update</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-start align-middle table-bordered table-hover mb-3">
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
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: #1d2026;">
                                        <?php
                                        $sql = "SELECT *, p.Product_ID AS Product_ID 
                                                FROM product p 
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
                                                echo "<td>" . ($num) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Product_ID']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Product_Name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Category']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Product_Description']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Product_quantity_available']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Product_Price']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['Product_Cost']) . "</td>";
                                                $status = ($row['Product_Status'] == 1) ? "Active" : "Inactive";
                                                echo "<td>" . htmlspecialchars($status) . "</td>";
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
                        
                    </div>
                    <div>
                        <hr>
                        <button type="button" class="btn btn-primary" onclick="back()">Back</button>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <?php require "js/Main_js.php"; ?>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        function back() {
            window.history.back();
        }
    </script>
</body>
</html>
