<?php

require "dataconnection.php";

if (isset($_POST["signup"])) {
  
    // Proceed with form submission
    $adName = $_POST['adminname'];
    $adEmail = $_POST['adminEmail'];
    $adpassword = $_POST['adminPassword'];
    
    // Hash the password with MD5 (Note: Consider using a stronger hashing algorithm)
    $hashedPassword = md5($adpassword);

    // Check if admin ID or email already exists using prepared statement
    $checkQuery = "SELECT * FROM admin WHERE Admin_Email = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $adEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['notification'] = "Admin Email already exists";
        header("Location: signup.php"); // Redirect back to the signup page
        exit;
    } else {
        // Insert new admin using prepared statement
        $insertQuery = "INSERT INTO admin ( Admin_Username, Admin_Password, Admin_Email) VALUES (?,  ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $adName, $hashedPassword, $adEmail);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>
                            alert("Admin account created successfully.");
                            setTimeout(function() {
                                window.location.href = "signin.php";
                            }, 000)
                          </script>';
            exit();
        } else {
            echo '<script>
            alert("Failed to create admin account.");
            setTimeout(function() {
                window.location.href = "signup.php";
            }, 000); 
          </script>';
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head.html' ?>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <?php include 'sidebar.php'; ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <?php include 'navbar.php'; ?>
            <!-- Navbar End -->

            <!-- Sign Up Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="text-center mb-4">
                                <h3 class="text-primary"><i class="fa fa-user-plus me-2"></i>New Staff</h3>
                            </div>

                            <!-- Form Start -->
                            <form method="post" action="" oninput='adminCPassword.setCustomValidity(adminCPassword.value != adminPassword.value ? "Passwords do not match." : "")'>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="adminname" placeholder="Admin Username" required>
                                    <label for="adminname">Admin Username</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="adminEmail" placeholder="Email" required>
                                    <label for="adminEmail">Email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="adminPassword" name="adminPassword" placeholder="Password" oninput="checkPasswordLength()" required>
                                    <label for="adminPassword">Password</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="adminCPassword" name="adminCPassword" placeholder="Confirm Password" required>
                                    <label for="adminCPassword">Confirm Password</label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="signup" name="signup">Add Staff</button>
                                </div>
                            </form>
                            <!-- Form End -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign Up End -->
        </div>
        <!-- Content End -->
    </div>

    <!-- Main Script -->
    <?php require "js/Main_js.php" ?>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>
