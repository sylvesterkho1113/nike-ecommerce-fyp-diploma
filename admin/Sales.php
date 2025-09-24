<?php 
require 'dataconnection.php';

if (isset($_GET['id'])) {
    $Orderid = $_GET['id'];
    $_SESSION['Oid'] = $Orderid;

    $checkExisting = mysqli_prepare($conn, "
        SELECT b.Invoice_ID, p.Product_Name, p.Product_Price, p.Product_Image, b.Invoice_Status, r.Quantity, b.Total_Amount, c.Customer_Username, b.Invoice_Date
        FROM `bill_master` b
        JOIN bill_master_transaction r ON b.Invoice_ID = r.Invoice_ID
        JOIN product p ON r.Product_ID = p.Product_ID
        JOIN customer c ON b.Customer_ID = c.Customer_ID
        WHERE b.Invoice_ID = ?
    ");

    if (!$checkExisting) {
        die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($checkExisting, "i", $Orderid);
    mysqli_stmt_execute($checkExisting);
    $resultExisting = mysqli_stmt_get_result($checkExisting);

    if ($resultExisting->num_rows > 0) {
        $invoiceDetails = [];
        while ($row = mysqli_fetch_assoc($resultExisting)) {
            $invoiceDetails[] = $row;
        }
    } else {
        echo "<script>alert('Order ID does not exist')</script>";
        echo "<script>alert('$_GET[id]')</script>";
        echo "<script>window.location = 'index.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <style>
        img{
            width: 100%;
            border: 3px solid black;
        }
        strong{
            color:gray;
        }
    </style>
<?php include 'head.html' ?>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <?php include 'sidebar.php' ?>

        <div class="content">
            <?php include 'navbar.php' ?>

            <div class="bg-secondary rounded h-100 p-4" style="margin: 15px;">
                <h5 class="mb-4">Sales Report <?php echo $_SESSION['Oid'] ?></h5>
                <div class="row">
                    <?php if (!empty($invoiceDetails)) { 
                        $headerSet = false;
                        foreach ($invoiceDetails as $row) {
                            if (!$headerSet) { ?>
                                <div class="col-12 mb-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body d-flex justify-content-between">
                                            <span><strong>Invoice ID :</strong> <?php echo $row['Invoice_ID']; ?></span>
                                            <span><strong>Customer :</strong> <?php echo $row['Customer_Username']; ?></span>
                                            <span><strong>Status :</strong> <?php echo $row['Invoice_Status']; ?></span>
                                            <span><strong>Total :</strong> <?php echo $row['Total_Amount']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php $headerSet = true;
                            } ?>
                          <div class="col-md-4">
                            <div class="card text-white bg-dark mb-3">
                            <div class="card-body" style="background-color:gray">
                                <img src="img/product/<?php echo $row['Product_Image']; ?>" class="product-image" alt="Product Image">
                                <hr style="color:white">
                                <p class="card-text"><b>Product Name :</b> <?php echo $row['Product_Name']; ?></p>
                                <p class="card-text"><b>Product Price :</b> <?php echo $row['Product_Price']; ?></p>
                                <p class="card-text"><b>Quantity :</b> <?php echo $row['Quantity']; ?></p>
                                <p class="card-text"><b>Date :</b> <?php echo $row['Invoice_Date']; ?></p>
                                <hr style="color:white">
                            </div>

                            </div>
                        </div>

                    <?php } } ?>
                </div>
                <button type="button" class="btn btn-primary" onclick="back()">Back</button>
            </div>
        </div>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <?php require "js/Main_js.php" ?>
</body>
</html>
