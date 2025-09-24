<?php
    session_start();
    include("config.php");
    include("header.php");

    $Customer_ID = $_SESSION["Customer_ID"];

    $sql = "SELECT * FROM customer WHERE Customer_ID = $Customer_ID and Customer_Status = 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cus_id = $row["Customer_ID"];
        $cus_name = $row["Customer_Username"];
        $cus_email = $row["Customer_Email"];
        $cus_phnumber = $row["Customer_Phone_Number"];
        $address1 = $row["Billing_Address_Line1"];
        $address2 = $row["Billing_Address_Line2"];
        $address3 = $row["Billing_Address_Line3"];
        $address4 = $row["Billing_Address_Line4"];
        $cus_image = $row["Customer_Profile_Image"];
        $delivery_address_1 = $row["Customer_Delivery_Address_1"];
        $delivery_address_2 = $row["Customer_Delivery_Address_2"];
        $delivery_address_3 = $row["Customer_Delivery_Address_3"];
    } else {
        echo "User not found!";
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

    <style>
        .label {
            display: inline-block;
            width: 150px; /* 调整宽度以适应你的需求 */
            font-weight: bold;
        }
        .info {
            background-color:#F1F1F1;
            display: inline-block;
            border: 1px solid black;
            padding-left: 8px;
            width: 60%;
        }
        p {
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .column {
            margin-left: 20px;
        }
    </style>

</head>

<body>

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
                                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
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
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                        <div class="myaccount-tab-menu nav" role="tablist">
                                            <a href="#dashboad" class="active" data-bs-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard </a>
                                            <a href="#history" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders </a>
                                            <a href="#account-info" data-bs-toggle="tab"><i class="fa fa-user"></i> Account Details </a>
                                            <a href="#delivery-address" data-bs-toggle="tab"><i class="fa fa-map-marker"></i> Delivery Address </a>
                                            <a href="#" onclick="confirmLogout()"><i class="fa fa-sign-out"></i> Logout </a>
                                        </div>
                                    </div>
                                    <!-- My Account Tab Menu End -->

                                    <!-- My Account Tab Content Start -->
                                    <div class="col-lg-9 col-md-8">
                                        <div class="tab-content" id="myaccountContent">
                                            <!-- Single Tab Content Start -->
                                            <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                                <div class="myaccount-content">
                                                <h5>Dashboard</h5>
                                                <div class="welcome">
                                                <p>Hello, <strong><?php echo $cus_name ?></strong></p>
                                                </div>
                                                <p class="mb-0">From your account dashboard. you can easily check &
                                                view your recent orders, manage your shipping and billing addresses
                                                and edit your password and account details.</p>
                                                </div>
                                            </div>
                                            <!-- Single Tab Content End -->

                                            <!-- Single Tab Content Start -->
                                            <div class="tab-pane fade" id="history" role="tabpanel">
                                                <div class="myaccount-content">
                                                    <h5>History</h5>
                                                    <div class="myaccount-table table-responsive text-center">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Order</th>
                                                                    <th>Date</th>
                                                                    <th>Status</th>
                                                                    <th>Total (Without include Tax)</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $sql = "SELECT * FROM bill_master WHERE Customer_ID = $Customer_ID";
                                                                    $result = $conn->query($sql);
                                                                    
                                                                    if($result){
                                                                        $num=0;
                                                                        while($row = $result->fetch_assoc()){
                                                                            $num++;
                                                                            echo "<tr>";
                                                                            echo "<td>{$num}</td>";
                                                                            echo "<td>{$row['Invoice_Date']}</td>";
                                                                            echo "<td>{$row['Invoice_Status']}</td>";
                                                                            echo "<td>{$row['Total_Amount']}</td>";
                                                                            echo "<td><a href='Receipt.php?Invoice_ID={$row['Invoice_ID']}' class='btn btn-sqr'>View</a></td>";
                                                                            echo "<tr>";
                                                                        }
                                                                    }else {
                                                                        echo "0 results";
                                                                    }
                                                                
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Single Tab Content End -->

                                            <!-- Single Tab Content Start -->
                                            <div class="tab-pane fade" id="account-info" role="tabpanel">
                                                <div class="myaccount-content">
                                                    <h5>Account Details</h5>
                                                    <div class="account-details-form" class="column">
                                                        <?php if ($result) { ?>
                                                            <p><span class='label'>Username      :</span> <span class='info'><?php echo $cus_name?></span></p>
                                                            <p><span class='label'>Email         :</span> <span class='info'><?php echo $cus_email?></span></p>
                                                            <p><span class='label'>Phone Number  :</span> <span class='info'><?php echo $cus_phnumber?></span></p>
                                                            <p><span class='label'>Address       :</span> <span class='info'><?php echo $address1?>, <?php echo $address2?>, <?php echo $address3?>, <?php echo $address4?></span></p>
                                                            <button class="btn btn-sqr"><a href="edit_profile.php" style="color: white;">Edit</a></button>
                                                        <?php  }?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- Single Tab Content End -->

                                            <!-- Single Tab Content Start -->
                                            <div class="tab-pane fade" id="delivery-address" role="tabpanel">
                                                <div class="myaccount-content">
                                                    <h5>Delivery Address</h5>
                                                    <div class="account-details-form" class="column">
                                                    <?php if ($result) { ?>
                                                            <p>
                                                                <span class='label'>Address 1   :</span> 
                                                                <span class='info' style="margin-right: 15px;"><?php echo $delivery_address_1?></span>
                                                                <button class="btn btn-sqr"><a href="edit-address.php" style="color: white;">Edit</a></button>
                                                            </p>
                                                        <?php  }?>
                                                    </div>
                                                </div>
                                                <div class="myaccount-content">
                                                    <div class="account-details-form" class="column">
                                                        <?php if ($result) { ?>
                                                            <p>
                                                                <span class='label'>Address 2   :</span> 
                                                                <span class='info' style="margin-right: 15px;"><?php echo $delivery_address_2?></span>
                                                                <button class="btn btn-sqr"><a href="edit-address.php" style="color: white;">Edit</a></button>
                                                            </p>
                                                        <?php  }?>
                                                    </div>
                                                </div>
                                                <div class="myaccount-content">
                                                    <div class="account-details-form" class="column">
                                                        <?php if ($result) { ?>
                                                            <p>
                                                                <span class='label'>Address 3   :</span> 
                                                                <span class='info' style="margin-right: 15px;"><?php echo $delivery_address_3?></span>
                                                                <button class="btn btn-sqr"><a href="edit-address.php" style="color: white;">Edit</a></button>
                                                            </p>
                                                        <?php  }?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- Single Tab Content End -->
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

    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->

    <script>
        function confirmLogout() {
            var confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "logout.php";
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-receipt').on('click', function() {
                var invoiceId = $(this).data('invoice-id');
                
                $.ajax({
                    url: 'fetch_receipt.php',
                    method: 'POST',
                    data: { Invoice_ID: invoiceId },
                    success: function(response) {
                        // Assuming the server returns the URL of the generated PDF
                        window.location.href = response.pdfUrl;
                    },
                    error: function() {
                        alert('Error fetching receipt. Please try again.');
                    }
                });
            });
        });
    </script>




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