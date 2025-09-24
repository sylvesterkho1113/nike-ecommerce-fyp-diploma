<?php
session_start();
include("config.php");

$error_message = '';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the session contains the customer's email
    if (isset($_SESSION['Customer_Email'])) {
        $Customer_Email = $_SESSION['Customer_Email'];

        // Get the new password and confirm password from the form
        $new_password = isset($_POST["new_password"]) ? trim($_POST["new_password"]) : '';
        $confirm_password = isset($_POST["cpassword"]) ? trim($_POST["cpassword"]) : '';

        // Check if passwords match
        if ($new_password === $confirm_password) {
            // Check password strength
            if (strlen($new_password) >= 6 && preg_match('/[A-Z]/', $new_password) && preg_match('/[0-9]/', $new_password) && preg_match('/[!@#$%^&*]/', $new_password)) {
                // Hash the new password
                $hashed_password = md5($new_password);

                // Prepare and execute the update statement
                $update = mysqli_prepare($conn, "UPDATE customer SET Customer_Password = ? WHERE Customer_Email = ?");
                if (!$update) {
                    die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
                }

                mysqli_stmt_bind_param($update, "ss", $hashed_password, $Customer_Email);
                mysqli_stmt_execute($update);

                // Check if the update was successful
                if (mysqli_stmt_affected_rows($update) > 0) {
                    echo "<script>alert('Password updated successfully'); window.location.href = 'login.php';</script>";
                    exit;
                } else {
                    $error_message = 'Password update failed';
                }
            } else {
                $error_message = 'Password must be at least 8 characters long and include an uppercase letter, a number, and a special character';
            }
        } else {
            $error_message = 'Passwords do not match';
        }
    } else {
        $error_message = 'Please re-enter your password!!!';
    }
}

// Check if the email parameter is set in the URL
if (isset($_GET['email'])) {
    // Get the email from the URL and decode it
    $email = urldecode($_GET['email']);

    // Set the email in the session
    $_SESSION['Customer_Email'] = $email;
} 
?>

<!DOCTYPE html>
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
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,600,700,800,900%7CPoppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/plugins/slick.min.css">
    <link rel="stylesheet" href="assets/css/plugins/animate.css">
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">
    <link rel="stylesheet" href="assets/css/plugins/jqueryui.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <?php include("header.php"); ?>

    <main>
        <div class="breadcrumb-area breadcrumb-img bg-img" data-bg="assets/img/banner/shop.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <h3 class="breadcrumb-title">Reset Password</h3>
                                <ul class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
                                    <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-register-wrapper section-padding">
            <div class="container">
                <div class="member-area-from-wrap">
                    <div class="row" style="justify-content: center; align-items: center;">
                        <div class="col-lg-6">
                            <div class="login-reg-form-wrap">
                                <h5>Reset Password</h5>
                                <?php if (!empty($error_message)): ?>
                                    <div class="alert alert-danger" style="font-size: 15px; padding: 5px; margin: 5px 0;"><?php echo $error_message; ?></div>
                                <?php endif; ?>
                                <form action="reset_password.php" method="post">
                                    <div class="single-input-item">
                                        <label>New Password</label>
                                        <input type="password" name="new_password" placeholder="At least 8 characters with symbols and numbers" required />
                                    </div>
                                    <div class="single-input-item">
                                        <label>Confirm Password</label>
                                        <input type="password" name="cpassword" placeholder="Re-enter your password" required />
                                    </div>
                                    <div class="single-input-item">
                                        <button class="btn btn-sqr" type="submit" name="save">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include("footer.php"); ?>

    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>

    <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins/slick.min.js"></script>
    <script src="assets/js/plugins/countdown.min.js"></script>
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <script src="assets/js/plugins/jqueryui.min.js"></script>
    <script src="assets/js/plugins/image-zoom.min.js"></script>
    <script src="assets/js/plugins/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/plugins/masonry.pkgd.min.js"></script>
    <script src="assets/js/plugins/ajaxchimp.js"></script>
    <script src="assets/js/plugins/ajax-mail.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script src="assets/js/plugins/google-map.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
