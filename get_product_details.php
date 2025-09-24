<?php
session_start(); // 启动会话

if (isset($_GET['Product_Name'])) {
    $product_name = $_GET['Product_Name'];
    include("config.php");

    // 获取 cart_id
    $cart_id = null;
    if (isset($_SESSION['Customer_ID'])) {
        $customer_id = $_SESSION['Customer_ID'];
        $cart_sql = "SELECT Cart_ID FROM cart WHERE Customer_ID = $customer_id";
        $cart_result = $conn->query($cart_sql);
        if ($cart_result->num_rows > 0) {
            $cart_row = $cart_result->fetch_assoc();
            $cart_id = $cart_row['Cart_ID'];
        }
    }

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
                        <div class="pro-large-img">
                            <img src="admin/img/product/<?php echo $image ?>" alt="product-details" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="product-details-des">
                        <h3 class="product-name"><?php echo $row["Product_Name"] ?></h3>
                        <div class="availability">
                            <i class="fa fa-check-circle"></i>
                            <span><?php echo $stock_row["total_stock"] ?> in stock</span>
                        </div>
                        <div class="price-box">
                            <span class="price-regular">RM <?php echo $row["Product_Price"] ?></span>
                        </div>
                        <p class="pro-desc"><?php echo $row["Product_Description"] ?></p>
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
                                <div class="quantity">
                                    <div class="pro-qty"><input type="text" readonly name="quantity" value="1"></div>
                                </div>
                                <div class="action_link">
                                    <button type="submit" class="btn btn-cart2">Add To Cart</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        <?php
    } else {
        echo "Product not found";
    }
} else {
    echo "Product name not provided";
}
?>

<script>
    document.getElementById('add-to-cart-form').addEventListener('submit', function(event) {
        var customerId = <?php echo isset($_SESSION['Customer_ID']) ? 'true' : 'false'; ?>;
        if (!customerId) {
            event.preventDefault();
            window.location.href = 'login.php';
        }
    });
</script>
