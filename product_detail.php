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
                                <h3 class="breadcrumb-title">SHOP</h3>
                                <ul class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
                                    <li class="breadcrumb-item"><a href="product.php">Shop</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb area end -->

        <!-- page main wrapper start -->
        <div class="shop-main-wrapper section-padding pb-0">
            <div class="container">
                <div class="row">
                    <!-- product details wrapper start -->
                    <div class="col-lg-12 order-1 order-lg-2">
                        <!-- product details inner end -->
                        <?php
                            $product_name = $_GET['Product_Name'];
                            include("config.php");
                            
                            // 获取产品详情
                            $sql = "SELECT * FROM product WHERE Product_Name = '$product_name'";
                            $result = $conn->query($sql);
                            
                            $stock_sql = "SELECT Product_Name, SUM(Product_quantity_available) AS total_stock
                            FROM product
                            WHERE Product_Name = '$product_name' AND Product_Status = '1'
                            GROUP BY Product_Name";
                            $stock_result = $conn->query($stock_sql);
                            
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $stock_row = $stock_result->fetch_assoc();
                                $image = $row["Product_Image"];
                                ?>
                                <div class="product-details-inner">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="product-large-slider">
                                                <div class="pro-large-img img-zoom">
                                                    <img src="admin/img/product/<?php echo $image ?>" alt="product-details" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="product-details-des">
                                                <h3 class="product-name"><?php echo $row["Product_Name"] ?></h3>
                                                <div class="price-box">
                                                    <span class="price-regular">RM <?php echo $row["Product_Price"] ?></span>
                                                </div>
                                                <div class="availability">
                                                    <i class="fa fa-check-circle"></i>
                                                    <span><?php echo $stock_row["total_stock"] ?> in stock</span>
                                                </div>
                                                <form id="add-to-cart-form" action="insert-cart.php" method="post">
                                                    <input type="hidden" name="product_name" value="<?php echo $row["Product_Name"] ?>">
                                                    <?php if (isset($_SESSION['Customer_ID'])) { ?>
                                                        <input type="hidden" name="customer_id" value="<?php echo $_SESSION['Customer_ID']; ?>">
                                                    <?php } ?>
                                                    <div class="quantity-cart-box d-flex align-items-center">
                                                        <?php 
                                                        // 1. 获取所有相关的 Size_ID
                                                        $size_id_sql = "SELECT Size_ID FROM product WHERE Product_Name = '$product_name' AND Product_Status = 1";
                                                        $size_id_result = $conn->query($size_id_sql);
                                                        
                                                        // 2. 将所有 Size_IDs 存储在一个数组中
                                                        $size_ids = array();
                                                        while($size_id_row = $size_id_result->fetch_assoc()) {
                                                            $size_ids[] = $size_id_row['Size_ID'];
                                                        }
                                                        
                                                        // 3. 将数组转换为字符串以便在 SQL 查询中使用
                                                        $size_ids_string = implode(',', $size_ids);
                                                        
                                                        // 4. 获取所有尺寸并排序
                                                        if (!empty($size_ids_string)) {
                                                            $size_sql = "SELECT Size FROM size WHERE Size_ID IN ($size_ids_string) ORDER BY Size ASC";
                                                            $size_result = $conn->query($size_sql);
                                                        }
                                                        ?>
                                                        <h6 class="option-title">size:</h6>
                                                        <select class="nice-select" name="size">
                                                            <?php
                                                                while($size_row = $size_result->fetch_assoc()) { ?>
                                                                    <option value="<?php echo $size_row["Size"] ?>"><?php echo $size_row["Size"] ?></option>
                                                                <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="quantity-cart-box d-flex align-items-center">
                                                        <h6 class="option-title">qty:</h6>
                                                        <?php
                                                        $max_qty = ($stock_row["total_stock"] < 9) ? $stock_row["total_stock"] : 9;
                                                        ?>
                                                        <input type="number" name="quantity" min="1" max="<?php echo $max_qty; ?>" value="1" class="nice-select">
                                                    </div>
                                                    <div class="action_link">
                                                        <button type="submit" class="btn btn-cart2">Add To Cart</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                echo "Product not found";
                            }
                        ?>
                        <!-- product details inner end -->

                        <!-- product details reviews start -->
                        <?php
                            $product_name = $_GET['Product_Name'];

                            $sql = "SELECT * FROM product WHERE Product_Name = '$product_name'";
                            $result = $conn->query($sql);

                            if ($result->num_rows >0) {
                                $row = $result->fetch_assoc();?>
                                <div class="product-details-reviews section-padding pb-0">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="product-review-info">
                                                <ul class="nav review-tab">
                                                    <li>
                                                        <a class="active" data-bs-toggle="tab" href="#tab_one">description</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content reviews-tab">
                                                    <div class="tab-pane fade show active" id="tab_one">
                                                        <div class="tab-one">
                                                            <p><?php echo $row["Product_Description"] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php   } ?>
                        <!-- product details reviews end -->
                    </div>
                    <!-- product details wrapper end -->
                </div>
            </div>
        </div>
        <!-- page main wrapper end -->

        <!-- Related product area start -->
        <section class="product-gallery section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <h3 class="title">RELATED PRODUCT</h3>
                            <h4 class="sub-title">Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius claritas est etiam processus dynamicus, qui sequitur mutationem.</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="product-carousel--4 slick-row-5 slick-arrow-style">
                            <!-- product single item start -->
                            <?php 
                                $pc_sql = "SELECT PC_ID FROM product WHERE Product_Name = ?";
                                $stmt = $conn->prepare($pc_sql);
                                $stmt->bind_param("s", $product_name);
                                $stmt->execute();
                                $pc_result = $stmt->get_result();

                                if ($pc_result->num_rows > 0) {
                                    $row = $pc_result->fetch_assoc();
                                    $pc_id = $row["PC_ID"];

                                    $sql = "SELECT * 
                                    FROM product 
                                    WHERE PC_ID = ? AND Product_Name != ?
                                    GROUP BY Product_Name
                                    ORDER BY RAND()";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("is", $pc_id, $product_name);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $image = $row["Product_Image"];?>
                                                <div class="product-item">
                                                    <div class="product-thumb">
                                                        <a href="product_detail.php?Product_Name=<?php echo urlencode($row["Product_Name"]); ?>">
                                                            <img src="admin/img/product/<?php echo htmlspecialchars($image); ?>" alt="product thumb">
                                                        </a>
                                                        <div class="button-group">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quick_view" data-product-name="<?php echo htmlspecialchars($row['Product_Name']); ?>">
                                                                <span data-bs-toggle="tooltip" title="Quick View">
                                                                    <i class="fa fa-eye"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="product-caption">
                                                            <h6 class="product-name">
                                                                <a href="product_detail.php?Product_Name=<?php echo urlencode($row["Product_Name"]); ?>"><?php echo htmlspecialchars($row["Product_Name"]); ?></a>
                                                            </h6>
                                                            <div class="price-box">
                                                                <span class="price-regular">RM <?php echo htmlspecialchars($row["Product_Price"]); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php 
                                        }
                                    }
                                } ?>
                            <!-- product single item end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Related product area end -->
    </main>

    <?php include("footer.php"); ?>

    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->



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

    <script>
        document.getElementById('add-to-cart-form').addEventListener('submit', function(event) {
            var customerId = <?php echo isset($_SESSION['Customer_ID']) ? 'true' : 'false'; ?>;
            if (!customerId) {
                event.preventDefault();
                window.location.href = 'login.php';
            }
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