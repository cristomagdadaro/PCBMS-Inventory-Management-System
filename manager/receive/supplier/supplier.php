<?php
require_once dirname(__DIR__, 1) . '\supplier\supplier_crud.php';
?>

<div>
    <div class="btn-group btn-group" role="group">
        <button type="button" class="btn btn-success d-flex align-items-center" id="toggle-newsupplier-form" data-toggle="tooltip" data-placement="left" title="Add new supplier">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"></path>
            </svg>
        </button>
    </div>
</div>

<div class="container-table fluid">
    <div class="row">
        <div id='supplier-datatable' class="col-sm mt-2" style="margin: auto">
            <table id="tableContent" class="table table-bordered nowrap">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Company</th>
                        <th scope="col">Name</th>
                        <th scope="col">Sex</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="supplier-table-body">
                </tbody>
            </table>
        </div>

        <div id="create-newsupplier-form" class="col-sm mt-2" style="margin: auto">
            <form id="addUpdateSupplierForm" class="box" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newsuppliertitle">Create new supplier</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-outline mb-2">
                            <label class="form-label col-form-label-sm" for="company">Company Name<span style="color:red">*</span></label>
                            <input type="text" id="company-form" class="form-control-sm" name="company" required />
                        </div>
                        <div class="form-outline mb-2">
                            <label class="form-label col-form-label-sm " for="contact-person">Contact Person<span style="color:red">*</span></label>
                            <input type="text" id="contact-person-form" class="form-control-sm" name="contact-person" required />
                        </div>
                        <div class="form-outline mb-2">
                            <label class="form-label col-form-label-sm " for="sex">Sex<span style="color:red">*</span></label>
                            <select id="sex-form" class="form-control-sm" name="sex" required>
                                <option value="" selected hidden>choose</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Non-binary">Non-binary</option>
                            </select>
                        </div>
                        <div class="form-outline mb-2">
                            <label class="form-label col-form-label-sm " for="number">Phone Number<span style="color:red">*</span></label>
                            <input type="phone" id="number-form" class="form-control-sm" name="number" required />
                        </div>
                        <div class="form-outline mb-2">
                            <label class="form-label col-form-label-sm " for="address">Address<span style="color:red">*</span></label>
                            <input type="text" id="address-form" class="form-control-sm" name="address" required />
                        </div>
                        <div class="form-outline mb-2">
                            <label class="form-label" for="newsupplier">&nbsp;</label>
                            <div class="row">
                                <div class="col-sm">
                                    <input type="button" id="newsupplier-form-btn" class="btn btn-sm btn-primary btn-block mb-4" name="newsupplier" value="Submit" />
                                </div>
                                <div class="col-sm">
                                    <input type="reset" id="reset-newsupplier-form-btn" class="btn btn-sm btn-danger btn-block mb-4" name="reset-newsupplier" value="Cancel" />
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
<script type="text/javascript">
    var t = $('#tableContent').DataTable();
    var update_id = -1;
    $(document).ready(function() {
        $('#receive-sidebar').addClass('active');
        Retrieve_Suppliers();
        $('#reset-newsupplier-form-btn').on('click', function() {
            var form = document.getElementById("create-newsupplier-form");
            form.style.display = 'none';
        });

        $('#toggle-newsupplier-form').on('click', function() {
            var form = document.getElementById("create-newsupplier-form");
            var addbtn = document.getElementById("newsupplier-form-btn");
            document.getElementById('addUpdateSupplierForm').reset();
            form.style.display = 'block';
            addbtn.value = "Submit";
            addbtn.name = 'newsupplier';
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
            var id = data[i]['supp_id']
            var company = data[i]['company'];
            var person = data[i]['contact_person'];
            var sex = data[i]['sex'];
            var phone = data[i]['phone'];
            var address = data[i]['address'];
            var action = ActionButton(id, company, person, sex, phone, address);
            t.row.add([id, company, person, sex, phone, address, action]).draw();
        }
    }

    function ActionButton(_id, _company, _person, _sex, _phone, _address) {
        return '<div class="d-flex justify-content-center">' +
            '<div class="btn-group btn-group-sm" role="group">' +
            `<button type="button" class="btn btn-outline-warning d-flex align-items-center" onclick="javascript: UpdateSupplier(${_id},'${_company}','${_person}','${_sex}','${_phone}','${_address}');" data-toggle="tooltip" data-placement="left" title="Modify this supplier">` +
            '<svg class="bi me-2" width="16" height="16"><use xlink:href="#update_icon" /></svg>' +
            '</button>' +
            `<button type="button" class="btn btn-outline-danger d-flex align-items-center" onclick="javascript: DeleteSupplierClick('${_company}',${_id});" data-toggle="tooltip" data-placement="right" data-html='true' title='Caution! Remove this supplier'>` +
            '<svg class="bi me-2" width="16" height="16"><use xlink:href="#delete_icon" /></svg>' +
            '</button>' +
            '</div>' +
            '</div>';
    }

    function Retrieve_Suppliers() {
        $.ajax({
            type: "POST",
            url: "./supplier/supplier_crud.php",
            dataType: 'json',
            data: {
                retrieve: 'supplier'
            },
            success: function(data) {
                Load_Datatable(data);
            }
        });
    }

    function DeleteSupplierClick(company, supp_id) {
        ConfirmDelete('Confirm to remove ' + company + ' as supplier permanently?', DeleteSupplier, supp_id);
    }

    function DeleteSupplier(supp_id) {
        $.ajax({
            type: "POST",
            url: "./supplier/supplier_crud.php",
            dataType: 'json',
            data: {
                delete: supp_id
            },
            success: function(data) {
                Retrieve_Suppliers();
                if (data['affected_rows'] != 'undefined' && data['affected_rows'] > 0)
                    Notification("Supplier removed", 'warning');
                else
                    Notification(data['error'], 'danger');
            }
        });
    }

    function UpdateSupplier(_id, _company, _person, _sex, _phone, _address) {
        var form = document.getElementById("create-newsupplier-form");
        var comp_name = document.getElementById("company-form");
        var person = document.getElementById("contact-person-form");
        var sex = document.getElementById("sex-form");
        var number = document.getElementById("number-form");
        var address = document.getElementById("address-form");
        var updatebtn = document.getElementById("newsupplier-form-btn");

        form.style.display = 'block';

        update_id = _id;
        comp_name.value = _company;
        person.value = _person;
        sex.value = _sex;
        number.value = _phone;
        address.value = _address;

        updatebtn.value = "Update";
        updatebtn.name = 'updatesupplier';
    }

    $("#newsupplier-form-btn").click(function() {
        var company = $("#company-form").val();
        var person = $("#contact-person-form").val();
        var sex = $("#sex-form").val();
        var number = $("#number-form").val();
        var address = $("#address-form").val();
        if (company != "" && person != "" && sex != "" && number != "" && address != "") {
            if ($('#newsupplier-form-btn').val() == "Update") {
                $.ajax({
                    type: "POST",
                    url: "./supplier/supplier_crud.php",
                    dataType: 'json',
                    cache: false,
                    data: {
                        id: update_id,
                        company: company,
                        contact_person: person,
                        sex: sex,
                        number: number,
                        address: address,
                        update: true
                    },
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (typeof response['error'] == "undefined") {
                            if (response['affected_rows'] > 0) {
                                Notification('Supplier info updated', 'success');
                                Retrieve_Suppliers();
                            } else {
                                Notification('No changes made', 'info');
                            }
                            $('#reset-newsupplier-form-btn').click();
                        } else {
                            Notification(response['error'], 'danger');
                            $('#company-form').focus();
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "./supplier/supplier_crud.php",
                    dataType: 'json',
                    cache: false,
                    data: {
                        company: company,
                        contact_person: person,
                        sex: sex,
                        number: number,
                        address: address,
                        new: true
                    },
                    success: function(response) {
                        console.log(JSON.stringify(response));
                        if (typeof response['error'] == 'undefined') {
                            Notification('New supplier created', 'success');
                            Retrieve_Suppliers();
                            $('#reset-newsupplier-form-btn').click();
                        } else {
                            Notification(response['error'], 'danger');
                            $('#company-form').focus();
                        }
                    }
                });
            }
        }
    });
</script>