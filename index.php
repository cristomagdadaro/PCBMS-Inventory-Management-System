<!DOCTYPE html>
<html lang="en">
<?php
session_start();
$_SESSION["session_status"] = "not authorized";
if (isset($_GET['logout']) && $_GET['logout'] == 1)
    include 'php/deauthenticate.php';
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main-style-sheet.css">
    <link rel="stylesheet" href="css/alertbox.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <title>Welcome | PCBMS</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background: rgb(5,86,3) !important;">
        <a class="navbar-brand active" href="index.php">PCBMS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a id="login-btn" class="nav-item nav-link" data-toggle="modal" data-target="#loginModal">Login</a>
                <a id="reg-btn" class="nav-item nav-link" data-toggle="modal" data-target="#registrationModal">Register</a>
            </div>
        </div>
    </nav>
    <div id="alertmessagebox">

    </div>
    <?php
    require "login/index.php";
    require "register/index.php";
    require "php/messagebox.php";
    alertbox();
    ?>

    <div class="big-vsu-logo" id="vsu-logo">
        <img src="img/vsu.png" alt="Visayas State University Logo">
    </div>
    <div class="big-vsu-logo" id="dcst-logo">
        <img src="img/dcst.png" alt="Department of Computer Science and Technology Logo">
    </div>
    <div class="front-msg align-middle" id="front-msg">
        <h1>Pasalubong Center</h1>
        <h2>Business Management System</h2>
    </div>

    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
    <script src="/js/alertbox.js"></script>
    <script>
        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#register-form-btn").click(function() {
                var fname = $('#fname').val().trim();
                var mname = $('#mname').val().trim();
                var lname = $('#lname').val().trim();
                var position = $('#position').find(':selected').val();
                var username = $('#username').val().trim();
                var password = $('#password').val().trim();
                if (fname != "" && lname != "" && position != "" && username != "" && password != "") {
                    $.ajax({
                        url: '/manager/user/user_crud.php',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            fname: fname,
                            mname: mname,
                            lname: lname,
                            designation: position,
                            username: username,
                            password: password,
                            register: true
                        },
                        success: function(response) {
                            console.log(response);
                            if (Object.keys(response).length && response['inserted_id'] > 0) {
                                Notification('Successfully registered', 'success');
                                $('#close_reg').click();
                            }else{
                                Notification(response['error'], 'danger');
                                $('#username').focus();
                            }
                        },
                        error: function(e){
                            console.log(e);
                        }
                    });
                }else{
                    Notification('Please fill all the required fields', 'warning');
                }
            });

            $("#login-form-btn").click(function() {
                var username = $("#username-form").val().trim();
                var password = $("#password-form").val().trim();
               
                if (username != "" && password != "") {
                    $.ajax({
                        url: '/manager/user/user_crud.php',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            username: username,
                            password: password,
                            login: true
                        },
                        success: function(response) {
                            console.log(response);
                            if (Object.keys(response).length && response[0]['user_id'] > 0) {
                                if (response[0]['designation'] == 'Store Manager')
                                    window.location.href = "/manager/dashboard/";
                                else if (Object.keys(response).length && response[0]['designation'] == 'Cashier')
                                    window.location.href = "/cashier/";
                            } else {
                                Notification('No username or password found', 'danger');
                            }
                        },
                        error: function(e){
                            console.log(e);
                        }
                    });
                }else{
                    Notification('Please fill all the required fields', 'warning');
                }
            });
        });
    </script>
</body>

</html>