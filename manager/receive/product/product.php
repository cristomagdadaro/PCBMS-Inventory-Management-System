<?php
require_once dirname(__DIR__, 1) . '\product\product_crud.php';
?>

<div id="product-container">
    <div>
        <div class="btn-group btn-group" role="group">
            <button type="button" class="btn btn-success d-flex align-items-center" id="toggle-newproduct-form" data-toggle="tooltip" data-placement="left" title="Add new product">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#add_icon" />
                </svg>
            </button>
        </div>
    </div>
    <div class="container-table fluid">
        <div class="row">
            <div id="product-datatable" class="mt-2" style="margin: auto">
                <table id="tableContent" class="table table-bordered nowrap">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Product</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Shelflife (days)</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                    </tbody>
                </table>
            </div>
            <div id="create-newproduct-form" class="col-sm mt-2" style="margin: auto">
                <form id="addUpdateProductForm" class="box" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newproducttitle">Create new product</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-outline mb-2">
                                <label class="form-label col-form-label-sm " for="prod_name">Product Name<span style="color:red">*</span></label>
                                <input type="text" id="prod_name_form" class="form-control-sm" name="prod_name" required />
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label col-form-label-sm " for="unit">Unit<span style="color:red">*</span></label>
                                <select name="unit" id="unit_form" class="form-control-sm" required>
                                    <option value="" selected hidden>Choose</option>
                                    <option value="pcs">pcs - Piece/s</option>
                                    <option value="ml">ml - Milliliter</option>
                                    <option value="l">l - Liter</option>
                                    <option value="kg">kg - Kilogram</option>
                                    <option value="g">g - Gram</option>
                                    <option value="lb">lb - Pound</option>
                                </select>
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label col-form-label-sm " for="shelf_life">Shelflife (days)</label>
                                <input type="number" id="shelf_life_form" class="form-control-sm" name="shelf_life" />
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label" for="newproduct">&nbsp;</label>
                                <div class="row">
                                    <div class="col-sm">
                                        <input type="button" id="newproduct-form-btn" class="btn btn-sm btn-primary btn-block mb-4" name="newproduct" value="Submit" />
                                    </div>
                                    <div class="col-sm">
                                        <input type="reset" id="reset-newproduct-form-btn" class="btn btn-sm btn-danger btn-block mb-4" name="reset-newproduct" value="Cancel" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Page Content Holder -->
        </div>
    </div>
</div>
<script type="text/javascript">
    var t = $('#tableContent').DataTable();

    $(document).ready(function() {
        $('#receive-sidebar').addClass('active');
        Retrieve_Products();
        $('#reset-newproduct-form-btn').on('click', function() {
            var form = document.getElementById("create-newproduct-form");
            form.style.display = 'none';
        });

        $('#toggle-newproduct-form').on('click', function() {
            var form = document.getElementById("create-newproduct-form");
            var addbtn = document.getElementById("newproduct-form-btn");
            document.getElementById('addUpdateProductForm').reset();
            form.style.display = 'block';
            addbtn.value = "Submit";
            addbtn.name = 'newproduct';
        });
        //prevent the previous POSt request to be sent again when reloaded
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });



    function Load_Datatable(data) {
        var len = Object.keys(data).length;
        t.clear();
        for (var i = 0; i < len; i++) {
            var id = data[i]['prod_id']
            var name = data[i]['prod_name'];
            var life = data[i]['shelf_life'];
            var unit = data[i]['unit'];
            var action = ActionButton(id, name, life, unit);
            t.row.add([id, name, unit, life, action]).draw();
        }
    }

    function ActionButton(_id, _name, _life, _unit) {
        return '<div class="d-flex justify-content-center">' +
            '<div class="btn-group btn-group-sm" role="group">' +
            `<button type="button" class="btn btn-outline-warning d-flex align-items-center" onclick="javascript: UpdateProduct(${_id},'${_name}',${_life},'${_unit}');" data-toggle="tooltip" data-placement="left" title="Modify this product">` +
            '<svg class="bi me-2" width="16" height="16"><use xlink:href="#update_icon" /></svg>' +
            '</button>' +
            `<button type="button" class="btn btn-outline-danger d-flex align-items-center" onclick="javascript: DeleteProductClick('${_name}',${_id});" data-toggle="tooltip" data-placement="right" data-html='true' title='Caution! Remove this product'>` +
            '<svg class="bi me-2" width="16" height="16"><use xlink:href="#delete_icon" /></svg>' +
            '</button>' +
            '</div>' +
            '</div>';
    }

    function Retrieve_Products() {
        $.ajax({
            type: "POST",
            url: "./product/product_crud.php",
            dataType: 'json',
            data: {
                retrieve: 'products'
            },
            success: function(data) {
                Load_Datatable(data);
            }
        });
    }

    function DeleteProductClick(prod_name, prod_id) {
        ConfirmDelete('Confirm to remove ' + prod_name + ' from product list permanently?', DeleteProduct, prod_id);
    }

    function DeleteProduct(prod_id) {
        $.ajax({
            type: "POST",
            url: "./product/product_crud.php",
            dataType: 'json',
            data: {
                delete: prod_id
            },
            success: function(data) {
                Retrieve_Products();
                if (data['affected_rows'] != 'undefined' && data['affected_rows'] > 0)
                    Notification("Product removed", 'warning');
                else
                    Notification(data['error'], 'danger');
            }
        });
    }

    var update_id = -1;

    function UpdateProduct(id, _name, _life, _unit) {
        var form = document.getElementById("create-newproduct-form");
        var prod_name = document.getElementById("prod_name_form");
        var unit = document.getElementById("unit_form");
        var shelflife = document.getElementById("shelf_life_form");
        var updatebtn = document.getElementById("newproduct-form-btn");

        form.style.display = 'block';

        prod_name.value = _name;
        unit.value = _unit;
        shelflife.value = _life;
        update_id = id;
        updatebtn.value = "Update";
        updatebtn.name = 'updateproduct';
    }

    $("#newproduct-form-btn").click(function() {
        var name = $("#prod_name_form").val();
        var life = $("#shelf_life_form").val();
        var unit = $("#unit_form").val();
        if (name != "" && life != "" && unit != "") {
            if ($('#newproduct-form-btn').val() == "Update") {
                $.ajax({
                    type: "POST",
                    url: "./product/product_crud.php",
                    dataType: 'json',
                    cache: false,
                    data: {
                        prod_name: name,
                        shelf_life: life,
                        unit: unit,
                        update: update_id
                    },
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (typeof response['error'] == "undefined") {
                            if (response['affected_rows'] > 0) {
                                Notification('Product info updated', 'success');
                                Retrieve_Products();
                            } else {
                                Notification('No changes made', 'info');
                            }
                            $('#reset-newproduct-form-btn').click();
                        } else {
                            Notification(response['error'], 'danger');
                            $('#prod_name_form').focus();
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "./product/product_crud.php",
                    dataType: 'json',
                    cache: false,
                    data: {
                        prod_name: name,
                        shelf_life: life,
                        unit: unit,
                        new: true
                    },
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (typeof response['error'] == 'undefined') {
                            Notification('New product created', 'success');
                            Retrieve_Products();
                            $('#reset-newproduct-form-btn').click();
                        } else {
                            Notification(response['error'], 'danger');
                            $('#prod_name_form').focus();
                        }
                    }
                });
            }
        } else {
            Notification('Please fill all the required fields', 'warning');
            $('#prod_name_form').focus();
        }
    });
</script>