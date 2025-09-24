<?php
    session_start();

    include("config.php");

    // 检查用户是否已经登录
        $Customer_ID = $_SESSION["Customer_ID"];

        
            $sql_update = "UPDATE customer SET Customer_Status = 0 WHERE Customer_ID = $Customer_ID";
            if ($conn->query($sql_update) === TRUE) {
                // 更新成功后重定向到登录/注册页面
                session_destroy();
                setcookie("cus-login", "", time() - 3600, "/"); // Expire cookie
                setcookie("cus-username", "", time() - 3600, "/"); // Expire cookie
                setcookie("customerid", "", time()- 3600, "/");
                header("Location: login.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        

?>
