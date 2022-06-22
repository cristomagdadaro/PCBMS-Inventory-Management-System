<?php
require_once dirname(__DIR__, 2) . '\php\db.php';
require_once dirname(__DIR__, 2) . '\php\messagebox.php';
require_once dirname(__DIR__, 2) . '\php\cleaner.php';

class Order extends DB
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_All_Order()
    {
        $this->sql = "SELECT * FROM `pcbms_db`.`get_order_summary`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    private function New_Order($company, $personnel, $date_order)
    {
        $this->sql = "INSERT INTO `pcbms_db`.`orders` (`comp_id`, `user_id`, `order_date`) VALUES ('{$company}', '{$personnel}', '{$date_order}');";
        $result = parent::Insert_Query();
        return $result;
    }

    private function New_OrderDetail($order, $index, $or_id)
    {
        $prod_id = $order["product_id"][$index];
        $quantity = $order["quantity"][$index];
        $this->sql = "INSERT INTO `pcbms_db`.`order_details` (`order_id`, `prod_id`, `quantity`) VALUES ('$or_id', '{$prod_id}', '{$quantity}');";
        $result = parent::Insert_Query();
        return $result;
    }

    public function CreateOrder($order)
    {
        $order_result = $this->New_Order($order["company"], $order["personnel"], $order["date_order"]);
        $or_id = $order_result['inserted_id'];
        if ($or_id) {
            $res = 0;
            for ($i = 0; $i < count($order["product_id"]); $i++) {
                $order_detail = $this->New_OrderDetail($order, $i, $or_id);
                if (!$order_detail['inserted_id']) {
                    $this->sql = "DELETE FROM `pcbms_db`.`orders` WHERE  `or_id`='{$or_id}';";
                    parent::Delete_Query();
                    echo json_encode($order_detail);
                    return;
                }else
                   $res++;
            }
            echo json_encode(array("affected_row"=>$res));
        } else
            echo json_encode($order_result);
    }

    public function Edit_Order($product, $or_id)
    {
        $order = json_decode(file_get_contents("order.cache"));
        if (count($order) > 0) {
            $update_flag = false;
            $single_flag = false;
            if ($order[0]->supp_id != $product['company'] || $order[0]->status != $product['status']) {
                $this->sql = "UPDATE `pcbms_db`.`orders` SET `comp_id`='{$product['company']}', `status`='{$product['status']}' WHERE  `or_id`='$or_id';";
                $result = parent::Update_Query();
                if ($result['affected_rows'] > 0) {
                    $single_flag = true;
                }
                if (isset($result['errno']) && $result['errno'] != 0){
                    echo json_encode($result);
                    $single_flag = false;
                    return;
                }
            }

            $update_flag = $this->Check_CountChanges($order, $product, $or_id);

            for ($i = 0; $i < count($product['product_id']); $i++) {
                $this->sql  = "UPDATE `pcbms_db`.`order_details` SET `prod_id`='{$product['product_id'][$i]}', `quantity`='{$product['quantity'][$i]}' WHERE  `item_id`='{$product['item_id'][$i]}' AND `order_id`='$or_id';";
                $result = parent::Update_Query();
                if ($result['affected_rows'] > 0) {
                    $update_flag = true;
                }
                if (isset($result['errno']) && $result['errno'] != 0){
                    echo json_encode($result);
                    $update_flag = false;
                    return;
                }
            }

            if ($update_flag || $single_flag){
                setmessage("Updated the order", "success");
                echo json_encode(array("errno"=>0,"error"=>null,"affected_rows"=>count($product['product_id'])));
            }
            else if (!$update_flag && !$single_flag){
                setmessage("No changes made", "success");
                echo json_encode(array("errno"=>0,"error"=>null,"affected_rows"=>0));
            }
        } else {
            setmessage("Error reading order cache file", "danger");
            echo json_encode(array("errno"=>1,"error"=>"Error reading order cache file","affected_rows"=>-1));
        }
    }

    private function Remove_OrderedItem($or_id, $item_id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`order_details` WHERE  `order_id`=$or_id AND `item_id`=$item_id;";
        $result = parent::Delete_Query();
        return $result['affected_rows'] > 0;
    }

    function Check_CountChanges($original, $update, $or_id)
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
            if ($this->Remove_OrderedItem($or_id, $delete)) {
                $change_flag = true;
            }
        }
        foreach ($added_item as $add) {
            $index = array_search($add, $updt_item_id);
            if ($this->New_OrderDetail($update, $index, $or_id)) {
                $change_flag = true;
            }
        }
        return $change_flag;
    }

    public function Remove_Order($or_id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`orders` WHERE  `or_id`=$or_id;";
        $result = parent::Delete_Query();
        echo json_encode($result);
    }

    public function get_Suppliers()
    {
        $this->sql = "SELECT * FROM `supplier`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    public function get_Contact_Person($company)
    {
        $this->sql = "SELECT `contact_person`,`phone` FROM `supplier` WHERE `company`='$company';";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    public function get_Personnels()
    {
        $this->sql = "SELECT * FROM `personnel`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    public function get_Product_Info($prod_id)
    {
        $this->sql = "select * from `product` WHERE `prod_id`='$prod_id'";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    public function get_Products()
    {
        $this->sql = "select * from `product`;";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    public function get_Order_Info($or_id)
    {
        $this->sql = "select * from `get_order_detail` WHERE `order_id`='$or_id'";
        $result = parent::Select_Query();
        echo json_encode($result);
        file_put_contents('order.cache', json_encode($result));
    }
}

$O = new Order();
if (isset($_POST['new']) && $_POST['new'] == 'order') {
    $O->CreateOrder($_POST);
} elseif (isset($_POST['delete'])) {
    $O->Remove_Order($_POST['delete']);
} elseif (isset($_POST['update'])) {
    $O->Edit_Order($_POST, $_POST['or_id']);
} elseif (isset($_POST['company_name'])) {
    $O->get_Contact_Person($_POST['company_name']);
} elseif (isset($_POST['prod_id'])) {
    $O->get_Product_Info($_POST['prod_id']);
} elseif (isset($_POST['or_id'])) {
    $O->get_Order_Info($_POST['or_id']);
} elseif(isset($_POST['retrieve']) && $_POST['retrieve'] == 'order'){
    $O->Get_All_Order();
} elseif(isset($_POST['getsuppliers'])){
    $O->get_Suppliers();
} elseif(isset($_POST['getproducts'])){
    $O->get_Products();
}

?>