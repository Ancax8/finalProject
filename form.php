<?php
include "connect.php";
include "loginregis.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/css/login.css">

</head>
<body>
    <div class="LOGnav">
        <div class="login active" id="login"> 
            <form method="POST" action="form.php">
                <h3 id="judul">Login Form</h3>
                <label for="nama">username/email</label>
                <input type="text" placeholder="Name" name="nama" required>
                <div class="pass_box">
                    <label for="pwd">Password</label>
                    <input type="password" placeholder="Password" name="pwd" id="pwd" required>
                    <img src="asset/gambar/closeEye.png" alt="closeEye" id="closeEye">
                </div>
                <button id="gas" type="submit" name="gas"> GAS</button>
                <p>You dont have account yet?  <a href="#"onclick="showForm('register', 'login')">Register</a></p>
            </form>
        </div>
        <div class="login" id="register">
            <form method="POST" action="form.php">
                <h3>Register Form</h3>
                <input type="text" placeholder="Nama Lengkap" id="nma" name="nma" required>
                <p id="nama">   </p>
                <input type="text" placeholder="username" name="nma3" id="nma3" name="nma3" required>   
                <input type="tel" placeholder="telephone" name="tlp" id="tlp" name="tlp" maxlength="15" required>
                 <input type="email" placeholder="email" id="mail" name="mail" required>
                <input type="text" placeholder="Password" id="pwd1" name="pw" required>
                <p id="alert"></p>
                <input type="text" placeholder="Password" id="pwd2" required>
                <p id="confirm"></p>
                <button type="submit" name="submit"> GAS</button>
                <p>Remember your acount?  <a href="#" onclick="showForm('login', 'login')">Sing Up</a></p>
            </form>
        </div>
        <div class="login active" id="newPassword">
            <form>
                <h3 class="newPassword">New Password</h3>
                <input type="password" name="" id="" placeholder="newPassword">
                <input type="password" name="" id="" placeholder="newPassword">
                <button type="submit" id="" name=""> Create </button>
            </form>
        </div>
    </div>

     <?php if ($alert != null) { ?>
    <script>
        const dataAlert = <?= json_encode($alert) ?>;
    </script>
    <?php } ?>

    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="asset/js/login.js"></script>


</body>
</html>