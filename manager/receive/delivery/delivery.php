<?php
require_once dirname(__DIR__, 1) . '\delivery\delivery_crud.php';
?>
<div>
    <div class="btn-group btn-group" role="group">
        <button type="button" class="btn btn-success" id="toggle-newdelivery-form" onclick="javascript: NavbarLinkClick('delivery','./delivery/delivery_create.php');">
            <div class="d-flex align-items-center" data-toggle="tooltip" data-placement="left" title="Add new delivery">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#add_icon" />
                </svg>
            </div>
        </button>
    </div>
</div>

<div class="container-table fluid">
    <div class="row">
        <div id="delivery-datatable" class="mt-2" style="margin: auto">
            <table id="tableContent" class="table table-bordered nowrap">
                <thead>
                    <tr>
                        <th scope="col" style="width: 200px !important;">CP ID</th>
                        <th scope="col">Delivered</th>
                        <th scope="col">Company</th>
                        <th scope="col">Items</th>
                        <th scope="col">Avg. interest</th>
                        <th scope="col">Total Sell Price </th>
                        <th scope="col">Total Quantity</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="delivery-table-body">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    var t = $('#tableContent').DataTable();
    $(document).ready(function() {
        $('#receive-sidebar').addClass('active');
        Retrieve_Products();
    });

    function DeleteProduct(cp_id) {
        if (confirm('Confirm to remove delivery number ' + cp_id + ' permanently?')) {
            $.ajax({
                type: "POST",
                url: "/manager/receive/delivery/delivery_crud.php",
                dataType: 'json',
                data: {
                    delete: cp_id
                },
                success: function(data) {
                    Retrieve_Products();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Notification("Error: Status: " + textStatus + " Message: " + errorThrown, 'danger');
                }
            });
        } else
            return false;
    }

    function Load_Datatable(data) {
        var len = Object.keys(data).length;
        t.clear();
        for (var i = 0; i < len; i++) {
            var cp_id = data[i]['cp_id']
            var dist_item_count = data[i]['dist_item_count'];
            var avg_interest = data[i]['avg_interest'];
            var total_sell_price = data[i]['total_sell_price'];
            var total_qty = data[i]['total_qty'];
            var total_amt = data[i]['total_amt'];
            var date_delivered = data[i]['date_delivered'];
            var user_id = data[i]['user_id'];
            var supp_id = data[i]['supp_id'];
            var company = data[i]['company'];

            var action = ActionButton(cp_id);
            t.row.add([cp_id, date_delivered, company, dist_item_count, avg_interest, total_sell_price, total_qty, total_amt, action]).draw();
        }
    }

    function Retrieve_Products() {
        $.ajax({
            type: "POST",
            url: "/manager/receive/delivery/delivery_crud.php",
            dataType: 'json',
            data: {
                retrieve: 'delivery'
            },
            success: function(data) {
                Load_Datatable(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Notification("Error: Status: " + textStatus + " Message: " + errorThrown, 'danger');
            }
        });
    }

    function ActionButton(_cp_id) {
        return '<div class="d-flex justify-content-center">' +
            '<div class="btn-group btn-group-sm" role="group">' +
            `<button type="button" class="btn btn-outline-warning d-flex align-items-center" onclick="javascript: NavbarLinkClick('delivery','./delivery/delivery_update.php?cp_id='+${_cp_id});" data-toggle="tooltip" data-placement="top" title="Edit/View details">` +
            '<svg class="bi me-2" width="16" height="16">' +
            '<use xlink:href="#update_icon" />' +
            '</svg>' +
            '</button>' +
            `<button type="button" class="btn btn-outline-danger d-flex align-items-center" onclick="javascript: DeleteProduct(${_cp_id});" data-toggle="tooltip" data-placement="right" data-html="true" title="<span style='color:yellow;'>Caution!</span> Remove this delivery">` +
            '<svg class="bi me-2" width="16" height="16">' +
            '<use xlink:href="#delete_icon" />' +
            '</svg>' +
            '</button>' +
            '</div>' +
            '</div>';
    }
</script>