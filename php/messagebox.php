<?php
function setmessage($msg, $type)
{
    $_SESSION['message'] = $msg;
    $_SESSION['msg_type'] = $type;
    return true;
}

function alertbox()
{
    if (isset($_SESSION['message'])) {
        if ($_SESSION['msg_type'] == 'danger') { ?>
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?php echo $_SESSION['message'] ?>
            </div>
        <?php } elseif ($_SESSION['msg_type'] == 'success') { ?>
            <div class="alert success">
                <span class="closebtn">&times;</span>
                <strong>Success!</strong> <?php echo $_SESSION['message'] ?>
            </div>
        <?php } elseif ($_SESSION['msg_type'] == 'info') { ?>
            <div class="alert info">
                <span class="closebtn">&times;</span>
                <?php echo $_SESSION['message'] ?>
            </div>
        <?php } elseif ($_SESSION['msg_type'] == 'warning') { ?>
            <div class="alert warning">
                <span class="closebtn">&times;</span>
                <strong>Warning!</strong> <?php echo $_SESSION['message'] ?>
            </div>
<?php }
        unset($_SESSION['message']);
    }
}
?>