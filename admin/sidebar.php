<?php
    $sidebar = ($conn->query("SELECT * FROM admin WHERE Admin_ID = '$_SESSION[adminid]'"));
    $sidebar = mysqli_fetch_array($sidebar);
    if($sidebar == 0){
        header("Location: signin.php");
        exit();
    }
    else{
    $profile = "SELECT * FROM admin WHERE Admin_ID = '$_SESSION[adminid]'";
    $profile = mysqli_query($conn, $profile);
    $profile = mysqli_fetch_array($profile);
    $image = $profile['Admin_Profile_Image'];
    $name = $profile['Admin_Username'];
    }
?>
<!DOCTYPE html>
<html>
    <body>
    <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h4 class="text-primary"><i class="fa fa-user-edit me-2" style="color:red">TKT Sport Shoes</i></h>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <?php if (empty($image)): ?>
                            <img class="rounded-circle" src="img/user_default.jpeg" alt="" width="40" height="40">
                        <?php else: ?>
                            <img class="rounded-circle" src="<?php echo $image ?>" alt="" width="40" height="40">
                        <?php endif; ?>
                        
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class='ms-3'> 
                        <?php
                        if ( $_SESSION["admin_login"] ) {
                            
                            ?>
                            <h6 class='mb-0 ms-3'><?php echo $name?></h6>
                            <?php if ($_SESSION["adminid"] == '1') { ?>
                                <span>Super Admin</span>
                            <?php } else { ?>
                                <span>Admin</span>
                            <?php } ?>
                                
                        <?php } else { 
                            header("Location: signin.php");
                            exit();
                         } ?>
                            

                        
                    </div>
                        
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a> <!--nav-item nav-link active"-->
                    <a href="profile.php" class="nav-item nav-link"><i class="fa fa-user me-2"></i>Profile</a>
                    <a href="Report.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Report</a>
                    <?php if ($_SESSION["adminid"] == '1') { ?>
                    <a href="add_category.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Category</a>
                    <?php } ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Product</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="product_list-page.php" class="dropdown-item">Product List</a>
                            <a href="add_product.php" class="dropdown-item">Add Product</a>
                            <a href="product-q.php" class="dropdown-item">Product Quantity</a>
                            <a href="product_status.php" class="dropdown-item">Product Status</a>
                            <a href="product_delete.php" class="dropdown-item">Product Delete</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Order</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="order.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Order Status</a>
                            <a href="order_history.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Order History</a>
                        </div>
                    </div> 
                    <?php if ($_SESSION["adminid"] == '1') { ?>
                    <a href="signup.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>New Staff</a>
                    <?php } ?>
                
                </div>
            </nav>
        </div>
    </body>
</html>