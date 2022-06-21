<?php
require_once dirname(__DIR__, 1) . '\delivery\delivery_crud.php';
?>
<div class="container-table fluid">
    <div class="modal-header">
        <h4 class="modal-title" id="newdeliverytitle">Update Delivery</h4>
        <h4><?php echo date('Y-m-d H:i A') ?></h4>
    </div>
    <form action="./delivery/delivery_crud.php?cp_id=<?= $_GET["cp_id"] ?>" id="addUpdateDeliveryForm" class="box" method="POST">
        <div class="modal-body" style="margin: auto; padding:0;">
            <div class="container-fluid" style="padding: 0px;">
                <div class="row" style="padding: 0px; margin: 0px;">
                    <div class="input-group input-group-sm mb-3 col-sm-5">
                        <label class="col-form-label" for="company">Company<span style="color:red">*</span></label>
                        <select name="company" id="company_form" class="form-control-sm" onchange="javascript: deliverySupplierClick(this);" style="text-overflow: ellipsis;" required>
                            <option value="" selected hidden>choose</option>
                            <?php
                            $result = get_Suppliers();
                            while ($supp = $result->fetch_assoc()) {
                            ?>
                                <option value="<?= $supp['supp_id'] ?>">
                                    <?= $supp['company'] ?></option>
                            <?php
                            }
                            ?>
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
                                <button type="button" id="discard-newdelivery-form-btn" class="btn btn-sm btn-warning d-flex align-items-center" onclick="javascript: dicardChanges();" data-toggle="tooltip" data-placement="left" title="Undo all changes">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#undo_icon" />
                                    </svg>
                                </button>
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
                            <input type="text" class="form-control-plaintext text-left" id="personnel-id-form" name="personnel" value="<?= $_SESSION["user"] ?>" readonly />
                        </div>
                        <label class="col-sm-1 col-form-label text-right">Date:</label>
                        <div class="col-sm-3">
                            <input class="form-control-plaintext text-left" type="text" id="date_delivery_form" name="date_delivery" value="<?php echo date("Y-m-d"); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col">
                    <input type="button" id="close-newdelivery-form-btn" onclick="javascript: NavbarLinkClick('delivery','./delivery/delivery.php');" class="btn btn-sm float-right btn-danger" value="Close" />
                </div>
                <div class="col">
                    <input type="submit" id="newdelivery-form-btn" class="btn btn-sm btn-success btn-block mb-4" name="updatedelivery" value="Submit" />
                </div>
            </div>
        </div>
    </form>
    <!-- Page Content Holder -->
</div>
<script type="text/javascript" src="./delivery/delivery.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#deliverytablink').addClass('active');
        $('#receive-sidebar').addClass('active');
        localStorage.setItem('currentpage', './delivery/delivery.php');
    });

    function CloseBtnClick(url) {
        localStorage.setItem('currentpage', url);
        $("#content").load(url);
    }

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

    var _product = '<select name="product_id[]" onchange="javascript:deliveryProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required> <option value = "" hidden > choose </option> <?php $products = get_Products();
                                                                                                                                                                                                                                    while ($prod = $products->fetch_assoc()) { ?><option value = "<?= $prod["prod_id"] ?>" > <?= $prod["prod_name"] ?> </option><?php } ?></select > ';
    var company = document.getElementById("company_form");
    var contact_person = document.getElementById("dealer_name");
    var personnel = document.getElementById("personnel-form");
    var personnel_id = document.getElementById("personnel-id-form");
    var d_date = document.getElementById("date_delivery_form");
    var item_id, product, particulars, unit, unit_price, sell_price, quantity, amount, interest, expiry, action;


    $(document).ready(function() {
        var updatebtn = document.getElementById("newdelivery-form-btn");
        var xhr = new XMLHttpRequest();

        var cp_id = <?php echo $_GET["cp_id"] ?>;

        xhr.open('POST', './delivery/delivery_crud.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
        xhr.onload = function() {
            if (this.status == 200) {
                try {
                    var delivery = JSON.parse(this.responseText);
                    LoadDataTable(delivery);
                } catch (e) {
                    alert(e);
                }
            }
        }
        xhr.send("cp_id=" + cp_id);

        updatebtn.value = "Save Changes";
        updatebtn.id = 'updatedelivery-form-btn';
        updatebtn.name = 'updatedelivery';
    });

    function LoadDataTable(delivery) {
        company.value = delivery[0].supp_id;
        contact_person.value = delivery[0].contact_person + ' - ' + delivery[0].phone;
        personnel.textContent = delivery[0].personnel;
        personnel_id.value = delivery[0].user_id;
        d_date.value = delivery[0].date_delivered;

        for (let i = 0; i < delivery.length; i++) {
            var product = '<select name="product_id[]" onchange="javascript:deliveryProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required> <option value = "" hidden > choose </option><option value="' + delivery[i].prod_id + '" selected>' + delivery[i].prod_name + '</option><?php $products = get_Products();
                                                                                                                                                                                                                                                                                                                                    while ($prod = $products->fetch_assoc()) { ?><option value = "<?= $prod['prod_id'] ?>" > <?= $prod['prod_name'] ?> </option><?php } ?></select>';
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

            t.row.add([item_id, product, particulars, unit, unit_price, interest, sell_price, quantity, amount, expiry, action]).draw(false);
            counter++;
            size++;
        }
    }
</script>