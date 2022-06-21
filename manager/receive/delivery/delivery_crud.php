<?php
require_once dirname(__DIR__, 3) . '\php\db.php';
require_once dirname(__DIR__, 3) . '\php\messagebox.php';
require_once dirname(__DIR__, 3) . '\php\cleaner.php';
require_once dirname(__DIR__, 2) . '\order\order_crud.php';

class Delivery extends DB
{
    function __construct()
    {
        parent::__construct();
    }

    function Get_All_Deliveries()
    {
        $this->sql = "SELECT * FROM `get_delivery_summary`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    private function CreateConsignedProduct($company, $personnel, $date_delivery)
    {
        $this->sql = "INSERT INTO `pcbms_db`.`consigned_product` (`supp_id`, `user_id`, `date_delivered`) VALUES ('{$company}', '{$personnel}', '{$date_delivery}');";
        $result = parent::Insert_Query();
        return $result;
    }

    private function CreateConsignedDetail($delivery, $index, $cp_id)
    {
        $prod_id = $delivery["product_id"][$index];
        $particulars = validate($delivery["particulars"][$index]);
        $unit_price = $delivery["unit_price"][$index];
        $interest = $delivery["interest"][$index];
        $selling_price = $delivery["selling_price"][$index];
        $quantity = $delivery["quantity"][$index];
        $amount = $delivery["amount"][$index];
        $date_expiry = $delivery["date_expiry"][$index];

        $this->sql = "INSERT INTO `pcbms_db`.`consigned_details` (`cp_id`, `prod_id`, `particulars`, `expiry_date`, `unit_price`, `interest`, `selling_price`, `quantity`, `amount`) VALUES ('$cp_id', '{$prod_id}', '{$particulars}', '{$date_expiry}', '{$unit_price}', '{$interest}', '{$selling_price}', '{$quantity}', '{$amount}');";
        $result = parent::Insert_Query();
        return $result['inserted_id'];
    }

    function New_Delivery($delivery)
    {
        $cp_id = $this->CreateConsignedProduct($delivery["company"], $delivery["personnel"], $delivery["date_delivery"]);
        if ($cp_id['inserted_id'] > 0) {
            $insert_history = array();
            for ($i = 0; $i < count($delivery["product_id"]); $i++)
                $result = $this->CreateConsignedDetail($delivery, $i, $cp_id['inserted_id']);
            if ($result['inserted_id'] > 0)
                array_push($insert_history, $result);
            else {
                $this->sql = "DELETE FROM `pcbms_db`.`consigned_product` WHERE  `cp_id`='{$cp_id['inserted_id']}';";
                $result = parent::Delete_Query();
                return $result;
            }
            echo json_encode($insert_history);
        }
    }

    function Edit_Delivery($product, $cp_id)
    {
        if ($GLOBALS['conn']->connect_errno != 0) {
            setmessage($GLOBALS['conn']->connect_error, 'danger', '');
        }
        $delivery = json_decode(file_get_contents("delivery.cache"));

        if (count($delivery) > 0) {
            $update_flag = false;
            if ($delivery[0]->supp_id != $product['company']) {
                $sql = "UPDATE `pcbms_db`.`consigned_product` SET `supp_id`='{$product['company']}' WHERE  `cp_id`='$cp_id';";
                $result = $GLOBALS['conn']->query($sql);
                if ($result && $GLOBALS['conn']->affected_rows > 0) {
                    $update_flag = true;
                }
            }

            $update_flag = $this->CheckCountChanges($delivery, $product, $cp_id);

            for ($i = 0; $i < count($product['product_id']); $i++) {
                $sql = "UPDATE `pcbms_db`.`consigned_details` SET `prod_id`='{$product['product_id'][$i]}',`particulars`='{$product['particulars'][$i]}',`unit_price`='{$product['unit_price'][$i]}',`interest`='{$product['interest'][$i]}',`selling_price`='{$product['selling_price'][$i]}',`quantity`='{$product['quantity'][$i]}',`amount`='{$product['amount'][$i]}',`expiry_date`='{$product['date_expiry'][$i]}' WHERE  `item_id`='{$product['item_id'][$i]}' AND `cp_id`='$cp_id';";
                $result = $GLOBALS['conn']->query($sql);
                if ($result && $GLOBALS['conn']->affected_rows > 0) {
                    $update_flag = true;
                } else
                    $update_flag = false;
            }

            if ($update_flag)
                setmessage("Updated the delivery", "success");
            else if (!$update_flag && $GLOBALS['conn']->errno == 0)
                setmessage("No changes made", "info");
            else
                setmessage("Error {$GLOBALS['conn']->errno}: {$GLOBALS['conn']->error}", "danger");
        } else {
            setmessage("Error reading delivery cache file", "danger");
        }
    }

    function Remove_DeliverdItem($cp_id, $item_id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`consigned_details` WHERE  `cp_id`=$cp_id AND `item_id`=$item_id;";
        $result = parent::Delete_Query();
        echo json_encode($result);
    }

    function CheckCountChanges($original, $update, $cp_id)
    {
        $update_size = count($update['item_id']);
        $original_size = count($original);
        $orig_item_id = [];
        $updt_item_id = [];
        $deleted_item = [];
        $added_item = [];
        $change_flag = false;

        for ($i = 0; $i < $original_size; $i++)
            $orig_item_id[] = $original[$i]->item_id;
        for ($i = 0; $i < $update_size; $i++)
            $updt_item_id[] = $update['item_id'][$i];

        $added_item = array_diff($updt_item_id, $orig_item_id);
        $deleted_item = array_diff($orig_item_id, $updt_item_id);

        foreach ($deleted_item as $delete) {
            if ($this->Remove_DeliverdItem($cp_id, $delete)) {
                $change_flag = true;
            }
        }
        foreach ($added_item as $add) {
            $index = array_search($add, $updt_item_id);
            if ($this->CreateConsignedDetail($update, $index, $cp_id)) {
                $change_flag = true;
            }
        }

        return $change_flag;
    }

    function Remove_Delivery($cp_id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`consigned_details` WHERE  `cp_id`=$cp_id;";
        $result = parent::Delete_Query();
        echo json_encode($result);
    }

    function get_Suppliers()
    {
        $this->sql = "SELECT * FROM `supplier`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function get_Contact_Person($company)
    {
        $company = validate($company);
        $this->sql = "SELECT `contact_person`,`phone` FROM `supplier` WHERE `company`='$company';";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function get_Personnels()
    {
        $this->sql = "SELECT * FROM `personnel`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function get_Products()
    {
        $this->sql = "SELECT * FROM `product`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function get_Product_Info($prod_id)
    {
        $this->sql = "SELECT `unit`,`shelf_life` FROM `product` WHERE `prod_id`='$prod_id';";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function get_Consigned_Info($cp_id)
    {
        $this->sql = "SELECT * FROM `get_delivery_detail` WHERE `cp_id`='$cp_id'";
        $result = parent::Select_Query();
        file_put_contents('delivery.cache', json_encode($result));
        var_dump($result);
    }
}

$D = new Delivery();

if (isset($_GET['delete'])) {
    echo "delete";
    return;
    $D->Remove_Delivery($_GET['delete']);
} elseif (isset($_POST['new']) && $_POST['new'] = "delivery") {
    echo "new";
    return;
    $D->New_Delivery($_POST);
} elseif (isset($_POST['updatedelivery']) && isset($_GET['cp_id'])) {
    echo "update";
    return;
    $D->Edit_Delivery($_POST, $_GET['cp_id']);
} elseif (isset($_POST['company'])) {
    $D->get_Contact_Person($_POST['company']);
    unset($_POST['company']);
} elseif (isset($_POST['prod_id'])) {
    $D->get_Product_Info($_POST['prod_id']);
    unset($_POST['prod_id']);
} elseif (isset($_POST['cp_id'])) {
    $D->get_Consigned_Info($_POST['cp_id']);
    unset($_POST['cp_id']);
} elseif (isset($_POST['retrieve']) && $_POST['retrieve'] == 'delivery'){
    $D->Get_All_Deliveries();
} elseif(isset($_POST['date_delivery'])){
    var_dump($_POST);
}
