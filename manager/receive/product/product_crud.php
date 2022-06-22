<?php
require_once dirname(__DIR__, 3) . '\php\db.php';
require_once dirname(__DIR__, 3) . '\php\messagebox.php';
require_once dirname(__DIR__, 3) . '\php\cleaner.php';

class Product extends DB
{
    function __construct()
    {
        parent::__construct();
    }

    function Get_All_Products()
    {
        $this->sql = "SELECT * FROM `pcbms_db`.`product`";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function Get_A_Product($id)
    {
        $this->sql = "SELECT * FROM `pcbms_db`.`get_product_sale_details` WHERE `prod_id`=$id LIMIT 1";
        $result = parent::Select_Query();
        echo json_encode($result);
    }

    function New_Product($name, $life, $unit)
    {
        $name = validate($name);
        $this->sql = "INSERT INTO `pcbms_db`.`product` (`prod_name`, `shelf_life`, `unit`) VALUES ('$name',$life,'$unit');";
        $result = parent::Insert_Query();
        echo json_encode($result);
    }

    function Remove_Product($id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`product` WHERE  `prod_id`=$id;";
        $result = parent::Delete_Query();
        echo json_encode($result);
    }

    function Edit_Product($name, $life, $unit, $id)
    {
        $name = validate($name);
        $this->sql = "UPDATE `pcbms_db`.`product` SET `prod_name`='$name', `shelf_life`=$life, `unit`='$unit' WHERE  `prod_id`=$id;";
        $result = parent::Update_Query();
        echo json_encode($result);
    }

    function product_dropdown()
    {
        $sql = "SELECT * FROM `get_product_summary`;";
        $result =  DB::$conn_static->query($sql);
        $ctr_row = 0;
        $ctr_result = 0;
        $str = '';
        $str .= "<option value='' hidden selected>choose</option>";
        while ($row = $result->fetch_assoc()) {
            if($row['total_qty_sold'] < $row['initial_qty'])
                $str .= "<option value='{$row['prod_id']}'>{$row['prod_name']}</option>";
            else
                $ctr_row++;
            $ctr_result++;
        }
        if($ctr_row == $ctr_result)
            $str .= '<option value="" selected>No products available</option>';
        echo $str;
    }
}

$P = new Product();
if (isset($_POST['delete'])) {
    $P->Remove_Product($_POST['delete']);
} elseif (isset($_POST['new'])) {
    $P->New_Product($_POST['prod_name'], $_POST['shelf_life'], $_POST['unit']);
} elseif (isset($_POST['update'])) {
    $P->Edit_Product($_POST['prod_name'], $_POST['shelf_life'], $_POST['unit'], $_POST['update']);
} elseif (isset($_POST['prod_id'])) {
    $P->Get_A_Product($_POST['prod_id']);
} elseif (isset($_POST['retrieve'])) {
    $P->Get_All_Products();
} elseif (isset($_POST['availableprods'])) {
    $P->product_dropdown();
} 
