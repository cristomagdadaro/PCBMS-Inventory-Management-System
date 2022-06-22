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
    <link rel="stylesheet" href="/css/cashier-style.css">
    <link rel="stylesheet" href="/css/alertbox.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/sweetalert2.all.min.js"></script>
    <title>Cashier | PCBMS</title>
</head>

<body>
    <div id="cashier-content">
        <?php
        session_start();
        require_once dirname(__DIR__, 1) . '\php\icon.php';
        require_once dirname(__DIR__, 1) . '\manager\receive\product\product_crud.php';
        require_once dirname(__DIR__, 1) . '\cashier\sales_crud.php';
        require_once dirname(__DIR__, 1) . '\php\messagebox.php';
        require './navbar/navbar.php';
        alertbox();
        ?>
        <div id="alertmessagebox">
        </div>
        <div class="container-table fluid">

            <div class="row">
                <div id="transacttion-datatable" class="mt-2" style="margin: auto">
                    <table id="tableContent" class="table table-bordered nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Sale ID</th>
                                <th scope="col">Item No.</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                            </tr>
                        </thead>
                        <tbody id="delivery-table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    <script>
        var t = $('#tableContent').DataTable();
        GetTransactions();

        function GetTransactions() {
            $.ajax({
                type: "POST",
                url: "./sales_crud.php",
                dataType: 'json',
                data: {
                    transaction: true
                },
                success: function(data) {
                    console.log(data);
                    if (typeof data['error'] == 'undefined') {
                        LoadData(data);
                    } else {
                        Notification(data['error'], 'danger');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    Notification("Error: Status: " + textStatus + " Message: " + errorThrown, 'danger');
                }
            });
        }

        function LoadData(data) {
            var len = Object.keys(data).length;
            t.clear();
            for (var i = 0; i < len; i++) {
                var item_no = data[i]['item_no']
                var sale_id = data[i]['sale_id'];
                var prod_name = data[i]['prod_name'];
                var qty_sold = data[i]['qty_sold'];
                var amount_sold = data[i]['amount_sold'];
                var date_issued = new Date(data[i]['date_issued']);
                var customer = data[i]['customer'];
                var address = data[i]['address'];
                t.row.add([item_no, sale_id, prod_name, qty_sold, amount_sold, customer, date_issued.toDateString(), date_issued.toLocaleString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                })]).draw();
            }
        }
    </script>
</body>

</html>