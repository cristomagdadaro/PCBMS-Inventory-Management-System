<?php
require_once dirname(__DIR__, 1) . '\php\db.php';
require_once dirname(__DIR__, 1) . '\php\messagebox.php';
require_once dirname(__DIR__, 1) . '\php\cleaner.php';

class Sales extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function CreateCustomer($name, $address)
    {
        $this->sql = "INSERT INTO `pcbms_db`.`customer` (`name`, `address`) VALUES ('{$name}', '{$address}');";
        $result = parent::Insert_Query();
        return json_encode($result);
    }

    function CreateSale($sale)
    {
        $result = $this->CreateCustomer($sale['customer_name'], $sale['address']);
        $result = json_decode($result);
        if (isset($result->inserted_id) && $result->inserted_id > 0) {
            $this->sql = "INSERT INTO `pcbms_db`.sales (`customer`,`date_issued`,`user_id`) VALUES ('{$result->inserted_id}','{$sale['date_purchase']}','{$sale['user_id']}');";
            $result = parent::Insert_Query();
            $result = json_decode(json_encode($result));
            if (isset($result->inserted_id) && $result->inserted_id) {
                $sales_id = $result->inserted_id;
                $len = count($sale['prod_id']);
                $res = [];
                for ($i = 0; $i < $len; $i++) {
                    $prod_id = $sale['prod_id'][$i];
                    $quantity = $sale['quantity'][$i];
                    $amount = $sale['amount'][$i];
                    $this->sql = "INSERT INTO `sale_details` (`sale_id`,`prod_id`,`qty_sold`,`amount_sold`) VALUES ($sales_id, $prod_id, $quantity, $amount);";
                    $result = parent::Insert_Query();
                    $result = json_decode(json_encode($result));
                    if (isset($result->inserted_id) && $result->inserted_id) {
                        array_push($res, $result);
                    }
                }
                echo json_encode(array("affected_rows" => count($res)));
            } else {
                echo json_encode(array("error" => 'Error creating new sales record'));
            }
        } else {
            echo json_encode(array("error" => 'Error creating new customer'));
        }
    }

    function get_transaction(){
        $this->sql = 'SELECT * FROM `get_transactions`';
        $result = parent::Select_Query();
        echo json_encode($result);
    }
}

$S = new Sales();
//echo json_encode($_POST);
if (isset($_POST['newpurchase'])) {
    $S->CreateSale($_POST);
} elseif (isset($_POST['transaction'])) {
    $S->get_transaction();
}
