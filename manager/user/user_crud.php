<?php
require_once '../../php/db.php';
require_once '../../php/cleaner.php';
require_once '../../php/messagebox.php';

date_default_timezone_set('Asia/Manila');
session_start();
class User extends DB
{
    private $table = 'personnel';

    function __construct()
    {
        parent::__construct();
    }

    // Prevents unathorized user from changing to an a different webpage that isn't their role. 
    public static function Check_Permission($pos)
    {
        $positions = ['Store Manager', 'Cashier'];
        $_SESSION["session_status"] == "authorized";
        if ($_SESSION["session_status"] != "authorized"){
            setmessage('Unauthrized Access', 'danger');
            header("location: /");
        } else {
            $message = 'You\'re unauthorize to access that webpage';
            if ($_SESSION["position"] != $positions[$pos] && $_SESSION["position"] == $positions[1]) {
                setmessage($message, 'danger'); //for cashier
                header("Location: /cashier/");
            } elseif ($_SESSION["position"] != $positions[$pos] && $_SESSION["position"] == $positions[0]) {
                setmessage($message, 'danger'); //for manager
                header("Location: /manager/");
            }
        }
    }

    // Create a new user account
    function New_User($user)
    {
        $fname = validate($user['fname']);
        $lname = validate($user['lname']);
        $mname = validate($user['mname']);
        $designation = validate($user['designation']);
        $username = validate($user['username']);
        $password = validate($user['password']);

        $this->sql = "INSERT INTO `pcbms_db`.{$this->table} (fname, mname, lname, designation, username, `password`) VALUES ('$fname','$mname','$lname','$designation','$username','$password');";
        $result = parent::Insert_Query();
        echo json_encode($result);
    }

    // Retrieve a specific user, used when signing in.
    function Get_A_User($username, $password)
    {
        $username = validate($username);
        $password = validate($password);
        $this->sql = "SELECT `user_id`, `fname`, `mname`, `lname`, `designation`, `picture` FROM `pcbms_db`.{$this->table} WHERE `username` = '$username' AND `password` = '$password' LIMIT 1;";
        $result = parent::Select_Query();

        if (!isset($result['errno'])) {
            if(sizeof($result) > 0){
                $user = $result[0];
        
                $mid = !empty($user['mname']) ? "{$user['mname'][0]}." : "";
                $_SESSION["name"] = "{$user['fname']} {$mid} {$user['lname']}";
                $_SESSION["fname"] = $user['fname'];
                $_SESSION["mname"] = $user['mname'];
                $_SESSION["lname"] = $user['lname'];
                $_SESSION["user"] = $user['user_id'];
                $_SESSION["position"] =  $user['designation'];
                $_SESSION["session_status"] = "authorized";
                if (!empty(base64_encode($user['picture']))) {
                    $_SESSION["picture"] = $user['picture'];
                }
                
                echo json_encode(array(["user_id"=>$user['user_id'],"fname"=>$user['fname'],"mname"=>$user['mname'],"lname"=>$user['lname'],"designation"=>$user['designation']]));
    
                setmessage("Welcome {$_SESSION["name"]}", "info");
            }else{
                echo json_encode(array(["user_id"=>-1]));
                $_SESSION["session_status"] = "not authorized";
            }
        } else {
            var_dump($result);
            $_SESSION["session_status"] = "not authorized";
        }
    }

