<?php
    require 'db.php';
    require 'messagebox.php';

    session_start();
    
    class logout{
        function __construct(){
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params["httponly"]);
            }
            session_unset();
            session_destroy();
            setmessage('Successfully logged out','success');
            $_SESSION['session_status'] = 'not authorized';
            header("location: /");
        }   
    }
    $logout = new logout();
