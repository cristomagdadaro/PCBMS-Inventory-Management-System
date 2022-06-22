<?php
require_once dirname(__DIR__, 1) . '\delivery\delivery_crud.php';
?>

<div class="container-table fluid">
    <div class="modal-header">
        <h4 class="modal-title" id="newdeliverytitle">Update Delivery</h4>
        <h4><?php session_start();
            echo date('Y-m-d h:i A') ?></h4>
    </div>
    <form id="addUpdatedeliveryForm" class="box" method="POST">
        <div class="modal-body" style="margin: auto; padding:0;">
            <div class="container-fluid" style="padding: 0px;">
                <div class="row" style="padding: 0px; margin: 0px;">
                    <div class="input-group input-group-sm mb-3 col-sm-5">
                        <label class="col-form-label" for="company">Company<span style="color:red">*</span></label>
                        <select name="company" id="company_form" class="form-control-sm" onchange="javascript: deliverySupplierClick(this);" style="text-overflow: ellipsis;" required>
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
                                <button type="button" id="discard-newdelivery_form_btn" class="btn btn-sm btn-warning d-flex align-items-center" onclick="javascript: dicardChanges();" data-toggle="tooltip" data-placement="left" title="Undo all changes">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#undo_icon" />
                                    </svg>
                                </button>
                                <button type="reset" id="reset-newdelivery_form_btn" class="btn btn-sm btn-secondary d-flex align-items-center" onclick="javascript:ClearTable();" data-toggle="tooltip" data-placement="right" title="Remove all the inputted data">
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
                            <input class="form-control-plaintext text-left" type="text" id="date_deliver_form" name="date_delivery" value="<?php echo date("Y-m-d"); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col">
                    <input type="button" id="close-newdelivery_form_btn" onclick="javascript: NavbarLinkClick('delivery','./delivery/delivery.php');" class="btn btn-sm float-right btn-danger" value="Close" />
                </div>
                <div class="col">
                    <input type="button" id="newdelivery_form_btn" class="btn btn-sm btn-success btn-block mb-4" name="updatedelivery" value="Submit" />
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
<script type="text/javascript" src="/manager/receive/delivery/delivery.js"></script>
<script type="text/javascript" src="/js/alertbox.js"></script>
<script type="text/javascript">
    var option_str = "<option value='' hidden>Choose</option>";
    $(document).ready(function() {
        Retrieve_Suppliers();
        Retrieve_Products();
        $('#deliverytablink').addClass('active');
        $('#delivery-sidebar').addClass('active');
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

    var company = document.getElementById("company_form");
    var contact_person = document.getElementById("dealer_name");
    var personnel = document.getElementById("personnel-form");
    var personnel_id = document.getElementById("personnel_id_form");
    var d_date = document.getElementById("date_deliver_form");
    var item_id, product, quantity, unit, action;


    $(document).ready(function() {
        var updatebtn = document.getElementById("newdelivery_form_btn");
        var xhr = new XMLHttpRequest();

        var cp_id = <?php echo $_GET["cp_id"] ?>;

        xhr.open('POST', './delivery/delivery_crud.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhr.onload = function() {
            if (this.status == 200) {
                try {
                    var delivery = JSON.parse(this.responseText);
                    console.log(delivery);
                    LoadDataTable(delivery);
                } catch (e) {
                    Notification(e, 'danger')
                }
            }
        }
        xhr.send("getconsigned=" + cp_id);

        updatebtn.value = "Save Changes";
        updatebtn.name = 'updatedelivery';
    });

    function LoadDataTable(delivery) {
        company.value = delivery[0].supp_id;
        contact_person.value = delivery[0].contact_person + ' - ' + delivery[0].phone;
        personnel.textContent = delivery[0].personnel;
        personnel_id.value = delivery[0].user_id;
        d_date.value = delivery[0].date_delivered;

        for (let i = 0; i < delivery.length; i++) {
            item_id = '<input type="text" id="item_id_form" value="' + delivery[i].item_id + '" class="form-control-plaintext text-center" name="item_id[]" readonly/>';
            particulars = '<input type="text" id="particulars_form" value="' + delivery[i].particulars + '" class="form-control-sm" name="particulars[]" />';
            unit = '<input type="text" id="unit_form" value="' + delivery[i].unit + '" class="form-control-sm text-center" name="unit[]" readonly style="cursor: not-allowed;"/>';
            unit_price = '<input type="number" value="' + delivery[i].unit_price + '" step="0.01" class="form-control-sm" id="unit_price_form" name="unit_price[]" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);" aria-label="Amount (to the nearest dollar)" required>';
            sell_price = '<input type="number" value="' + delivery[i].selling_price + '" step="0.01" class="form-control-sm text-center" style="cursor: not-allowed;" id="selling_price_form" name="selling_price[]" aria-label="Amount (to the nearest dollar)" required readonly>';
            quantity = '<input type="number" value="' + delivery[i].quantity + '" id="quantity_form" class="form-control-sm" name="quantity[]" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);" required />';
            amount = '<input type="number" value="' + delivery[i].amount + '" step="0.01" class="form-control-sm" id="amount_form" name="amount[]" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);" aria-label="Amount (to the nearest dollar)" required>';
            interest = '<input type="number" value="' + delivery[i].interest + '" step="0.01" class="form-control-sm" id="interest_form" name="interest[]" value="0.12" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);">';
            expiry = '<input type="date" value="' + delivery[i].expiry_date + '" class="form-control-sm" id="date_expiry_form" name="date_expiry[]">'
            action = '<div class="d-flex justify-content-center"><div class="btn-group btn-group-sm" role="group"><button type="button" onclick="javascript: AddRow();" class="addRow btn btn-outline-primary d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#add_row_icon" /></svg></button><button type="button" onclick="javascript: RemoveRow(this);" class="btn btn-outline-danger d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#remove_row_icon" /></svg></button></div></div>';

            t.row.add([item_id, Options_Str(option_str, delivery[i].prod_id, delivery[i].prod_name), particulars, unit, unit_price, interest, sell_price, quantity, amount, expiry, action]).draw(false);
            counter++;
            size++;
        }
    }

    $("#newdelivery_form_btn").on('click', function() {
        var cp_id = <?php echo $_GET["cp_id"] ?>;
        var company = $('#company_form').find(':selected').val();
        var personnel = $('#personnel_id_form').val();
        var date_delivery = $("#date_delivery_form").val();
        var item_id = $("input[name='item_id[]']").map(function() {
            return $(this).val();
        }).get();
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
        if (!isEmpty(company) && !isEmpty(personnel) && !isEmpty(unit) && !isEmpty(unit_price) && !isEmpty(selling_price) && !isEmpty(amount) && !isEmpty(interest) && !isEmpty(date_expiry) && !isEmpty(product_id) && !isEmpty(quantity)) {
            $.ajax({
                url: './delivery/delivery_crud.php',
                type: 'post',
                dataType: 'json',
                data: {
                    item_id: item_id,
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
                    company: company,
                    cp_id: cp_id,
                    updatedelivery: true
                },
                success: function(response) {
                    if (typeof response['error'] == 'undefined') {
                        if (response['affected_rows'] > 0) {
                            Notification('Updated delivery list info', 'success');
                        } else {
                            Notification('No changes made', 'info');
                        }
                        $('#close-newdelivery_form_btn').click();
                    } else {
                        Notification(response['error'], 'danger');
                        $('#company_form').focus();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
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
</script>