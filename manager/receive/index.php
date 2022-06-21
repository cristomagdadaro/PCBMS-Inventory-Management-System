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
        require_once './delivery/delivery_crud.php';
        ?>
        <!-- Page Content Holder -->
        <div id="content">
            <?php
            require_once dirname(__DIR__, 2) . '\layout\navbar\navbar-recieve.php';
            alertbox();
            ?>
            <div id="alertmessagebox">
            </div>
            <div id="change-content">

            </div>
        </div>
        <!-- Page Content Holder -->
        <!--For Toggling the sidebar-->
        <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="/js/popper.min.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <!--For Datatable Important-->
        <script type="text/javascript" src="/js/datatables.min.js"></script>
        <script type="text/JavaScript" src="/js/jquery.dataTables.min.js"></script>
        <script type="text/JavaScript" src="/js/dataTables.bootstrap4.min.js"></script>

        <script type="text/javascript" src="/js/sidebar-toggle.js"></script>
        <script type="text/javascript" src="/js/alertbox.js"></script>
        <script>
            $(document).ready(function() {
                $("#change-content").load(localStorage.getItem('currentpage'));
                WhatTab(localStorage.getItem('type'));
            });

            function NavbarLinkClick(type,url) {
                localStorage.setItem('currentpage', url);
                $("#change-content").load(url);
                localStorage.setItem('type', type);
                WhatTab(type);
            }

            function WhatTab(type){
                if(type == 'supplier'){
                    $('#suppliertablink').addClass('active');
                    $('#producttablink').removeClass('active');
                    $('#deliverytablink').removeClass('active');
                }
                else if (type == 'product'){
                    $('#suppliertablink').removeClass('active');
                    $('#producttablink').addClass('active');
                    $('#deliverytablink').removeClass('active');
                }
                else if (type == 'delivery'){
                    $('#suppliertablink').removeClass('active');
                    $('#producttablink').removeClass('active');
                    $('#deliverytablink').addClass('active');
                }
            }
        </script>

    </div>
</body>

</html>