    // Add image to an account.
    function UploadPicture()
    {
        if (!empty($_FILES["image"]["name"])) {
            $fileName = basename($_FILES["image"]["name"]);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG');
            if (in_array($fileType, $allowTypes)) {
                $image = $_FILES['image']['tmp_name'];
                $imgContent = addslashes(file_get_contents($image));
                $this->sql = "UPDATE `pcbms_db`.`personnel` SET `picture` = '$imgContent' WHERE `user_id` = {$_SESSION['user']}";
                $result = parent::Update_Query();

                if (!isset($result['errno'])) {
                    if($result['affected_rows'] > 0){
                        $im = file_get_contents($image);
                        header("Content-type: image/jpeg");
                        $_SESSION['picture'] = $im;
                        setmessage("File uploaded successfully.", "success");
                    }else{
                        setmessage("No changes made", "info");
                    }
                } else {
                    setmessage("File upload failed {$result['errno']}: {$result['error']}", "danger");
                }
            } else {
                setmessage('Sorry, only JPG, JPEG, and PNG files are allowed to upload.', "danger");
            }
        } else {
            setmessage('Please select an image file to upload.', "danger");
        }
        header('Location: ./');
    }

    function UpdateUser($user)
    {
        $user['account-fn'] = validate($user['account-fn']);
        $user['account-mn'] = validate($user['account-mn']);
        $user['account-ln'] = validate($user['account-ln']);
        $user['account-username'] = validate($user['account-username']);

        $sql = "UPDATE `pcbms_db`.`personnel` SET `fname`='{$user['account-fn']}', `mname`='{$user['account-mn']}', `lname`='{$user['account-ln']}', `designation`='{$user['position']}', `username`='{$user['account-username']}', `password`='{$user['account-pass']}' WHERE `user_id`='{$_SESSION['user']}'";
        if (empty($user['account-username']) && !empty($user['account-pass']))
            $sql = "UPDATE `pcbms_db`.`personnel` SET `fname`='{$user['account-fn']}', `mname`='{$user['account-mn']}', `lname`='{$user['account-ln']}', `designation`='{$user['position']}', `password`='{$user['account-pass']}' WHERE `user_id`='{$_SESSION['user']}'";
        else if (empty($user['account-pass']) && !empty($user['account-username']))
            $sql = "UPDATE `pcbms_db`.`personnel` SET `fname`='{$user['account-fn']}', `mname`='{$user['account-mn']}', `lname`='{$user['account-ln']}', `designation`='{$user['position']}', `username`='{$user['account-username']}' WHERE `user_id`='{$_SESSION['user']}'";
        else if (empty($user['account-username']) && empty($user['account-username']))
            $sql = "UPDATE `pcbms_db`.`personnel` SET `fname`='{$user['account-fn']}', `mname`='{$user['account-mn']}', `lname`='{$user['account-ln']}', `designation`='{$user['position']}' WHERE `user_id`='{$_SESSION['user']}'";

        $this->sql = $sql;
        $result = $this->Update_Query();
        var_dump($result);
        if (!isset($result['errno'])) {
            if($result['affected_rows'] > 0){
                $mid = !empty($user['account-mn']) ? "{$user['account-mn'][0]}." : "";
                $_SESSION["name"] = "{$user['account-fn']} {$mid} {$user['account-ln']}";
                $_SESSION["fname"] = $user['account-fn'];
                $_SESSION["mname"] = $user['account-mn'];
                $_SESSION["lname"] = $user['account-ln'];
                $_SESSION["position"] =  $user['position'];
                setmessage("Successfully updated", "success");
            }else{
                setmessage("No changes made", "info");
            }
        } else {
            setmessage("Error {$GLOBALS['conn']->errno}: {$GLOBALS['conn']->error}", "danger");
        }
        header('Location: ./');
    }

    function Remove_User($id)
    {
        $this->sql = "DELETE FROM `pcbms_db`.`personnel` WHERE  `user_id`=$id;";
        $result = parent::Delete_Query();
        echo json_encode($result);
    }
}

$S = new User();

if (isset($_POST['register'])) {
    $S->New_User($_POST);
} elseif (isset($_POST['login'])) {
    $S->Get_A_User($_POST['username'], $_POST['password']);
} elseif (isset($_POST['save-profile-btn'])) {
    $S->UpdateUser($_POST);
} elseif (isset($_POST['submitpicture'])) {
    $S->UploadPicture();
} elseif (isset($_POST['delete'])){
    $S->Remove_User($_POST['delete']);
}