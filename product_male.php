<!doctype html>
<html class="no-js" lang="zxx">

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
    <!-- Bookstrap css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
    <!-- Start Header Area -->
    <?php include("header.php"); ?>
    <!-- end Header Area -->

    <main>
        <!-- breadcrumb area start -->
        <div class="breadcrumb-area breadcrumb-img bg-img" data-bg="assets/img/banner/shop.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <h3 class="breadcrumb-title">SHOP</h3>
                                <ul class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
                                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb area end -->

        <!-- page main wrapper start -->
        <div class="shop-main-wrapper section-padding">
            <div class="container">
                    <!-- shop main wrapper start -->
                    <div class="col-lg-9 order-1 order-lg-2" style="margin-left: 10%; max-width: 1500px;">
                        <div class="shop-product-wrapper">
                            <!-- shop product top wrap start -->
                            <div class="shop-top-bar">
                                <div class="row align-items-center">
                                    <div class="col-lg-7 col-md-6 order-2 order-md-1">
                                        <div class="top-bar-left">
                                            <div class="product-view-mode">
                                                <a class="active" href="#" data-target="grid-view" data-bs-toggle="tooltip" title="Grid View"><i class="fa fa-th"></i></a>
                                                <a href="#" data-target="list-view" data-bs-toggle="tooltip" title="List View"><i class="fa fa-list"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- shop product top wrap start -->

                            <!-- product item list wrapper start -->
                            <div class="shop-product-wrap grid-view row mbn-30">
                                <?php include("config.php") ?>
                                <!-- product single item start -->
                                <?php
                                    // Step 1: Retrieve Size_IDs for gender 1
                                    $gender_sql = "SELECT Size_ID FROM size WHERE gender = 0";
                                    $gender_result = $conn->query($gender_sql);
                                    $sizes = [];
                                    if ($gender_result->num_rows > 0) {
                                        while ($gender_result_row = $gender_result->fetch_assoc()) {
                                            $sizes[] = $gender_result_row["Size_ID"];
                                        }
                                    }

                                    $size_list = implode(',', $sizes);

                                    // Step 2: Retrieve products based on Size_IDs
                                    $sql = "SELECT * FROM product WHERE Size_ID IN ($size_list) AND Product_Status = 1";
                                    $result = $conn->query($sql);

                                    $products = [];
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $products[] = $row;
                                        }
                                    }

                                    // Step 3: Group and sum products by Product_Name
                                    $grouped_products = [];
                                    foreach ($products as $product) {
                                        $product_name = $product['Product_Name'];
                                        if (!isset($grouped_products[$product_name])) {
                                            $grouped_products[$product_name] = [
                                                'Product_Name' => $product['Product_Name'],
                                                'Product_Image' => $product['Product_Image'],
                                                'Product_Price' => $product['Product_Price'],
                                                'Product_Description' => $product['Product_Description'],
                                                'total_stock' => 0
                                            ];
                                        }
                                        $grouped_products[$product_name]['total_stock'] += $product['Product_quantity_available'];
                                    }

                                    // Step 4: Display the products
                                    if (!empty($grouped_products)) {
                                        foreach ($grouped_products as $product) {
                                            ?>
                                            <div class="col-md-4 col-sm-6">
                                                <!-- product grid start -->
                                                <div class="product-item">
                                                    <div class="product-thumb">
                                                        <a href="product_detail.php?Product_Name=<?php echo urlencode($product["Product_Name"]); ?>">
                                                            <img src="admin/img/product/<?php echo $product["Product_Image"] ?>" alt="product thumb">
                                                        </a>
                                                        <div class="button-group">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quick_view" data-product-name="<?php echo $product['Product_Name']; ?>">
                                                                <span data-bs-toggle="tooltip" title="Quick View">
                                                                    <i class="fa fa-eye"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="product-caption">
                                                            <h6 class="product-name">
                                                                <a href="product_detail.php?Product_Name=<?php echo urlencode($product["Product_Name"]); ?>"><?php echo $product["Product_Name"] ?></a>
                                                            </h6>
                                                            <div class="price-box">
                                                                <span class="price-regular">RM <?php echo $product["Product_Price"] ?></span>
                                                            </div>
                                                            <div class="availability">
                                                                <i class="fa fa-check-circle"></i>
                                                                <span><?php echo $product["total_stock"] ?> in stock</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- product grid end -->

                                                <!-- product list item end -->
                                                <div class="product-list-item">
                                                    <div class="product-thumb">
                                                        <a href="product_detail.php?Product_Name=<?php echo urlencode($product["Product_Name"]); ?>">
                                                            <img src="admin/img/product/<?php echo $product["Product_Image"] ?>" alt="product thumb">
                                                        </a>
                                                        <div class="button-group">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quick_view" data-product-name="<?php echo $product['Product_Name']; ?>">
                                                                <span data-bs-toggle="tooltip" title="Quick View">
                                                                    <i class="fa fa-eye"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product-content-list">
                                                        <h4 class="product-name"><a href="product_detail.php?Product_Name=<?php echo urlencode($product["Product_Name"]); ?>"><?php echo $product["Product_Name"] ?></a></h4>
                                                        <div class="price-box">
                                                            <span class="price-regular"> RM <?php echo $product["Product_Price"] ?></span>
                                                        </div>
                                                        <div class="availability">
                                                            <i class="fa fa-check-circle"></i>
                                                            <span><?php echo $product["total_stock"] ?> in stock</span>
                                                        </div>
                                                        <p><?php echo $product["Product_Description"] ?></p>                                        
                                                    </div>
                                                </div>
                                                <!-- product list item end -->
                                            </div>
                                            <!-- product single item start -->
                                            <?php
                                        }
                                    } else {
                                        echo "<p style='text-align: center; font-size: 20px; margin-bottom:10px;'>0 Product</p>";
                                    }
                                ?>
                            </div>
                            <!-- product item list wrapper end -->
                        </div>
                    </div>
                    <!-- shop main wrapper end -->
            </div>
        </div>
        <!-- page main wrapper end -->
    </main>

    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->


    <!-- footer area start -->
    <?php include("footer.php"); ?>
    <!-- footer area end -->



    <!-- Quick view modal start -->
    <div class="modal" id="quick_view">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="quick_view_body">
                    <!-- Product details will be loaded here dynamically -->
                </div>
            </div>
        </div>
    </div>
    <!-- Quick view modal end -->


    <!-- script start -->
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var quickViewModal = document.getElementById('quick_view');
        quickViewModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // 触发模态窗口的按钮
            var productName = button.getAttribute('data-product-name'); // 从data-*属性中提取信息
            var modalBody = quickViewModal.querySelector('#quick_view_body');
            
            // 使用AJAX加载产品详情
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_product_details.php?Product_Name=' + encodeURIComponent(productName), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    modalBody.innerHTML = xhr.responseText;
                } else {
                    modalBody.innerHTML = 'Error loading product details.';
                }
            };
            xhr.send();
        });
    });
    </script>
    <!-- script end -->

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