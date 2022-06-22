<div class="container-table fluid">
    <div class="modal-header">
        <h4 class="modal-title" id="newordertitle">Update order</h4>
        <h4><?php echo date('Y-m-d h:i A') ?></h4>
    </div>
    <form id="addUpdateorderForm" class="box" method="POST">
        <div class="modal-body" style="margin: auto; padding:0;">
            <div class="container-fluid" style="padding: 0px;">
                <div class="row" style="padding: 0px; margin: 0px;">
                    <div class="input-group input-group-sm mb-3 col-sm-5">
                        <label class="col-form-label" for="company">Company<span style="color:red">*</span></label>
                        <select name="company" id="company_form" class="form-control-sm" onchange="javascript: orderSupplierClick(this);" style="text-overflow: ellipsis;" required>
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
                                <button type="button" id="discard-neworder-form-btn" class="btn btn-sm btn-warning d-flex align-items-center" onclick="javascript: dicardChanges();" data-toggle="tooltip" data-placement="left" title="Undo all changes">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#undo_icon" />
                                    </svg>
                                </button>
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
                    Ordered by:
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
        <div class="modal-footer">
            <div class="row">
                <div class="col">
                    <input type="button" id="close-neworder-form-btn" onclick="javascript: LoadPage('./order.php');" class="btn btn-sm float-right btn-danger" value="Close" />
                </div>
                <div class="col">
                    <input type="button" id="neworder-form-btn" class="btn btn-sm btn-success btn-block mb-4" name="updateorder" value="Submit" />
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

    var company = document.getElementById("company_form");
    var contact_person = document.getElementById("dealer_name");
    var personnel = document.getElementById("personnel-form");
    var personnel_id = document.getElementById("personnel_id_form");
    var o_date = document.getElementById("date_order_form");
    var _status = document.getElementById("status_form");
    var item_id, product, quantity, unit, action;


    $(document).ready(function() {
        var updatebtn = document.getElementById("neworder-form-btn");
        var xhr = new XMLHttpRequest();

        var or_id = <?php echo $_GET["or_id"] ?>;

        xhr.open('POST', 'order_crud.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhr.onload = function() {
            if (this.status == 200) {
                try {
                    var order = JSON.parse(this.responseText);
                    LoadDataTable(order);
                } catch (e) {
                    alert(e);
                }
            }
        }
        xhr.send("or_id=" + or_id);

        updatebtn.value = "Save Changes";
        updatebtn.id = 'updateorder-form-btn';
        updatebtn.name = 'updateorder';
    });

    function LoadDataTable(order) {
        company.value = order[0].supp_id;
        contact_person.value = order[0].contact_person + ' - ' + order[0].phone;
        personnel.textContent = order[0].personnel;
        personnel_id.value = order[0].user_id;
        o_date.value = order[0].order_date;
        _status.value = order[0].status;
        for (let i = 0; i < order.length; i++) {
            item_id = '<input type="text" id="item_id_form" value="' + order[i].item_id + '" class="form-control-plaintext text-center" name="item_id[]" readonly/>';
            unit = '<input type="text" id="unit_form" class="form-control-sm" value="' + order[i].unit + '" name="unit[]" readonly/>';
            quantity = '<input type="number" value="' + order[i].quantity + '" id="quantity_form" class="form-control-sm" name="quantity[]" required />';
            action = '<div class="d-flex justify-content-center"><div class="btn-group btn-group-sm" role="group"><button type="button" onclick="javascript: AddRow();" class="addRow btn btn-outline-primary d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#add_row_icon" /></svg></button><button type="button" onclick="javascript: RemoveRow(this);" class="btn btn-outline-danger d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#remove_row_icon" /></svg></button></div></div>';

            t.row.add([item_id, Options_Str(option_str, order[i].prod_id, order[i].prod_name), unit, quantity, action]).draw(false);
            counter++;
            size++;
        }
    }

    function Options_Str(_options, _prod_id = null, _prod_name = null) {
        if (_prod_id && _prod_name)
            return `<select name="product_id[]" onchange="javascript:orderProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required><option value='${_prod_id}' selected hidden>${_prod_name}</option>${_options}</select >`;
        return `<select name="product_id[]" onchange="javascript:orderProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required>${_options}</select >`;
    }

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
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Notification("Error: " + textStatus + " Message: " + errorThrown, 'danger');
            }
        });
    }

    $("#neworder-form-btn").on('click', function() {
        var company = $('#company_form').find(':selected').val();
        var status = $('#status_form').find(':selected').val();
        var user_id = $('#personnel_id_form').val();
        var date = $('#date_order_form').val();
        var or_id = <?php echo $_GET["or_id"] ?>;
        var prod_name = $("select[name='product_id[]']").map(function() {
            return $(this).val();
        }).get();
        var quantity = $("input[name='quantity[]']").map(function() {
            return $(this).val();
        }).get();
        var item_id = $("input[name='item_id[]']").map(function() {
            return $(this).val();
        }).get();
        if (!isEmpty(company) && !isEmpty(status) && !isEmpty(user_id) && !isEmpty(date) && !isEmpty(or_id)) {
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
                    item_id: item_id,
                    quantity: quantity,
                    or_id: or_id,
                    update: true
                },
                success: function(response) {
                    if (response['errno'] <= 0) {
                        if (response['affected_rows']) {
                            Notification('Updated order list info', 'success');
                        } else {
                            Notification('No changes made', 'info');
                        }
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
</script>