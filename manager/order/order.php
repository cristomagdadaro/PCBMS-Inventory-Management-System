<div class="btn-group btn-group" role="group">
    <button type="button" class="btn btn-success" id="toggle-neworder-form" onclick="javascript: LoadPage('order_create.php');">
        <div class="d-flex align-items-center" data-toggle="tooltip" data-placement="left" title="Add new order">
            <svg class="bi me-2" width="16" height="16">
                <use xlink:href="#add_icon" />
            </svg>
        </div>
    </button>
</div>
<div class="container-table fluid">
    <div class="row">
        <div id="order-datatable" class="mt-2" style="margin: auto">
            <table id="tableContent" class="table table-bordered nowrap">
                <thead>
                    <tr>
                        <th scope="col" style="width: 30px !important;">Order ID</th>
                        <th scope="col" style="width: 200px !important;">Company</th>
                        <th scope="col" style="width: 40px !important;">Items</th>
                        <th scope="col" style="width: 100px !important;">Ordered Date</th>
                        <th scope="col" style="width: 100px !important;">Contact</th>
                        <th scope="col" style="width: 100px !important;">Status</th>
                        <th scope="col" style="width: 50px !important;">Action</th>
                    </tr>
                </thead>
                <tbody id="order-table-body">
                </tbody>
            </table>
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
        var t = $('#tableContent').DataTable({
            columnDefs: [{
                className: "dt-center",
                targets: [0, 1, 2, 3, 4, 5, 6]
            }]
        });

        $(document).ready(function() {
            Retrieve_Orders();
            $('#ordertablink').addClass('active');
            $('#order-sidebar').addClass('active');
        });

        function ActionButton(ord_id) {
            return '<div class="d-flex justify-content-center">' +
                '<div class="btn-group btn-group-sm" role="group">' +
                `<button type="button" class="btn btn-outline-warning d-flex align-items-center" onclick="javascript: LoadPage('./order_update.php?or_id=${ord_id}')" data-toggle="tooltip" data-placement="top" title="Edit/View details">` +
                '<svg class="bi me-2" width="16" height="16">' +
                '<use xlink:href="#update_icon" />' +
                '</svg>' +
                '</button>' +
                `<button type="button" class="btn btn-outline-danger d-flex align-items-center" onclick="javascript:DeleteProduct(${ord_id});" data-toggle="tooltip" data-placement="right" data-html="true" title="Caution! Remove this order">` +
                '<svg class="bi me-2" width="16" height="16">' +
                '<use xlink:href="#delete_icon" />' +
                '</svg>' +
                '</button>' +
                '</div>' +
                '</div>';
        }

        function Load_Datatable(data) {
            var len = Object.keys(data).length;
            t.clear();
            for (var i = 0; i < len; i++) {
                var company = data[i]['company'];
                var item_count = data[i]['item_count'];
                var or_id = data[i]['or_id']
                var order_date = data[i]['order_date'];
                var phone = data[i]['phone'];
                var status = data[i]['status'];
                var action = ActionButton(or_id);
                t.row.add([or_id, company, item_count, order_date, phone, status, action]).draw();
            }
        }

        function Retrieve_Orders() {
            $.ajax({
                type: "POST",
                url: "./order_crud.php",
                dataType: 'json',
                data: {
                    retrieve: 'order'
                },
                success: function(data) {
                    Load_Datatable(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Notification("Error: Status: " + textStatus + " Message: " + errorThrown, 'danger');
                }
            });
        }

        function DeleteProduct(or_id) {
            if (confirm(`Confirm to delete order no. ${or_id} permanently?`)) {
                $.ajax({
                    type: "POST",
                    url: "./order_crud.php",
                    dataType: 'json',
                    data: {
                        delete: or_id
                    },
                    success: function(data) {
                        Retrieve_Orders();
                        if (data['affected_rows'] != 'undefined' && data['affected_rows'] > 0)
                            Notification("Order removed", 'warning');
                        else
                            Notification(data['error'], 'danger');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Notification("Error: Status: " + textStatus + " Message: " + errorThrown, 'danger');
                    }
                });
            } else
                return false;
        }
    </script>
</div>