<?php
    session_start();
    include("config.php");
    $Customer_ID = $_SESSION["Customer_ID"];

    $sql = "Select * from customer where Customer_ID = $Customer_ID and Customer_Status = 1";
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
    } 

    if (isset($_POST["update"])) {
        // Retrieve product details
        $cus_id = isset($_POST["cus_id"]) ? trim($_POST["cus_id"]) : '';
        $cus_name = isset($_POST["cus_name"]) ? trim($_POST["cus_name"]) : '';
        $cus_email = isset($_POST["cus_email"]) ? trim($_POST["cus_email"]) : '';
        $cus_phnumber = isset($_POST["cus_phnumber"]) ? trim($_POST["cus_phnumber"]) : '';
        $address1 = isset($_POST["address1"]) ? trim($_POST["address1"]) : '';
        $address2 = isset($_POST["address2"]) ? trim($_POST["address2"]) : '';
        $address3 = isset($_POST["address3"]) ? trim($_POST["address3"]) : '';
        $address4 = isset($_POST["address4"]) ? trim($_POST["address4"]) : '';

        // Prepare and execute update statement for product details
        // Prepare and execute update statement for customer details
        $update = mysqli_prepare($conn, "UPDATE customer SET Customer_Username = ?, Customer_Email = ?, Customer_Phone_Number = ?, Billing_Address_Line1 = ?, Billing_Address_Line2 = ?, Billing_Address_Line3 = ?,Billing_Address_Line4 = ? WHERE Customer_ID = ?");
        if (!$update) {
            die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($update, "sssssssi", $cus_name, $cus_email, $cus_phnumber, $address1, $address2, $address3, $address4, $cus_id);
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
                                    <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
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
                                                    <h5>Account Details</h5>
                                                    <div class="account-details-form">
                                                        <form method="post" action="edit_profile.php">
                                                            <input type="hidden" name="cus_id" value="<?php echo htmlspecialchars($cus_id); ?>">
                                                            <div class="single-input-item">
                                                                <label for="display-name" class="required">Username</label>
                                                                <input type="text" name="cus_name"  id="display-name" <?php echo "value='$cus_name'";?> required placeholder="Display Name" />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <label for="email" class="required">Email Addres</label>
                                                                <input type="email" name="cus_email" id="email" readonly <?php echo "value='$cus_email'";?> required placeholder="Email Address"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <label for="phnum" class="required">Phone Number</label>
                                                                <input type="phnum" name="cus_phnumber" id="cus_phnumber" <?php echo "value='$cus_phnumber'";?> required placeholder="Phone Number"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <label for="address" class="required">Address</label>
                                                                <input type="phnum" name="address1" id="address1" <?php echo "value='$address1'";?> required placeholder="Line 1"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <input type="phnum" name="address2" id="address2" <?php echo "value='$address2'";?> required placeholder="Line 2"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <input type="phnum" name="address3" id="address3" <?php echo "value='$address3'";?> required placeholder="Line 3"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <input type="phnum" name="address4" id="address4" <?php echo "value='$address4'";?> required placeholder="Line 4"  />
                                                            </div>
                                                            <div class="single-input-item">
                                                                <button class="btn btn-sqr" type="submit" name="update">Save Changes</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('cus_phnumber');
            var form = document.querySelector('form');
            var errorMessage = document.createElement('div');
            errorMessage.style.color = 'red';
            phoneInput.parentNode.insertBefore(errorMessage, phoneInput.nextSibling);

            form.addEventListener('submit', function(event) {
                var phoneValue = phoneInput.value.replace(/\D/g, ''); // Remove all non-digit characters

                if (phoneValue.length < 10 || phoneValue.length > 11) {
                    event.preventDefault(); // Prevent form submission
                    errorMessage.textContent = 'Phone number must be 10 or 11 digits long.';
                } else {
                    errorMessage.textContent = ''; // Clear any previous error message
                }
            });

            // Format phone number with hyphens and spaces as the user types
            phoneInput.addEventListener('input', function() {
                var num = phoneInput.value.replace(/\D/g, ''); // Remove all non-digit characters
                var formattedNumber = '';

                if (num.length > 3 && num.length <= 7) {
                    formattedNumber = num.slice(0, 3) + '-' + num.slice(3);
                } else if (num.length > 7) {
                    formattedNumber = num.slice(0, 3) + '-' + num.slice(3, 7) + ' ' + num.slice(7);
                } else {
                    formattedNumber = num;
                }

                phoneInput.value = formattedNumber;
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