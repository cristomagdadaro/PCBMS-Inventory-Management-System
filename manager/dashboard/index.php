<?php
require_once '../user/user_crud.php';
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
    <link rel="stylesheet" href="/css/sidebar-style-sheet.css">
    <link rel="stylesheet" href="/css/main-style-sheet.css">
    <link rel="stylesheet" href="/css/alertbox.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/sweetalert2.all.min.js"></script>
    <title>Dashboard | PCBMS</title>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar Holder -->
        <?php
        require_once  "../../layout/sidebar/index.php";
        require_once  "../../php/icon.php";
        ?>
        <!-- Page Content Holder -->
        <div id="content">
            <?php
            require_once '../../layout/navbar/navbar-dashboard.php';
            require_once '../../php/messagebox.php';
            alertbox();
            ?>

            <p class="h4 text-title">Summary</p>

            <div id="remaining-products-container">
                <label class="col-form-label">Consigned Product Stock</label>
                <table id="remaining-products" class="table table-sm container-table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Total</th>
                            <th scope="col">Remain</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div id="sales-summary-container">
                <label class="col-form-label">Monthly Sales Report</label>
                <table id="sales-summary" class="table table-sm container-table">
                    <tbody>
                        <tr>
                            <th scope="row">No. of sold items</th>
                            <td id="num_sales">0</td>
                        </tr>
                        <tr>
                            <th scope="row">Revenue <span style="float: right;">₱</span></th>
                            <td id="revenue">0</td>
                        </tr>
                        <tr>
                            <th scope="row">Profit <span style="float: right;">₱</span></th>
                            <td id="profit">0</td>
                        </tr>
                        <tr>
                            <th scope="row">Cost <span style="float: right;">₱</span></th>
                            <td id="cost">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <!-- Page Content Holder -->
    </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dashboard-sidebar').addClass('active');
            $('#hometablink').on('click', function() {
                location.reload();
            });

            LoadProductSummary();
            LoadSalesSummary()
        });

        var delivery;

        function LoadProductSummary() {
            var table = document.getElementById('remaining-products').getElementsByTagName('tbody')[0];
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'dashboard.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
            xhr.onload = function() {
                if (this.status == 200) {
                    console.log(this.responseText);
                    delivery = JSON.parse(this.responseText);
                    var len = Object.keys(delivery).length;
                    var remaining = 0;
                    if (len <= 0) {
                        table.insertRow(i).innerHTML = "<td colspan='4' style='text-align: center'>Empty</td>";
                    }

                    for (var i = 0; i < len; i++) {
                        var row = table.insertRow(i);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        cell1.innerText = delivery[i].prod_name;
                        cell2.innerText = delivery[i].initial_qty;
                        remaining = delivery[i].initial_qty - delivery[i].total_qty_sold;
                        remaining = remaining > 0 ? remaining : 0;
                        cell3.innerText = remaining;
                        if ((remaining / delivery[i].initial_qty) <= 0)
                            cell4.innerText = 'Out of Stock';
                        else if ((remaining / delivery[i].initial_qty) <= 0.15)
                            cell4.innerText = 'Warning';
                        else if ((remaining / delivery[i].initial_qty) <= 0.30)
                            cell4.innerText = 'Low stock';
                        else
                            cell4.innerText = 'Normal';
                    }
                }
            }
            xhr.send('productsummary=1');
        }

        function LoadSalesSummary() {
            var table = document.getElementById('sales-summary').getElementsByTagName('tbody')[0];
            var num_sales = document.getElementById('num_sales');
            var revenue = document.getElementById('revenue');
            var profit = document.getElementById('profit');
            var cost = document.getElementById('cost');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'dashboard.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
            xhr.onload = function() {
                if (this.status == 200) {
                    delivery = JSON.parse(this.responseText);
                    var len = Object.keys(delivery).length;
                    var _revenue = 0;
                    var _profit = 0;
                    var _cost = 0;
                    var _num_sales = 0;

                    for (var i = 0; i < len; i++) {
                        _num_sales += parseInt(delivery[i].num_sales);
                        _revenue += parseInt(delivery[i].total_revenue);
                        _profit += parseFloat(delivery[i].total_revenue * 0.12);
                        _cost += parseFloat(_revenue - _profit);
                    }

                    num_sales.innerText = _num_sales;
                    revenue.innerText = _revenue;
                    profit.innerText = _profit;
                    cost.innerText = _cost;
                }
            }
            xhr.send('salessummary=1');
        }
    </script>
</body>

</html>