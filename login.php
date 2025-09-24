<?php

    include("config.php");

    session_start();
    
    // Check if the 'login' cookie is set and has a value of 'true'
    if (isset($_COOKIE['cus-login']) && $_COOKIE['cus-login'] === 'true') {
        // Set session variables using cookie values
        $_SESSION["cus-login"] = true;
        $_SESSION["Customer_Username"] = $_COOKIE["cus-username"];
        $_SESSION["Customer_ID"] = $_COOKIE["customerid"];
        header("Location: my-account.php");
        exit(); // Make sure to exit after header redirection
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        $hashed_password = md5($password);

        // 使用预处理语句提高安全性
        $sql = $conn->prepare("SELECT * FROM customer WHERE Customer_Email=? AND Customer_Password=?");
        $sql->bind_param("ss", $email, $hashed_password);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($row['Customer_Email'] === $email && $row['Customer_Password'] === $hashed_password) {
                //echo "Logged In!";
                $_SESSION['Customer_ID'] = $row['Customer_ID'];
                $_SESSION['Customer_Username'] = $row['Customer_Username'];
                $_SESSION['Customer_Email'] = $row['Customer_Email'];

                // 将上传状态设置为1
                $update_sql = $conn->prepare("UPDATE customer SET Customer_Status = 1 WHERE Customer_ID = ?");
                $update_sql->bind_param("i", $_SESSION['Customer_ID']);
                $update_sql->execute();

                $check_cart_sql = $conn->prepare("SELECT * FROM cart WHERE Customer_ID = ?");
                $check_cart_sql->bind_param("i", $_SESSION['Customer_ID']);
                $check_cart_sql->execute();
                $cart_result = $check_cart_sql->get_result();

                if ($cart_result->num_rows === 0) {
                    // 插入Customer_ID到cart表
                    $insert_cart_sql = $conn->prepare("INSERT INTO cart (Customer_ID) VALUES (?)");
                    $insert_cart_sql->bind_param("i", $_SESSION['Customer_ID']);
                    $insert_cart_sql->execute();
                }


                 // Set cookies for login status, username, and time with a one-month expiration
                setcookie("cus-login", "true", time() + (30 * 24 * 60 * 60), "/"); // 30 days
                setcookie("cus-username", $row["Customer_Username"], time() + (30 * 24 * 60 * 60), "/"); // 30 days
                setcookie("customerid", $row["Customer_ID"], time() + (30 * 24 * 60 * 60), "/"); // 30 days

                header("Location: product.php");
                exit();
            } else {
                header("Location: login.php?error=Incorrect Email or Password");
                exit();
            }
        } else {
            header("Location: login.php?error=Incorrect Email or Password");
            exit();
        }
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
                                <h3 class="breadcrumb-title">LOGIN</h3>
                                <ul class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
                                    <li class="breadcrumb-item active" aria-current="page">Login</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb area end -->

        <!-- login register wrapper start -->
        <div class="login-register-wrapper section-padding" >
            <div class="container">
                <div class="member-area-from-wrap">
                    <div class="row" style="justify-content: center; align-items: center;">
                        <!-- Login Content Start -->
                        <div class="col-lg-6">
                            <div class="login-reg-form-wrap">
                                <h5>Sign In</h4>
                                <form action="login.php" method="POST" >
                                    <div class="single-input-item">
                                        <input type="email" name="email" placeholder="Email or Username" required />
                                    </div>
                                    <div class="single-input-item">
                                        <input type="password" name="password" placeholder="Enter your Password" required />
                                    </div>
                                    <div class="single-input-item">
                                        <div class="login-reg-form-meta d-flex align-items-center justify-content-between" >
                                            <a href="register.php" class="forget-pwd">Register</a>
                                            <a href="forgotpassword.php" class="forget-pwd">Forget Password?</a>
                                        </div>
                                    </div>
                                    <div class="single-input-item">
                                        <button class="btn btn-sqr">Login</button>
                                    </div>
                                    <?php if(isset($_GET['error'])) { ?>
                                            <p class="error" style="color: red;"> <?php echo $_GET['error']; ?></p>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                        <!-- Login Content End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- login register wrapper end -->
    </main>

    <?php include("footer.php"); ?>

    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->



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
