<?php
require_once dirname(__DIR__, 1) . '\delivery\delivery_crud.php';
session_start();
?>
<div class="container-table fluid">
    <div class="modal-header">
        <h4 class="modal-title" id="newdeliverytitle">New Delivery</h4>
        <h4><?php echo date('Y-m-d H:i A') ?></h4>
    </div>
    <form id="addUpdateDeliveryForm" class="box" method="POST">
        <div class="modal-body" style="margin: auto; padding:0;">
            <div class="container-fluid" style="padding: 0px;">
                <div class="row" style="padding: 0px; margin: 0px;">
                    <div class="input-group input-group-sm mb-3 col-sm-5">
                        <label class="col-form-label" for="company">Company<span style="color:red">*</span></label>
                        <select name="company" id="company_form" class="form-control-sm" onchange="javascript: deliverySupplierClick(this);" style="text-overflow: ellipsis;" required>
                            <option value='' hidden>Choose</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mb-3 col-sm-5">
                        <label class="col-form-label">Contact Person</label>
                        <input class="form-control-plaintext text-left" value="None" type="text" id="dealer_name" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-3 col">
                        <label class="col-form-label">&nbsp;</label>
                        <div class="form-control-plaintext">
                            <div class="btn-group btn-group-sm d-flex float-right" role="group">
                                <button type="reset" id="reset-newdelivery-form-btn" class="btn btn-sm btn-secondary d-flex align-items-center" onclick="javascript:ClearTable();" data-toggle="tooltip" data-placement="right" title="Remove all the inputted data">
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
                                <th scope="col">Particulars</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Unit Price<span style="color:red">*</span></th>
                                <th scope="col">Interest<span style="color:red">*</span></th>
                                <th scope="col">Sell Price<span style="color:red">*</span></th>
                                <th scope="col">Quantity<span style="color:red">*</span></th>
                                <th scope="col">Amount<span style="color:red">*</span></th>
                                <th scope="col">Expiry Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div clas="col" style="padding: 0px; margin: 0px;">
                    Received by:
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label text-right text-truncate" id="personnel-form"><?php echo $_SESSION['name'] ?></label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control-plaintext text-left" id="personnel_id_form" name="personnel" value="<?= $_SESSION["user"] ?>" readonly />
                        </div>
                        <label class="col-sm-1 col-form-label text-right">Date:</label>
                        <div class="col-sm-3">
                            <input class="form-control-plaintext text-left" type="text" id="date_delivery_form" name="date_delivery" value="<?php echo date("Y-m-d"); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" modal-footer">
            <div class="row">
                <div class="col">
                    <input type="button" id="close-newdelivery-form-btn" onclick="javascript: NavbarLinkClick('delivery','./delivery/delivery.php');" class="btn btn-sm float-right btn-danger" value="Close" />
                </div>
                <div class="col">
                    <input type="button" id="newdelivery-form-btn" class="btn btn-sm btn-primary btn-block mb-4" name="newdelivery" value="Submit" />
                </div>
            </div>
        </div>
    </form>
    <!-- Page Content Holder -->
</div>
<script src="./delivery/delivery.js"></script>
<script>
    var t = $('#item-table-list').DataTable({
        searching: false,
        paging: false,
        info: false,
        ordering: false,
        deferRender: true,
        columnDefs: [{
            className: "dt-center",
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        }]
    });
    
    $(document).ready(function() {
        Retrieve_Suppliers();
        Retrieve_Products();
        $('#deliverytablink').addClass('active');
        $('#receive-sidebar').addClass('active');
        localStorage.setItem('currentpage', './delivery/delivery.php');
    });

    $(document).ready(function() {
        $("#newdelivery-form-btn").click(function() {
            var _company = $('#company_form').find(':selected').val();
            var personnel = $('#personnel_id_form').val();
            var date_delivery = $("#date_delivery_form").val();
            var particulars = $("input[name='particulars[]']").map(function() {
                return $(this).val();
            }).get();
            var unit = $("input[name='unit[]']").map(function() {
                return $(this).val();
            }).get();
            var unit_price = $("input[name='unit_price[]']").map(function() {
                return $(this).val();
            }).get();
            var selling_price = $("input[name='selling_price[]']").map(function() {
                return $(this).val();
            }).get();
            var amount = $("input[name='amount[]']").map(function() {
                return $(this).val();
            }).get();
            var interest = $("input[name='interest[]']").map(function() {
                return $(this).val();
            }).get();
            var date_expiry = $("input[name='date_expiry[]']").map(function() {
                return $(this).val();
            }).get();
            var product_id = $("select[name='product_id[]']").map(function() {
                return $(this).val();
            }).get();
            var quantity = $("input[name='quantity[]']").map(function() {
                return $(this).val();
            }).get();
            if (!isEmpty(_company) && !isEmpty(personnel) && !isEmpty(unit) && !isEmpty(unit_price) && !isEmpty(selling_price) && !isEmpty(amount) && !isEmpty(interest) && !isEmpty(date_expiry) && !isEmpty(product_id) && !isEmpty(quantity)) {
                $.ajax({
                    url: './delivery/delivery_crud.php',
                    type: 'post',
                    //dataType: 'json',
                    data: {
                        personnel: personnel,
                        particulars: particulars,
                        unit: unit,
                        unit_price: unit_price,
                        selling_price: selling_price,
                        amount: amount,
                        interest: interest,
                        date_expiry: date_expiry,
                        date_delivery: date_delivery,
                        product_id: product_id,
                        quantity: quantity,
                        company: _company,
                        new: 'delivery'
                    },
                    success: function(response) {
                        alert(response);
                        console.log(response);
                        if (Object.keys(response).length && response['affected_row'] > 0) {
                            Notification('New delivery list created', 'success');
                            $('#close-newdelivery-form-btn').click();
                        } else {
                            Notification(response['error'], 'danger');
                            $('#company_form').focus();
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            } else {
                Notification('Please fill all the required fields', 'warning');
            }});

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