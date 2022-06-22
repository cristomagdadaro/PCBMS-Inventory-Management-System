function CalculateDetails(e) {
    var row = e.closest('tr');
    var unit_price_form = row.childNodes[4].childNodes[0];
    var selling_price_form = row.childNodes[6].childNodes[0];
    var interest_form = row.childNodes[5].childNodes[0];
    var amount_form = row.childNodes[8].childNodes[0];
    var quantity_form = row.childNodes[7].childNodes[0];

    selling_price_form.value = (parseFloat((unit_price_form.value * interest_form.value)) + parseFloat(unit_price_form.value)).toFixed(2);
    if (parseFloat(selling_price_form.value) != 0) {
        amount_form.value = parseFloat(selling_price_form.value * quantity_form.value);
    }
}

function deliverySupplierClick() {
    var company = $("#company_form").children("option").filter(":selected").text();
    var contact_person = document.getElementById("dealer_name");
    var xhr = new XMLHttpRequest();

    xhr.open('POST', './delivery/delivery_crud.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhr.onload = function () {
        if (this.status == 200) {
            var person = JSON.parse(this.responseText);
            contact_person.value = person[0].contact_person + " - " + person[0].phone;
        }
    }
    xhr.send("company_name=" + company);
}

function dicardChanges() {
    t.clear();
    counter = 1;
    size = 1;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './delivery/delivery.cache', true);
    xhr.onload = function () {
        if (this.status == 200) {
            var product = JSON.parse(this.responseText);
            LoadDataTable(product);
        }
    }
    xhr.send();
}

function deliveryProductClick(e) {
    var row = e.closest('tr');
    var prod_id = e.value;
    var unit_form = row.childNodes[3].childNodes[0]
    var expiry_date = row.childNodes[9].childNodes[0];
    var xhr = new XMLHttpRequest();

    xhr.open('POST', './delivery/delivery_crud.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhr.onload = function () {
        if (this.status == 200) {
            var product = JSON.parse(this.responseText);
            var ex_date_str = new Date(Date.now() + (parseInt(product[0].shelf_life) * 86400000)).toISOString().substr(0, 10);
            unit_form.value = product[0].unit;
            expiry_date.value = ex_date_str;
        }
    }
    xhr.send("prod_id=" + prod_id);
}

var counter = 0;
var size = 0;
var _particulars = '<input type="text" id="particulars_form" class="form-control-sm" name="particulars[]" />';
var _unit = '<input type="text" id="unit_form" class="form-control-sm" name="unit[]" readonly/>';
var _unit_price = '<input type="number" step="0.01" class="form-control-sm" id="unit_price_form" name="unit_price[]" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);" aria-label="Amount (to the nearest dollar)" required>';
var _sell_price = '<input type="number" step="0.01" class="form-control-sm" id="selling_price_form" name="selling_price[]" aria-label="Amount (to the nearest dollar)" required readonly>';
var _quantity = '<input type="number" id="quantity_form" class="form-control-sm" name="quantity[]" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);" required />';
var _amount = '<input type="number" step="0.01" class="form-control-sm" id="amount_form" name="amount[]" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);" aria-label="Amount (to the nearest dollar)" required>';
var _interest = '<input type="number" step="0.01" class="form-control-sm" id="interest_form" name="interest[]" value="0.12" onkeyup="javascript:CalculateDetails(this);" onchange="javascript:CalculateDetails(this);">';
var _expiry = '<input type="date" class="form-control-sm" id="date_expiry_form" name="date_expiry[]">'
var _action = '<div class="d-flex justify-content-center"><div class="btn-group btn-group-sm" role="group"><button type="button" onclick="javascript: AddRow();" class="addRow btn btn-outline-primary d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#add_row_icon" /></svg></button><button type="button" onclick="javascript: RemoveRow(this);" class="btn btn-outline-danger d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#remove_row_icon" /></svg></button></div></div>';
var _item_id;

function AddRow() {
    counter++;
    size++;
    _item_id = '<input type="text" id="item_id_form" value="' + counter + '" class="form-control-plaintext text-center" name="item_id[]" readonly/>';
    t.row.add([_item_id, Options_Str(option_str), _particulars, _unit, _unit_price, _interest, _sell_price, _quantity, _amount, _expiry, _action]).draw(false);
}

function Options_Str(_options, _prod_id = null, _prod_name = null) {
    if (_prod_id && _prod_name)
        return `<select name="product_id[]" onchange="javascript: deliveryProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required><option value='${_prod_id}' selected>${_prod_name}</option>${_options}</select >`;
    return `<select name="product_id[]" onchange="javascript: deliveryProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required>${_options}</select >`;
}

function RemoveRow(e) {
    if (size > 1) {
        t.row(e.closest("tr")).remove().draw();
        size--;
    } else {
        counter = 0;
        size = 0;
        t.row(e.closest("tr")).remove();
        AddRow();
    }
}

function ClearTable() {
    counter = 0;
    size = 0;
    t.clear();
    AddRow();
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
        url: "./delivery/delivery_crud.php",
        dataType: 'json',
        data: {
            getdeliverysuppliers: true
        },
        success: function (data) {
            Dropdown_Suppliers(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
            Notification("Error: " + textStatus + " Message: " + errorThrown, 'danger');
        }
    });
}

function Retrieve_Products() {
    $.ajax({
        type: "POST",
        url: "./delivery/delivery_crud.php",
        dataType: 'json',
        data: {
            getproducts: true
        },
        success: function (data) {
            console.log(data);
            var len = Object.keys(data).length;
            if (len) {
                for (var i = 0; i < len; i++) {
                    option_str += `<option value='${data[i]['prod_id']}'>${data[i]['prod_name']}</option>`;
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
            Notification("Error: " + textStatus + " Message: " + errorThrown, 'danger');
        }
    });
}