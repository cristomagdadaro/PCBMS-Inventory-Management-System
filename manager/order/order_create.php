<div class="container-table fluid">
    <div class="modal-header">
        <h4 class="modal-title" id="newordertitle">New Order</h4>
        <h4><?php session_start();
            echo date('Y-m-d h:i A'); ?></h4>
    </div>
    <form id="addUpdateOrderForm" class="box" method="POST">
        <div class="modal-body" style="margin: auto; padding:0;">
            <div class="container-fluid" style="padding: 0px;">
                <div class="row" style="padding: 0px; margin: 0px;">
                    <div class="input-group input-group-sm mb-3 col-sm-5">
                        <label class="col-form-label" for="company">Company<span style="color:red">*</span></label>
                        <select name="company" id="company_form" class="form-control-sm" onchange="javascript: orderSupplierClick(this);" style="text-overflow: ellipsis;" required>
                            <option value="" hidden>Choose</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mb-3 col-5">
                        <label class="col-form-label">Contact Person</label>
                        <input class="form-control-plaintext text-left" value="None" type="text" id="dealer_name" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-3 col">
                        <label class="col-form-label">&nbsp;</label>
                        <div class="form-control-plaintext">
                            <div class="btn-group btn-group-sm d-flex float-right" role="group">
                                <button type="reset" id="reset-neworder-form-btn" class="btn btn-sm btn-secondary d-flex align-items-center" onclick="javascript:ClearTable();" data-toggle="tooltip" data-placement="right" title="Remove all the inputted data">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#trash_icon" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" style="padding: 0px; margin: 0px;">
                    <table id="item-table-list" class="table nowrap cell-border" name="item-list[]">
                        <thead>
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Product Name<span style="color:red">*</span></th>
                                <th scope="col">Unit</th>
                                <th scope="col">Quantity<span style="color:red">*</span></th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div clas="col" style="padding: 0px; margin: 0px;">
                    Order by:
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label text-right text-truncate" id="personnel-form"><?php echo $_SESSION['name'] ?></label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control-plaintext text-left" id="personnel_id_form" name="personnel" value="<?= $_SESSION["user"] ?>" readonly />
                        </div>
                        <label class="col-sm-1 col-form-label text-right">Date:</label>
                        <div class="col-sm-3">
                            <input class="form-control-plaintext text-left" type="text" id="date_order_form" name="date_order" value="<?php echo date("Y-m-d"); ?>" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label text-right text-truncate">Status<span style="color:red">*</span></label>
                        <div class="col-sm-3">
                            <select name="status" id="status_form" class="form-control-sm" required>
                                <option value="" selected hidden>choose</option>
                                <option value="Pending">Pending</option>
                                <option value="Received">Received</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" modal-footer">
            <div class="row">
                <div class="col">
                    <input type="button" id="close-neworder-form-btn" onclick="javascript: LoadPage('./order.php');" class="btn btn-sm float-right btn-danger" value="Close" />
                </div>
                <div class="col">
                    <input type="button" id="neworder-form-btn" class="btn btn-sm btn-primary btn-block mb-4" name="neworder" value="Submit" />
                </div>
            </div>
        </div>
    </form>
    <!-- Page Content Holder -->
</div>
<!--For Toggling the sidebar-->
<script type=" text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/js/popper.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<!--For Datatable Important-->
<script type="text/javascript" src="/js/datatables.min.js"></script>
<script type="text/JavaScript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/JavaScript" src="/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript" src="/js/sidebar-toggle.js"></script>
<script type="text/javascript" src="order.js"></script>
<script type="text/javascript" src="/js/alertbox.js"></script>
<script type="text/javascript">
    var option_str = "<option value='' hidden>Choose</option>";
    $(document).ready(function() {
        Retrieve_Suppliers();
        Retrieve_Products();
        $('#ordertablink').addClass('active');
        $('#order-sidebar').addClass('active');
    });

    var t = $('#item-table-list').DataTable({
        searching: false,
        paging: false,
        info: false,
        ordering: false,
        deferRender: true,
        columnDefs: [{
            className: "dt-center",
            targets: [0, 1, 2, 3, 4]
        }]
    });

    function Dropdown_Suppliers(data) {
        var len = Object.keys(data).length;
        var supplier_drop = document.getElementById('company_form');
        for (var i = 0; i < len; i++) {
            var option = document.createElement("option");
            option.text = data[i]['company'];
            option.value = data[i]['supp_id'];
            supplier_drop.add(option);
        }
    }

    function Retrieve_Suppliers() {
        $.ajax({
            type: "POST",
            url: "./order_crud.php",
            dataType: 'json',
            data: {
                getsuppliers: true
            },
            success: function(data) {
                Dropdown_Suppliers(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Notification("Error: " + textStatus + " Message: " + errorThrown, 'danger');
            }
        });
    }

    function Retrieve_Products() {
        $.ajax({
            type: "POST",
            url: "./order_crud.php",
            dataType: 'json',
            data: {
                getproducts: true
            },
            success: function(data) {
                var len = Object.keys(data).length;
                if (len) {
                    for (var i = 0; i < len; i++) {
                        option_str += `<option value='${data[i]['prod_id']}'>${data[i]['prod_name']}</option>`;
                    }
                    AddRow();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Notification("Error: " + textStatus + " Message: " + errorThrown, 'danger');
            }
        });
    }

    $(document).ready(function() {
        $("#neworder-form-btn").click(function() {
            var company = $('#company_form').find(':selected').val();
            var status = $('#status_form').find(':selected').val();
            var user_id = $('#personnel_id_form').val();
            var date = $('#date_order_form').val();
            var prod_name = $("select[name='product_id[]']").map(function() {
                return $(this).val();
            }).get();
            var quantity = $("input[name='quantity[]']").map(function() {
                return $(this).val();
            }).get();
            if (!isEmpty(company) && !isEmpty(status) && !isEmpty(user_id) && !isEmpty(date)) {
                $.ajax({
                    url: 'order_crud.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        company: company,
                        status: status,
                        personnel: user_id,
                        date_order: date,
                        product_id: prod_name,
                        quantity: quantity,
                        new: 'order'
                    },
                    success: function(response) {
                        console.log(response);
                        if (Object.keys(response).length && response['affected_row'] > 0) {
                            Notification('New order list created', 'success');
                            $('#close-neworder-form-btn').click();
                        } else {
                            Notification(response['error'], 'danger');
                            $('#company_form').focus();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Notification("Error: " + textStatus + " Message: " + errorThrown, 'danger');
                    }
                });
            } else {
                Notification('Please fill all the required fields', 'warning');
            }
        });

        function isEmpty(strIn) {
            if (strIn === undefined) {
                return true;
            } else if (strIn == null) {
                return true;
            } else if (strIn == "") {
                return true;
            } else {
                return false;
            }
        }
    });
</script>