<?php
require_once dirname(__DIR__, 2) . '\php\db.php';
require_once dirname(__DIR__, 2) . '\php\messagebox.php';
require_once dirname(__DIR__, 2) . '\php\cleaner.php';

class Dashboard extends DB
{
    function Get_Product_Summary()
    {
        $this->sql = "SELECT * FROM `get_product_summary`";
        $result = parent::Select_Query();
        if (gettype($result) == 'array') {
            echo json_encode($result);
        } else {
            echo json_encode($result->error);
        }
    }

    function Get_Sales_Summary()
    {
        $this->sql = "SELECT * FROM `get_sales_summary`";
        $result = parent::Select_Query();
        if (gettype($result) == 'array') {
            echo json_encode($result);
        } else {
            echo json_encode($result->error);
        }
    }
}

$D = new Dashboard();
if (isset($_POST['productsummary'])) {
    $D->Get_Product_Summary();
} elseif (isset($_POST['salessummary'])) {
    $D->Get_Sales_Summary();
}
