<?php
require_once dirname(__DIR__, 1) . '\php\db.php';
require_once dirname(__DIR__, 1) . '\php\messagebox.php';
require_once dirname(__DIR__, 1) . '\php\cleaner.php';

function CreateCustomer($name, $address){
    return Insert_Query('customer', ['name','address'], [&$name, &$address]);
}

function CreateSale($sale){
    $result = CreateCustomer($sale['customer_name'], $sale['address']);
    $result = json_decode($result);
    if($result->affected_rows > 0){
        $customer_id = $result->last_inserted;
        if($customer_id > 0){
            $cust_id = $customer_id;
            $date = $sale['date_purchase'];
            $user_id = $sale['user_id'];
            $result = Insert_Query('sales', ['customer','date_issued','user_id'], [&$cust_id, &$date, &$user_id]);
            $result = json_decode($result);
            if($result->affected_rows > 0){
                $sales_id = $result->last_inserted;
                $len = count($sale['products']);
                $res = [];
                for($i = 1; $i<=$len; $i++){
                    $row = $sale['products'][$i];
                    $result = Insert_Query('sale_details', ['sale_id','prod_id','qty_sold','amount_sold'], [&$sales_id, &$row[0], &$row[2], &$row[4]]);
                    $result = json_decode($result);
                    if($result->affected_rows > 0){
                        setmessage('Purchased successfully','success');
                        $res[] = $result;
                    } 
                }
                echo json_encode($res);
            }else{
                setmessage('Error creating new sales record','danger');
                echo 'Error creating new sales record';
            }
        }
    }else{
        setmessage('Error creating new customer','danger');
        echo 'Error creating new customer';
    }
}

if(isset($_POST['newpurchase'])){
    //var_dump($_POST);
    CreateSale($_POST);
}

// array(3) {
//     [1]=>
//     array(5) {
//       [0]=>
//       string(3) "202"
//       [1]=>
//       string(26) "Chocolate milkshake frappe"
//       [2]=>
//       string(1) "6"
//       [3]=>
//       string(3) "200"
//       [4]=>
//       string(4) "1200"
//     }
//     [2]=>
//     array(5) {
//       [0]=>
//       string(3) "203"
//       [1]=>
//       string(16) "Milkshake frappe"
//       [2]=>
//       string(1) "5"
//       [3]=>
//       string(3) "300"
//       [4]=>
//       string(4) "1500"
//     }
//     [3]=>
//     array(5) {
//       [0]=>
//       string(3) "204"
//       [1]=>
//       string(6) "Frappe"
//       [2]=>
//       string(1) "8"
//       [3]=>
//       string(3) "400"
//       [4]=>
//       string(4) "3200"
//     }
//   }

