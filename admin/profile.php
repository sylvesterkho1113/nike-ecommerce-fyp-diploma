<?php
require 'dataconnection.php';

// Check if admin is logged in

if (!isset($_SESSION['adminid'])) {
    header("Location: login.php");
    exit();
}

$adminID = $_SESSION['adminid'];

// Fetch existing admin data
$checkExisting = mysqli_prepare($conn, "SELECT * FROM admin WHERE Admin_ID = ?");
if (!$checkExisting) {
    die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
}

mysqli_stmt_bind_param($checkExisting, "s", $adminID);
mysqli_stmt_execute($checkExisting);
$resultExisting = mysqli_stmt_get_result($checkExisting);

if ($resultExisting->num_rows > 0) {
    $row = mysqli_fetch_assoc($resultExisting);
    $Name = $row["Admin_Username"];
    $_SESSION['admin_password'] = $row["Admin_Password"]; // assuming this is hashed with md5
    $pImage = $row["Admin_Profile_Image"];
}

// Function to handle image upload
function handleImageUpload($existingImage) {
    if (isset($_FILES["pImage"]) && $_FILES["pImage"]["error"] == UPLOAD_ERR_OK) {
        $filename = $_FILES["pImage"]["name"];
        $tempname = $_FILES["pImage"]["tmp_name"];
        $folder = "img/" . $filename;

        if (move_uploaded_file($tempname, $folder)) {
            return $folder;
        } else {
            echo "<script>alert('File upload failed. Please try again.');</script>";
            return $existingImage;
        }
    } else {
        return $existingImage;
    }
}

if (isset($_POST["update"])) {
    $Name = isset($_POST["Name"]) ? trim($_POST["Name"]) : '';
    $filename = handleImageUpload($pImage);

    $update = mysqli_prepare($conn, "UPDATE admin SET Admin_Username = ?, Admin_Profile_Image = ? WHERE Admin_ID = ?");
    if (!$update) {
        die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($update, "sss", $Name, $filename, $adminID);
    mysqli_stmt_execute($update);

    if (mysqli_stmt_affected_rows($update) > 0) {
        echo "<script>alert('Profile Updated Successfully');</script>";
        header("Location: profile.php");
        exit();
    } else {
        echo "<script>alert('Profile Update Failed');</script>";
    }
}

if (isset($_POST["changepassword"])) {
    $Opassword = isset($_POST["Opassword"]) ? trim($_POST["Opassword"]) : '';
    $Npassword = isset($_POST["Npassword"]) ? trim($_POST["Npassword"]) : '';

    $hashedOpassword = md5($Opassword); // Hash the old password input for comparison

    if ($hashedOpassword == $_SESSION['admin_password']) {
        if ($Opassword === $Npassword) {
            echo "<script>alert('New Password cannot be same as Old Password');</script>";
        } else {
            $hashedNpassword = md5($Npassword); // Hash the new password

            $update = mysqli_prepare($conn, "UPDATE admin SET Admin_Password = ? WHERE Admin_ID = ?");
            if (!$update) {
                die("Prepare failed: (" . mysqli_errno($conn) . ") " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($update, "ss", $hashedNpassword, $adminID);
            mysqli_stmt_execute($update);

            if (mysqli_stmt_affected_rows($update) > 0) {
                echo "<script>alert('Password Updated Successfully'); window.location.href='profile.php';</script>";
                exit();
            } else {
                echo "<script>alert('Password Update Failed');</script>";
            }
        }
    } 
}
?>

<script>
function previewImage(event) {
    var posterPreview = document.getElementById('image');
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function() {
        posterPreview.src = reader.result;
        posterPreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

function back() {
    window.history.back();
}
</script>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.html' ?>
<style>
    h4 {
        color: black;
    }
    label, input {
        color: white;
    }
    .centered {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .profile-img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        margin-bottom: 20px;
    }
</style>
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
        <?php include 'sidebar.php' ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <?php include 'navbar.php' ?>
            <!-- Navbar End -->

            <div class="container mt-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Admin Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!isset($_POST['editprofile']) && !isset($_POST['changepassword'])) { ?>
                            <div class="text-center">
                                <?php if (empty($pImage)): ?>
                                    <img src="img/user_default.jpeg" id="image" class="profile-img" alt="Current Image">
                                <?php else: ?>
                                    <img src="<?php echo $pImage; ?>" id="image" class="profile-img" alt="Current Image">
                                <?php endif; ?>
                                <div class="form-floating mb-3">
                                    <p><b><?php echo $Name; ?></b></p>
                                    <label for="floatingPassword">Admin Name</label>
                                </div>
                                <form action="profile.php" method="post">
                                    <button type="submit" class="btn btn-primary" name="changepassword">Change Password</button>
                                    <button type="submit" class="btn btn-primary" name="editprofile">Edit</button>
                                    <button type="button" class="btn btn-secondary" onclick="back()">Back</button>
                                </form>
                            </div>
                        <?php } ?>

                        <?php if (isset($_POST['editprofile'])) { ?>
                            <form action="profile.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3 centered">
                                    <?php if (!empty($pImage)): ?>
                                        <img src="<?php echo $pImage; ?>" id="image" class="profile-img" alt="Current Image">
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="pImage" class="form-label">Admin Image</label>
                                    <input type="file" class="form-control" id="pImage" accept="image/jpeg,image/jpg,image/png" name="pImage" onchange="previewImage(event)">
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" value="<?php echo $Name; ?>" required placeholder="Admin Name" name="Name">
                                    <label for="floatingPassword">Admin Name</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                                    <button type="button" class="btn btn-secondary" onclick="back()">Back</button>
                                </div>
                            </form>
                        <?php } ?>

                        <?php if (isset($_POST['changepassword'])) { ?>
                            <form action="profile.php" method="post">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" required placeholder="Old Password" name="Opassword">
                                    <label for="floatingPassword">Old Password</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" required placeholder="New Password" name="Npassword">
                                    <label for="floatingPassword">New Password</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="changepassword">Update Password</button>
                                    <button type="button" class="btn btn-secondary" onclick="back()">Back</button>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->
    </div>

    <!-- Main Script -->
    <?php require "js/Main_js.php" ?>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
</body>
</html>
