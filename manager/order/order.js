function orderSupplierClick() {
    var company = $("#company_form").children("option").filter(":selected").text();
    var contact_person = document.getElementById("dealer_name");
    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'order_crud.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhr.onload = function () {
        if (this.status == 200) {
            var person = JSON.parse(this.responseText);
            contact_person.value = person[0].contact_person + " - " + person[0].phone;
        }
    }
    xhr.send("company_name=" + company);
}

function orderProductClick(e) {
    var row = e.closest('tr');
    var prod_id = e.value;
    var unit_form = row.childNodes[2].childNodes[0]
    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'order_crud.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhr.onload = function () {
        if (this.status == 200) {
            var product = JSON.parse(this.responseText);
            unit_form.value = product[0].unit;
        }
    }
    xhr.send("prod_id=" + prod_id);
}

function dicardChanges() {
    t.clear();
    counter = 1;
    size = 1;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'order.cache', true);
    xhr.onload = function() {
        if (this.status == 200) {
            var product = JSON.parse(this.responseText);
            LoadDataTable(product);
        }
    }
    xhr.send();
}

var counter = 0;
var size = 0;
var _unit = '<input type="text" id="unit_form" class="form-control-sm" name="unit[]" readonly/>';
var _quantity = '<input type="number" id="quantity_form" class="form-control-sm" name="quantity[]" required />';
var _action = '<div class="d-flex justify-content-center"><div class="btn-group btn-group-sm" role="group"><button type="button" onclick="javascript: AddRow();" class="addRow btn btn-outline-primary d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#add_row_icon" /></svg></button><button type="button" onclick="javascript: RemoveRow(this);" class="btn btn-outline-danger d-flex align-items-center"><svg class="bi me-2" width="16" height="16"><use xlink:href="#remove_row_icon" /></svg></button></div></div>';
var _item_id;

function AddRow() {
    counter++;
    size++;
    _item_id = '<input type="text" id="item_id_form" value="' + counter + '" class="form-control-plaintext text-center" name="item_id[]" readonly/>';
    t.row.add([_item_id, Options_Str(option_str), _unit, _quantity, _action]).draw(false);
}

function Options_Str(_options, _prod_id = null, _prod_name = null) {
    if(_prod_id && _prod_name)
        return `<select name="product_id[]" onchange="javascript:orderProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required><option value='${_prod_id}' selected hidden>${_prod_name}</option>${_options}</select >`;     
    return `<select name="product_id[]" onchange="javascript:orderProductClick(this)" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required>${_options}</select >`;
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
