<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.min.css">
    <!-- Form CSS -->
    <link type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/css/sidebar-style-sheet.css">
    <link rel="stylesheet" href="/css/main-style-sheet.css">
    <link rel="stylesheet" href="/css/cashier-style.css">
    <link rel="stylesheet" href="/css/alertbox.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/sweetalert2.all.min.js"></script>
    <title>Cashier | PCBMS</title>
</head>

<body>
    <div id="cashier-content">
        <?php
        session_start();
        require_once dirname(__DIR__, 1) . '\php\icon.php';
        require_once dirname(__DIR__, 1) . '\manager\receive\product\product_crud.php';
        require_once dirname(__DIR__, 1) . '\cashier\sales_crud.php';
        require_once dirname(__DIR__, 1) . '\php\messagebox.php';
        require './navbar/navbar.php';
        alertbox();
        ?>
        <div id="purchase-details">
            <div id='scan-product'>
                <div class="input-group input-group-sm mb-3 ">
                    <label class="col-form-label">Choose Product</label>
                    <select name="product_id[]" onchange="javascript:ProductClick()" id="product-form" class="form-control-sm" style="text-overflow: ellipsis;" required>
                        <?php $products = get_Products();
                        if(!$products)
                            echo "<option value=''>No products avaliable</option>";
                        else
                            echo "<option value='' hidden>Choose</option>";
                        while ($prod = $products->fetch_assoc()) { 
                            //if($prod["total_qty_sold"] < $prod["initial_qty"]) { ?>
                                <option value="<?= $prod["prod_id"] ?>"> <?= $prod["prod_name"] ?> </option><?php } //}?>
                    </select>
                </div>

                <label class="form-control-plaintext p-0 text-left">Product ID: <span id="product-id">none</span></label>
                <label class="form-control-plaintext p-0 text-left">CP ID: <span id="cp-id">none</span></label>
                <label class="form-control-plaintext p-0 text-left">Item ID: <span id="item-id">none</span></label>
                <label class="form-control-plaintext p-0 text-left">Unit price: <span id="unit-price">none</span></label>
                <label class="form-control-plaintext p-0 text-left">Remaining stock: <span id="remain-stock">none</span></label>
                <div class="my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text p-1">Quantity</div>
                        </div>
                        <input type="number" class="form-control p-1" id="quantity" placeholder="none">
                    </div>
                </div>
                <div class="float-right my-1" role="group">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="javascript: ClearInputs()">&nbsp;Cancel&nbsp;</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="javascript: InsertItem()">&nbsp;&nbsp;Add&nbsp;&nbsp;</button>
                </div>
            </div>
            <div id='pos-receipt'>
                <form id="receipt-form">
                    <p>Transaction Number : <span>unkown</span></p>
                    <table class="bill-details">
                        <tbody>
                            <tr>
                                <td>Date : <span id='current-date'><?php echo date('Y-m-d') ?></span></td>
                                <td>Time : <span id='current-time'><?php echo date('h:i A') ?></span></td>
                            </tr>
                            <tr>
                                <td>Cashier:
                                    <span>
                                        <?php
                                        if (isset($_SESSION))
                                            echo $_SESSION['name'];
                                        else
                                            echo 'Unknown';
                                        ?>
                                    </span>
                                </td>
                                <td>Bill # : <span>4</span></td>
                            </tr>
                            <tr>
                                <td>Customer Name: <input type="text" class="form-control-sm p-1 my-1" name="customer-name" id="customer-name" placeholder="unknown"></td>
                                <td>Customer Address: <input type="text" class="form-control-sm p-1 my-1" name="customer-address" id="customer-address" placeholder="unknown"></td>
                            </tr>
                            <tr>
                                <th class="center-align" colspan="2"><span class="receipt">Receipt</span></th>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-sm items table-hover ">
                        <caption>
                            <div class="col">
                                <div class="row">
                                    <div class="input-group my-1 col-sm-6">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">Recieved Amount ₱</div>
                                        </div>
                                        <input type="number" min="0" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="form-control p-1" name="received-amount" id="received-amount" onkeyup="javascript: InputReceived()" placeholder="0" required>
                                    </div>

                                    <div class="input-group my-1 col-sm-6">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">Change ₱</div>
                                        </div>
                                        <input type="number" class="form-control p-1" name="change-amount" id="change-amount" placeholder="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="input-group my-1 my-1 col-sm-6">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">Paid via</div>
                                        </div>
                                        <select name="paymet-method" id="paymet-method" class="form-control p-1">
                                            <option value="Cash">Cash</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Debit Card">Debit Card</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col my-1">
                                        <input type="submit" value="Pay" id="pay-btn" class="btn btn-sm btn-success col">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-sm-6 my-1">
                                        <input type="reset" value="Cancel" class="btn btn-sm btn-outline-danger col" onclick="javascript: ClearReceipt()">
                                    </div>
                                    <div class="col-sm-6 my-1">
                                        <button class="btn btn-sm btn-primary col">Print Receipt</button>
                                    </div>
                                </div>
                            </div>
                        </caption>
                        <thead>
                            <tr>
                                <th class="heading id">ID</th>
                                <th class="heading name">Item</th>
                                <th class="heading qty">Qty</th>
                                <th class="heading rate">Unit</th>
                                <th class="heading amount">Amount</th>
                                <th class="heading action">Action</th>
                            </tr>
                        </thead>

                        <tbody id="receipt-table">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="sum-up line">Subtotal</td>
                                <td class="line price" id="subtotal">0</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="sum-up">VAT(12%)</td>
                                <td class="price" id="vat-value">0</td>
                            </tr>
                            <tr>
                                <th colspan="4" class="total text">Total</th>
                                <th class="total price" id="total-amount">0</th>
                            </tr>
                        </tfoot>
                    </table>
                    <section>
                        <!---->
                    </section>
                </form>
            </div>
        </div>
        <footer>
            <div class="copyright-footer">
                <p>&copy; Cristo Rey C. Magdadaro All rights reserved.</p>
            </div>
        </footer>
    </div>
    <!--For Toggling the sidebar-->
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/js/popper.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <!--For Datatable Important-->
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script type="text/JavaScript" src="/js/jquery.dataTables.min.js"></script>
    <script type="text/JavaScript" src="/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript" src="/js/sidebar-toggle.js"></script>
    <script type="text/javascript" src="/js/alertbox.js"></script>
    <script type="text/javascript">
        window.onload = function() {
            clock();
            LoadData();

            function clock() {
                var now = new Date();
                var TwentyFourHour = now.getHours();
                var hour = now.getHours();
                var min = now.getMinutes();
                var sec = now.getSeconds();
                var mid = 'pm';
                if (min < 10) {
                    min = "0" + min;
                }
                if (hour > 12) {
                    hour = hour - 12;
                }
                if (hour == 0) {
                    hour = 12;
                }
                if (TwentyFourHour < 12) {
                    mid = 'am';
                }
                document.getElementById('current-time').innerHTML = hour + ':' + min + ':' + sec + ' ' + mid;
                setTimeout(clock, 1000);
            }

            function LoadData() {
                var data = [{
                    "prod_id": 218,
                    "prod_name": "Chocolate milkshake frappe",
                    "quantity": 6,
                    "unit_price": 200
                }, {
                    "prod_id": 215,
                    "prod_name": "Milkshake frappe",
                    "quantity": 5,
                    "unit_price": 300
                }, {
                    "prod_id": 204,
                    "prod_name": "Frappe",
                    "quantity": 8,
                    "unit_price": 400
                }];

                var len = data.length;
                var amount = 0;
                for (var i = 0; i < len; i++) {
                    amount += RetrieveFromDatabase(i, data[i]);
                }
            }
        }

        $("#quantity").keypress(function(event) {
            event.preventDefault();
            if (event.key === 'Enter') {
                InsertItem();
            } else
                this.value += event.key;
        });

        var table = document.getElementById('receipt-table');
        var vat = document.getElementById('vat-value');
        var subtotal = document.getElementById('subtotal');
        var total_amt = document.getElementById('total-amount');
        var prod_id = document.getElementById('product-id');
        var cp_id = document.getElementById('cp-id');
        var item_id = document.getElementById('item-id');
        var unit_price = document.getElementById('unit-price');
        var remain_stock = document.getElementById('remain-stock');
        var qty = document.getElementById('quantity');

        function RetrieveFromDatabase(i, data) {
            var quantity = parseFloat(data['quantity']);
            var unit_price = parseFloat(data['unit_price']);
            var amount = quantity * unit_price;
            AddRow(i, data['prod_id'], data['prod_name'], quantity, unit_price, amount);
            Compute(amount);
            return amount;
        }

        function AddRow(i = 0, prod_id, prod_name, quantity, unit_price, amount) {
            var row = table.insertRow(i);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            var cell6 = row.insertCell(5);

            cell1.innerHTML = prod_id;
            cell2.innerHTML = prod_name;
            cell3.innerHTML = quantity;
            cell4.innerHTML = unit_price;
            cell5.innerHTML = amount;
            cell6.innerHTML = '<button type="button" class="btn btn-sm p-0 border-0 mx-auto btn-outline-danger d-flex align-items-center" onclick="RemoveRow(' + amount + ',this)"><svg class="bi me-2" width="16" height="16"><use xlink:href="#cancel_icon" /></svg></button>';
        }

        function RemoveRow(amount, row) {
            var r = $(row).closest("tr");
            Compute(-amount);
            r.remove();
        }

        function InsertItem() {
            var prod_name = $("#product-form").children("option").filter(":selected").text();
            var prod_id = document.getElementById('product-id').innerText;
            var unit_price = document.getElementById('unit-price').innerText;
            var qty = document.getElementById('quantity').value;
            var amount = parseFloat(qty * unit_price);

            if (!isBlank(prod_name) && !isBlank(unit_price) && !isBlank(qty)) {
                AddRow(0, prod_id, prod_name, parseInt(qty), unit_price, amount);
                Compute(amount);
                ClearInputs();
                document.getElementById('product-form').focus();
            }
        }

        function Compute(amount) {
            subtotal.innerText = parseFloat(subtotal.innerText) + parseFloat(amount);
            vat.innerText = parseFloat(subtotal.innerText) * 0.12;
            total_amt.innerText = parseFloat(subtotal.innerText) + parseFloat(vat.innerText);
        }

        function ClearInputs() {
            document.getElementById('product-id').innerText = 'none';
            document.getElementById('cp-id').innerText = 'none';
            document.getElementById('item-id').innerText = 'none';
            document.getElementById('unit-price').innerText = 'none';
            document.getElementById('product-form').value = "";
            document.getElementById('quantity').value = "";
            document.getElementById('remain-stock').innerText = 'none';
        }

        function isBlank(str) {
            return (!str || /^\s*$/.test(str) || str.includes('none') | str == 0);
        }

        function ProductClick() {
            var product = $("#product-form").children("option").filter(":selected").val();
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../manager/receive/product/product_crud.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
            xhr.onload = function() {
                if (this.status == 200) {
                    try {
                        var product = JSON.parse(this.responseText);
                        prod_id.innerText = product.prod_id;
                        cp_id.innerText = product.cp_id;
                        item_id.innerText = product.item_id;
                        unit_price.innerText = product.unit_price;
                        if(product.total_qty_sold >= product.initial_qty)
                            remain_stock.innerText = 0;
                        else
                            remain_stock.innerText = product.initial_qty - product.total_qty_sold;
                        document.getElementById('quantity').focus();
                    } catch (e) {
                        alert('Out of Stock');
                    }
                }
            }
            xhr.send("prod_id=" + product);
        }

        function ClearReceipt() {
            document.getElementById('vat-value').innerText = 0;
            document.getElementById('subtotal').innerText = 0;
            document.getElementById('total-amount').innerText = 0;
            $("#receipt-table tr").remove();
        }

        function InputReceived() {
            var total_amt = parseFloat(document.getElementById('total-amount').innerText);
            var received_amt = parseFloat(document.getElementById('received-amount').value);
            var change_form = document.getElementById('change-amount');
            if (total_amt && received_amt && change_form)
                change_form.value = received_amt - total_amt;

            if (!received_amt)
                change_form.value = 0;
        }

        $(document).on('submit', '#receipt-form', function(e) {
            if (table.childElementCount > 0 && document.getElementById('received-amount').value > 0) {
                var datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
                var user = <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 0 ?>;
                var total_amount = document.getElementById('total-amount').innerText;
                var customer_name = document.getElementById('customer-name').value;
                var customer_address = document.getElementById('customer-address').value;
                var product = [];
                for (var i = 0; i <= table.childElementCount; i++) {
                    var row = table.childNodes[i];
                    var cell = [];
                    for (var j = 0; j < row.childElementCount - 1; j++) {
                        cell.push(row.childNodes[j].innerText)
                    }
                    product.push(cell);
                }

                e.preventDefault();
                $.ajax({
                    method: "POST",
                    url: "sales_crud.php",
                    data: {
                        date_purchase: datetime,
                        customer_name: customer_name ? customer_name : 'unknown',
                        address: customer_address ? customer_address : 'unknown',
                        user_id: user,
                        amount: total_amount,
                        products: product,
                        newpurchase: true
                    },
                    success: function(data) {
                        try {
                            var result = JSON.parse(data);
                            Confirm('Success', 'Next customer?', 'Yes', 'No', '');
                            ClearReceipt();
                        } catch (e) {
                            alert(e.message);
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>