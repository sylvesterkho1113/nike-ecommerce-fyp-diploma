<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/header_logo.png">
    <title>About Us</title>

    <style>
        #main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .member {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            width: 50%;
            max-width: 70%;
            padding: 20px;
        }

        fieldset {
            border: none;
            margin: 0;
            padding: 0;
        }

        legend {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .profile-pic {
            float: left;
            margin-right: 20px;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        li span {
            font-weight: bold;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <?php include 'header.php' ?>

    <div id="main">
        <div class="member kwc">
            <fieldset>
                <legend>KHO WEI CONG</legend>
                <img src="image/kwc.png" alt="KHO WEI CONG" class="profile-pic">
                <ul>
                    <li><span>Student ID</span>: 1211211485</li>
                    <li><span>Student Email</span>: 1211211485@student.mmu.edu.my</li>
                    <li><span>Contact Number</span>: 017-7833 558</li>
                    <li>Team Leader for this Final Year Project.</li>
                </ul>
            </fieldset>
        </div>

        <div class="member tcy">
            <fieldset>
                <legend>TEE CHIN YEAN</legend>
                <img src="image/tcy.jpg" alt="TEE CHIN YEAN" class="profile-pic">
                <ul>
                    <li><span>Student ID</span>: 1211208756</li>
                    <li><span>Student Email</span>: 1211208756@student.mmu.edu.my</li>
                    <li><span>Contact Number</span>: 011-1091 5139</li>
                    <li>Team Member for this Final Year Project.</li>
                </ul>
            </fieldset>
        </div>

        <div class="member tsh">
            <fieldset>
                <legend>TEY SOON HONG</legend>
                <img src="image/tsh.png" alt="TEY SOON HONG" class="profile-pic">
                <ul>
                    <li><span>Student ID</span>: 1211209543</li>
                    <li><span>Student Email</span>: 1211209543@student.mmu.edu.my</li>
                    <li><span>Contact Number</span>: 011-1070 3767</li>
                    <li>Team Member for this Final Year Project.</li>
                </ul>
            </fieldset>
        </div>
    </div>

    <?php include 'footer.php' ?>
</body>
</html>
