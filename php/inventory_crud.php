<?php
require 'db.php';
require 'messagebox.php';



function get_Suppliers($conn){
    $result = $conn->query("select * from `get_suppliers`;");
    if ($result) {
        return $result;
    } else
        setmessage("Unable to retrieve lists of suppliers from the database","danger",NULL);
        return null;
}

function new_product($conn, $name, $unit, $crit, $qty)
{
    $sql = "insert into `pcbms`.`product` (`name`, `unit_price`, `critical_lvl`, `qty`) VALUES ('$name','$unit','$crit','$qty');";
    $result = $conn->query($sql);
    if ($result)
        return true;
    return $conn->errno;
}

function new_item($conn, $prod_code, $prod_id, $expry, $sell, $purchase, $qty)
{
    $sql = "insert into `pcbms`.`items` (`product_code`, `product_id`, `expiry_date`, `selling_unit_price`, `purchase_unit_price`, `quantity`) VALUES ('$prod_code','$prod_id','$expry','$sell','$purchase','$qty');";
    $result = $conn->query($sql);
    if ($result)
        return true;
    return $conn->errno;
}

function new_delivery($conn, $supp_id, $status, $delivery_date)
{
    $sql = "insert into `pcbms`.`delivery` (`supplier_id`, `status`, `delivery_date`) VALUES ('$supp_id','$status','$delivery_date');";
    $result = $conn->query($sql);
    if ($result)
        return true;
    return $conn->errno;
}

if (isset($_POST['add-btn'])) {
    session_start();
    if (new_product($conn, $_POST['product-name'], $_POST['unit'], $_POST['critical-level'], $_POST['quantity'])) {
        if (new_item($conn, $_POST['product-code'], $_POST['product-id'], $_POST['expiry-date'], $_POST['sell-price'], $_POST['purchase-price'], $_POST['quantity'])) {
            if (new_delivery($conn, $_POST['supplier_id'], $_POST['status'], $_POST['delivery-date'])) {
                setmessage("New Product Added", "success", "../inventory/index.php");
            } else {
                setmessage("Unable to create new delivery record: $conn->error", "danger", "index.php");
            }
            echo "\nDelivery Table: " . $conn->errno . " - "  . $conn->error;
        } else {
            setmessage("Unable to create new item record: $conn->error", "danger", "index.php");
        }
        echo "\nItem Table" . $conn->errno . " - "  . $conn->error;
    } else {
        setmessage("Unable to create new product record: $conn->error", "danger", "index.php");
    }
    echo "\nProduct Table: " . $conn->errno . " - " . $conn->error;
    $conn->close();
}
