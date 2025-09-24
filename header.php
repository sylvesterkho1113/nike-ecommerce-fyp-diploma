<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>TKT Sport Shoes</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/header_logo.png">

    <!-- Link CSS -->
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,600,700,800,900%7CPoppins:300,400,500,600,700,800,900" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font-awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Slick slider css -->
    <link rel="stylesheet" href="assets/css/plugins/slick.min.css">
    <!-- animate css -->
    <link rel="stylesheet" href="assets/css/plugins/animate.css">
    <!-- Nice Select css -->
    <link rel="stylesheet" href="assets/css/plugins/nice-select.css">
    <!-- jquery UI css -->
    <link rel="stylesheet" href="assets/css/plugins/jqueryui.min.css">
    <!-- template style css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- main style css -->
    <link rel="stylesheet" href="style.css">
    <!-- template style css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    ?>
    <!-- main header start -->
    <div class="main-header d-none d-lg-block">
        <!-- header top start -->
        <div class="header-top black-bg">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        
                    </div>
                    <div class="col-lg-6 d-flex justify-content-end">
                        <div class="header-social-link">
                            <a href="https://www.tiktok.com/@tkt.2410?is_from_webapp=1&sender_device=pc"><i class="fa-brands fa-tiktok"></i></a>
                            <a href="https://www.instagram.com/tkt.2410?igsh=eWowOGkxYzdmbmR1"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                        <ul class="user-info-block">
                            <?php if (isset($_SESSION['Customer_ID'])): ?>
                                <li><a href="my-account.php"><i class="fa fa-user-circle"></i> My Account</a></li>
                                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i>My Cart</a></li>
                            <?php else: ?>
                                <li><a href="login.php"><i class="fa fa-sign-in"></i> Log In</a></li>
                                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i>My Cart</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- header top end -->

        <!-- header middle area start -->
        <div class="header-main-area black-soft sticky">
            <div class="container">
                <div class="row align-items-center position-relative">
                    <!-- start logo area -->
                    <div class="col-auto">
                        <div class="logo">
                            <a href="home.php">
                                <img src="assets/img/logo.png" alt="Brand Logo" style="width: 250px;">
                            </a>
                        </div>
                    </div>
                    <!-- start logo area -->

                    <!-- main menu area start -->
                    <div class="col-auto position-static">
                        <div class="main-menu-area">
                            <div class="main-menu">
                                <!-- main menu navbar start -->
                                <nav class="desktop-menu">
                                    <ul>
                                        <li class="position-static"><a href="home.php">Home </a></li>
                                        <li><a href="product.php">Shop <i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown">
                                                <li><a href="#">Gender <i class="fa fa-angle-right"></i></a>
                                                    <ul class="dropdown">
                                                        <li><a href="product_male.php">Male</a></li>
                                                        <li><a href="product_female.php">Female</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Category <i class="fa fa-angle-right"></i></a>
                                                    <ul class="dropdown">
                                                    <?php
                                                    include("config.php");
                                                    $sql = "SELECT PC_ID, Category FROM product_category";
                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        // 输出每一行数据
                                                        while ($row = $result->fetch_assoc()) { ?>
                                                            <li><a href="product_by_category.php?PC_ID=<?php echo $row['PC_ID']; ?>"><?php echo $row['Category']; ?></a></li>
                                                        <?php }
                                                    }
                                                    ?>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="about_us.php">About us</a></li>
                                    </ul>
                                </nav>
                                <!-- main menu navbar end -->
                            </div>
                        </div>
                    </div>
                    <!-- main menu area end -->
                </div>
            </div>
        </div>
        <!-- header middle area end -->
    </div>
    <!-- main header end -->
</body>
</html>
