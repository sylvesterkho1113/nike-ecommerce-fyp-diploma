<?php
    session_start();
    include("config.php");
    $Customer_ID = $_SESSION["Customer_ID"];

    $sql = "Select * from customer where Customer_ID = $Customer_ID and Customer_Status = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cus_id = $row["Customer_ID"];
        $delivery_address_1 = $row["Customer_Delivery_Address_1"];
        $delivery_address_2 = $row["Customer_Delivery_Address_2"];
        $delivery_address_3 = $row["Customer_Delivery_Address_3"];
    } 

    if (isset($_POST["update"])) {
        // Retrieve product details
        $cus_id = isset($_POST["cus_id"]) ? trim($_POST["cus_id"]) : '';
        $delivery_address_1 = isset($_POST["delivery_address_1"]) ? trim($_POST["delivery_address_1"]) : '';
        $delivery_address_2 = isset($_POST["delivery_address_2"]) ? trim($_POST["delivery_address_2"]) : '';
        $delivery_address_3 = isset($_POST["delivery_address_3"]) ? trim($_POST["delivery_address_3"]) : '';

        // Prepare and execute update statement for product details
        // Prepare and execute update statement for customer details
        $update = mysqli_prepare($conn, "UPDATE customer SET Customer_Delivery_Address_1 = ?, Customer_Delivery_Address_2 = ?, Customer_Delivery_Address_3 = ?  WHERE Customer_ID = ?");
        if (!$update) {
            die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($update, "sssi",$delivery_address_1, $delivery_address_2, $delivery_address_3,  $cus_id);
        mysqli_stmt_execute($update);

        if (mysqli_stmt_affected_rows($update) > 0) {
            echo "<script>alert('Profile Updated Successfully')</script>";
        } else {
            echo "<script>alert('Profile Update Failed')</script>";
        }
        header("Location: my-account.php");
    }

?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>TKT Sport Shoes</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/header_logo.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS
	============================================ -->
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,600,700,800,900%7CPoppins:300,400,500,600,700,800,900" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- Font-awesome CSS -->
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
    <!-- Slick slider css -->
    <link rel="stylesheet" href="assets/css/plugins/slick.min.css">
    <!-- animate css -->
    <link rel="stylesheet" href="assets/css/plugins/animate.css">
    <!-- Nice Select css -->
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">
    <!-- jquery UI css -->
    <link rel="stylesheet" href="assets/css/plugins/jqueryui.min.css">
    <!-- main style css -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <?php include("header.php"); ?>

    <main>
        <!-- breadcrumb area start -->
        <div class="breadcrumb-area breadcrumb-img bg-img" data-bg="assets/img/banner/shop.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <h3 class="breadcrumb-title">MY ACCOUNT</h3>
                                <ul class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add / Edit Delivery Address</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb area end -->

        <!-- my account wrapper start -->
        <div class="my-account-wrapper section-padding">
            <div class="container">
                <div class="section-bg-color">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- My Account Page Start -->
                            <div class="myaccount-page-wrapper">
                                <!-- My Account Tab Menu Start -->
                                <div class="row" style="justify-content: center; align-items: center;">
                                    <!-- My Account Tab Content Start -->
                                    <div class="col-lg-9 col-md-8">
                                        <div class="tab-content" id="myaccountContent">
                                            <!-- Single Tab Content Start -->
                                                <div class="myaccount-content">
                                                    <h5>Delivery Address (You can add a new delivery address or add more delivery address !!!)</h5>
                                                    <div class="account-details-form">
                                                        <form method="post" action="edit-address.php">
                                                            <input type="hidden" name="cus_id" value="<?php echo htmlspecialchars($cus_id); ?>">
                                                            <div class="single-input-item">
                                                                <label for="address" class="required">Delivery Address 1</label>
                                                                <input type="phnum" name="delivery_address_1" id="email" <?php echo "value='$delivery_address_1'";?> placeholder="Address 1"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <label for="address" class="required">Delivery Address 2</label>
                                                                <input type="phnum" name="delivery_address_2" id="email" <?php echo "value='$delivery_address_2'";?> placeholder="Address 2"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <label for="address" class="required">Delivery Address 3</label>
                                                                <input type="phnum" name="delivery_address_3" id="email" <?php echo "value='$delivery_address_3'";?> placeholder="Address 3"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <button class="btn btn-sqr" type="submit" name="update">Upload</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                            </div> <!-- Single Tab Content End -->
                                        </div>
                                    </div> <!-- My Account Tab Content End -->
                                </div>
                            </div> <!-- My Account Page End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- my account wrapper end -->
    </main>

    <?php include("footer.php"); ?>




    <!-- JS
============================================ -->

    <!-- Modernizer JS -->
    <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <!-- jQuery JS -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <!-- slick Slider JS -->
    <script src="assets/js/plugins/slick.min.js"></script>
    <!-- Countdown JS -->
    <script src="assets/js/plugins/countdown.min.js"></script>
    <!-- Nice Select JS -->
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <!-- jquery UI JS -->
    <script src="assets/js/plugins/jqueryui.min.js"></script>
    <!-- Image zoom JS -->
    <script src="assets/js/plugins/image-zoom.min.js"></script>
    <!-- image loaded js -->
    <script src="assets/js/plugins/imagesloaded.pkgd.min.js"></script>
    <!-- masonry  -->
    <script src="assets/js/plugins/masonry.pkgd.min.js"></script>
    <!-- mailchimp active js -->
    <script src="assets/js/plugins/ajaxchimp.js"></script>
    <!-- contact form dynamic js -->
    <script src="assets/js/plugins/ajax-mail.js"></script>
    <!-- google map api -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfmCVTjRI007pC1Yk2o2d_EhgkjTsFVN8"></script>
    <!-- google map active js -->
    <script src="assets/js/plugins/google-map.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
</body>

</html>