<?php
require_once dirname(__DIR__, 3) . '\php\db.php';
require_once dirname(__DIR__, 3) . '\php\messagebox.php';
require_once dirname(__DIR__, 3) . '\php\cleaner.php';

class Supplier extends DB
{
    function __construct()
    {
        parent::__construct();
    }

    function Get_All_Suppliers()
    {
        $this->sql = "SELECT * FROM `pcbms_db`.`supplier` ORDER BY `supp_id` ASC";
        $result = parent::Select_Query();
        if (gettype($result) == 'array') {
            echo json_encode($result);
        } else {
            echo json_encode($result->error);
        }
    }

    function New_Supplier($supplier)
    {
        $company = validate($supplier['company']);
        $contact_person = validate($supplier['contact_person']);
        $sex = validate($supplier['sex']);
        $number = validate($supplier['number']);
        $address = validate($supplier['address']);

        $this->sql = "INSERT INTO `pcbms_db`.`supplier` (`company`, `contact_person`, `sex`, `phone`, `address`) VALUES ('$company','$contact_person','$sex','$number','$address');";
        $result = parent::Insert_Query();
        echo json_encode($result);
    }

    function Edit_Supplier($supplier, $id)
    {
        $company = validate($supplier['company']);
        $contact_person = validate($supplier['contact_person']);
        $sex = validate($supplier['sex']);
        $number = validate($supplier['number']);
        $address = validate($supplier['address']);
        $id = validate($id);

        $this->sql = "UPDATE `pcbms_db`.`supplier` SET `company`='$company', `contact_person`='$contact_person', `sex`='$sex', `phone`='$number', `address`='$address' WHERE  `supp_id`=$id;";
        $result = parent::Update_Query();
        echo json_encode($result);
    }

    function Remove_Supplier($id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`supplier` WHERE  `supp_id`=$id;";
        $result = parent::Delete_Query();
        echo json_encode($result);
    }
}

$S = new Supplier();

if (isset($_POST['delete'])) {
    $S->Remove_Supplier($_POST['delete']);
} elseif (isset($_POST['new'])) {
    $S->New_Supplier($_POST);
} elseif (isset($_POST['update']) && isset($_POST['id'])) {
    $S->Edit_Supplier($_POST, $_POST['id']);
} elseif(isset($_POST['retrieve'])){
    $S->Get_All_Suppliers();
}

