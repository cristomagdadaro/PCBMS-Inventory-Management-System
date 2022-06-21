<?php
require_once dirname(__DIR__, 1) . '\user\user_crud.php';
User::Check_Permission(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.min.css">
    <!-- Form CSS -->
    <link type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/css/datatables.min.css" />
    <link rel="stylesheet" href="/css/sidebar-style-sheet.css">
    <link rel="stylesheet" href="/css/main-style-sheet.css">
    <link rel="stylesheet" href="/css/alertbox.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/sweetalert2.all.min.js"></script>
    <title>Deliveries | PCBMS</title>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <?php
        require_once dirname(__DIR__, 2) . '\php\icon.php';
        require_once dirname(__DIR__, 2) . '\layout\sidebar\index.php';
        require_once dirname(__DIR__, 2) . '\php\messagebox.php';
        ?>
        <!-- Page Content Holder -->
        <div id="content">

            <?php
            require_once dirname(__DIR__, 2) . '\layout\navbar\navbar-order.php';
            require_once 'order_crud.php';
            alertbox();
            ?>
            <div id="alertmessagebox">
            </div>
            <div id="change-content">
            </div>
            <script>
                $(document).ready(function() {
                    $("#change-content").load(localStorage.getItem('currentpage'));
                });

                function LoadPage(url){
                    localStorage.setItem('currentpage', url);
                    $("#change-content").load(url);
                }
            </script>
        </div>
    </div>
</body>

</html>