<!-- php code -->
<?php
    //upload into sql
    include 'dataconnection.php';

    if(isset($_POST["sign-up"])){
        $un = $_POST["uname"];
        $em = $_POST["email"];
        $pn = $_POST["phone"];
        $pw = $_POST["password"];

        $hashed_password = hash('md5', $pw);

        //Check if the password alreday exists in the database or not
        $check_query = "SELECT * FROM customer WHERE Customer_Email = '$em'";
        $check_result = mysqli_query($conn, $check_query);

        if(mysqli_num_rows($check_result) > 0){
            echo "<script> alert('This email is already in use, Please use a different one.')</script>";
        }
        else{
            $query = "INSERT INTO customer
                    (Customer_Username,
                    Customer_Email,
                    Customer_Password,
                    Customer_Phone_Number)
                    VALUES ('$un', '$em', '$hashed_password', '$pn')";

            if(mysqli_query($conn, $query)){
                ?>
                <script>
                    alert('Welcome, <?php echo $un; ?>');
                    window.location.href = "login.php";
                </script>
                <?php
            }
            else{
                echo "Error: ".$query. "<br>" .mysqli_error($conn);
            }
        }
    }
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Register Form</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/header_logo.png">
</head>

<body>
    <?php include 'header.php' ?>

    <main>
        <!-- breadcrumb area start -->
        <div class="breadcrumb-area breadcrumb-img bg-img" data-bg="assets/img/banner/shop.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <h3 class="breadcrumb-title">REGISTER</h3>
                                <ul class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><i class="fa fa-home"></i></li>
                                    <li class="breadcrumb-item active" aria-current="page">Register</li>
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
                        <!-- Register Content Start -->
                        <div class="col-lg-6">
                            <div class="login-reg-form-wrap sign-up-form">
                                <h4>Singup Form</h4>
                                <form action="" method="post">
                                    <div class="single-input-item">
                                        <input type="text" name="uname" class="information" placeholder="Enter Your Username">
                                    </div>
                                    <div class="single-input-item">
                                        <input type="email" name="email" class="information" placeholder="Enter Your Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                                    </div>
                                    <div class="single-input-item">
                                        <input type="tel" name="phone" class="information" id="phone-number" placeholder="Enter Your Phone Number" required maxlength="13" minlength="12">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                            <input type="password" name="password" class="information" placeholder="Enter Your Password">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                            <input type="password" name="cpassword" class="information" placeholder="Confirm Your Password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4 mt-xxl-0 w-auto h-auto">
                                        <div
                                            data-mdb-alert-init class="alert px-4 py-3 mb-0 d-none"
                                            role="alert"
                                            data-mdb-color="warning"
                                            id="password-alert"
                                            style="font-size:18px; margin-top: 12px;"
                                        >
                                            <ul class="list-unstyled mb-0">
                                                <li class="requirements leng">
                                                    Your password must have at least 8 chars.</li>
                                                <li class="requirements big-letter">
                                                    Your password must have at least 1 capital letter.</li>
                                                <li class="requirements num">
                                                    Your password must have at least 1 number.</li>
                                                <li class="requirements special-char">
                                                    Your password must have at least 1 special char.</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="single-input-item">
                                        <input type="submit" name="sign-up" value="Sign Up" class="signup" id="signupbtn" style="background-color: yellowgreen">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Register Content End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- login register wrapper end -->
    </main>

    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->

    <script>
        //make the placeholder change when user hover the input
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('input[name="phone"]');
            const passrequire = document.querySelector('input[name="password"]');

            phoneInput.addEventListener('focus', function(){
                phoneInput.placeholder = 'e.g. 01X-XXXXXXXX';
            });

            phoneInput.addEventListener('blur', function(){
                phoneInput.placeholder = 'Enter Your Phone Number';
            })

            passrequire.addEventListener('focus', function(){
                passrequire.placeholder = 'Must at least 8 characters, using symbol and number';
            })

            passrequire.addEventListener('blur', function(){
                passrequire.placeholder = 'Enter Your Password';
            })
        });
    </script>

    <script>
        addEventListener("DOMContentLoaded", (event) => {
            // Select all input elements with the class 'information'
            const required = document.querySelectorAll('input');

            // Loop through each input element and set the required attribute
            required.forEach(input => {
                input.setAttribute('required', '');
            });

            // Set limitation fo the password
            const password = document.querySelector('input[name = "password"]');
            const cpassword = document.querySelector('input[name = "cpassword"]');
            const passwordAlert = document.getElementById("password-alert");
            const btn = document.getElementById('signupbtn');
            const requirements = document.querySelectorAll('.requirements');
            const leng = document.querySelector(".leng");
            const bigLetter = document.querySelector(".big-letter");
            const num = document.querySelector(".num");
            const specialChar = document.querySelector(".special-char");

            requirements.forEach((element) => element.classList.add("wrong"));

            password.addEventListener("focus", () => {
                passwordAlert.classList.remove("d-none");

                if(!password.classList.contains("is-valid")) {
                    password.classList.add("is-invalid");
                }
            });

            password.addEventListener("input", () => {
                const value = password.value;
                const LenghtValid = value.length >=8;
                const hasUpperCase = /[A-Z]/.test(value);
                const hasNumber = /\d/.test(value);
                const hasSpecialChar = /[!@#$%^&*()\[\]{}\\|;:'",<.>/?`~]/.test(value);
                const bgcolor = '#7FFFAA';
                const returncolor = '#ffffff'

                leng.classList.toggle("good", LenghtValid);
                leng.classList.toggle("wrong", !LenghtValid);
                bigLetter.classList.toggle("good", hasUpperCase);
                bigLetter.classList.toggle("wrong", !hasUpperCase)
                num.classList.toggle("good", hasNumber);
                num.classList.toggle("wrong", !hasNumber);
                specialChar.classList.toggle("good", hasSpecialChar);
                specialChar.classList.toggle("wrong", !hasSpecialChar);

                const isPasswordValid = LenghtValid && hasUpperCase && hasNumber && hasSpecialChar;

                if(isPasswordValid){
                    password.classList.remove('is-invalid');
                    password.classList.add("is-valid");

                    requirements.forEach((element) => {
                        element.classList.remove("wrong");
                        element.classList.add("good");
                    });

                    passwordAlert.classList.remove("alert-warning");
                    passwordAlert.classList.add("alert-success");

                    document.querySelector('input[name = "password"]').style.backgroundColor = bgcolor;
                    btn.disabled = false;//Enable the button

                    cpassword.addEventListener("input", () => {
                        if(cpassword.value != password.value){
                            document.querySelector('input[name = "cpassword"]').style.backgroundColor = returncolor;
                            btn.disabled = true;
                        }

                        else if(cpassword.value === password.value){
                            document.querySelector('input[name = "cpassword"]').style.backgroundColor = bgcolor;
                            btn.disabled = false;
                        }
                    });
                }

                else{
                    password.classList.remove("is-valid");
                    password.classList.add("is-invalid");
                    passwordAlert.classList.add("alert-warning");
                    passwordAlert.classList.remove("alert-success");
                    document.querySelector('input[name = "password"]').style.backgroundColor = returncolor;
                    btn.disabled = true;
                }
            });

            password.addEventListener("blur", () => {
                passwordAlert.classList.add("d-none");
                
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('phone-number');
            var form = document.querySelector('form');
            var errorMessage = document.createElement('div');
            errorMessage.style.color = 'red';
            phoneInput.parentNode.insertBefore(errorMessage, phoneInput.nextSibling);

            form.addEventListener('submit', function(event) {
                var phoneValue = phoneInput.value.replace(/\D/g, ''); // Remove all non-digit characters

                if (phoneValue.length < 10 || phoneValue.length > 11) {
                    event.preventDefault(); // Prevent form submission
                    errorMessage.textContent = 'Phone number must be 10 or 11 digits long.';
                } else {
                    errorMessage.textContent = ''; // Clear any previous error message
                }
            });

            // Format phone number with hyphens and spaces as the user types
            phoneInput.addEventListener('input', function() {
                var num = phoneInput.value.replace(/\D/g, ''); // Remove all non-digit characters
                var formattedNumber = '';

                if (num.length > 3 && num.length <= 7) {
                    formattedNumber = num.slice(0, 3) + '-' + num.slice(3);
                } else if (num.length > 7) {
                    formattedNumber = num.slice(0, 3) + '-' + num.slice(3, 7) + ' ' + num.slice(7);
                } else {
                    formattedNumber = num;
                }

                phoneInput.value = formattedNumber;
            });
        });
    </script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>



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