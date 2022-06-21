<?php
require_once 'user_crud.php';
User::Check_Permission(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.min.css">
    <!-- Form CSS -->
    <link type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/css/sidebar-style-sheet.css">
    <link rel="stylesheet" href="/css/main-style-sheet.css">
    <link rel="stylesheet" href="/css/profile-style.css">
    <link rel="stylesheet" href="/css/alertbox.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/sweetalert2.all.min.js"></script>
    <title>Dashboard | PCBMS</title>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar Holder -->
        <?php require dirname(__DIR__, 2) . "\layout\sidebar\index.php" ?>
        <!-- Page Content Holder -->
        <div id="content">
            <?php
            require dirname(__DIR__, 2) . '\layout\navbar\navbar-user.php';
            require_once dirname(__DIR__, 2) . '\php\icon.php';
            require_once dirname(__DIR__, 2) . '\php\messagebox.php';
            alertbox();
            ?>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-4 pb-5">
                        <!-- Account Sidebar-->
                        <div class="author-card pb-3">
                            <div class="author-card-cover" style="background-image: url(/img/pangasugan.jpg);"></div>
                            <div class="author-card-profile">
                                <div class="author-card-avatar d-flex justify-content-center">
                                    <img id="prodfilepicture" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($_SESSION['picture']); ?>" />
                                </div>
                                <button id="profile-btn" type="button" class="btn btn-sm btn-outline-primary border border-light d-flex align-items-center" data-toggle="tooltip" data-placement="right" title="Change profile picture">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#edit_profile_icon" />
                                    </svg>
                                </button>
                                <div class="author-card-details">
                                    <h5 class="author-card-name text-lg"><?php echo $_SESSION['name'] ?></h5><span class="author-card-position"><?php echo $_SESSION['position'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div id="profile-upload-container">
                            <form action="user_crud.php" method="POST" id="profile-upload" enctype="multipart/form-data">
                                <div class="input-group mb-3 form-control-plaintext">
                                    <div class="custom-file">
                                        <input type="file" accept="image/*" name="image" class="custom-file-input" id="inputGroupFile02">
                                        <label class="custom-file-label" id="filename-selected" for="inputGroupFile02">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <input type="submit" name="submitpicture" value="Submit" class="input-group-text">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Profile Settings-->
                    <div class="col-lg-8 pb-5" id="personal-info-container">
                        <form class="row" action="user_crud.php" method="POST">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account-fn">First Name</label>
                                    <input class="form-control-sm" type="text" name="account-fn" id="account-fn" value="<?php echo $_SESSION['fname'] ?>" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <select name="position" id="position" name="position" class="form-control-sm" required>
                                        <option value='<?php echo $_SESSION["position"] ?>' selected hidden><?php echo $_SESSION["position"] ?></option>
                                        <option value="Store Manager">Store Manager</option>
                                        <option value="Cashier">Cashier</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account-mn">Middle Name</label>
                                    <input class="form-control-sm" type="text" id="account-mn" name="account-mn" value="<?php echo $_SESSION['mname'] ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account-username">New Username</label>
                                    <input class="form-control-sm" type="text" id="account-username" name="account-username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account-ln">Last Name</label>
                                    <input class="form-control-sm" type="text" id="account-ln" name="account-ln" value="<?php echo $_SESSION['lname'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account-pass">New Password</label>
                                    <input class="form-control-sm" type="password" id="account-pass" name="account-pass">
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="mt-2 mb-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" id="update-profile-btn" class="btn btn-primary d-flex align-items-center" data-toggle="tooltip" data-placement="right" title="Update personal information">
                                                <svg class="bi me-2" width="16" height="16">
                                                    <use xlink:href="#update_icon" />
                                                </svg>
                                                &nbsp;Update Profile
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" id="unregister-profile-btn" onclick="DeleteProfile(<?= $_SESSION['user'] ?>)" class="btn btn-outline-danger d-flex align-items-center" data-toggle="tooltip" data-placement="right" title="Remove your personal information">
                                                <svg class="bi me-2" width="16" height="16">
                                                    <use xlink:href="#delete_icon" />
                                                </svg>
                                                &nbsp;Unregister
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="reset" id="cancel-profile-btn" class="btn btn-warning d-flex align-items-center" data-toggle="tooltip" data-placement="right" title="Discard the changes">
                                                <svg class="bi me-2" width="16" height="16">
                                                    <use xlink:href="#cancel_icon" />
                                                </svg>
                                                &nbsp;Cancel
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="submit" id="save-profile-btn" name="save-profile-btn" class="btn btn-success d-flex align-items-center" data-toggle="tooltip" data-placement="right" title="Commit changes">
                                                <svg class="bi me-2" width="16" height="16">
                                                    <use xlink:href="#save_icon" />
                                                </svg>
                                                &nbsp;Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Content Holder -->
    </div>
    <!--For Toggling the sidebar-->
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/js/popper.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <!--For Datatable Important-->
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script type="text/JavaScript" src="/js/jquery.dataTables.min.js"></script>
    <script type="text/JavaScript" src="/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript" src="/js/sidebar-toggle.js"></script>
    <script type="text/javascript" src="/js/alertbox.js"></script>
    <script type="text/javascript">
        var cancelbtn = document.getElementById('cancel-profile-btn');
        var profileuploadcontainer = document.getElementById('profile-upload-container');
        var profileupdatebtn = document.getElementById('update-profile-btn');
        var savebtn = document.getElementById('save-profile-btn');
        var prev = document.getElementById("prodfilepicture");
        var filename_selected = document.getElementById('filename-selected');
        var orginal_pic;

        $(document).ready(function() {
            $('#user-sidebar').addClass('active');
            cancelbtn.style.visibility = 'hidden';
            profileuploadcontainer.style.display = 'none';
            savebtn.style.visibility = 'hidden';
            EnableFields(true);
        });

        $(document).on("click", ".browse", function() {
            var file = $(this).parents().find(".file");
            file.trigger("click");
        });
        var fileName;

        $('input[type="file"]').change(function(e) {
            fileName = e.target.files[0].name;
            $("#file").val(fileName);
            filename_selected.innerText = fileName;
            orginal_pic = prev.src;
            var reader = new FileReader();
            reader.onload = function(e) {
                prev.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        });

        $('#profile-btn').on('click', function() {
            if (profileuploadcontainer.style.display == 'block') {
                profileuploadcontainer.style.display = 'none';
                this.innerHTML = '<svg class="bi me-2" width="16" height="16"><use xlink:href="#edit_profile_icon" /></svg>'
                if (fileName != null)
                    prev.src = orginal_pic;
                filename_selected.innerText = 'Choose file';
            } else {
                profileuploadcontainer.style.display = 'block';
                this.innerHTML = '<svg class="bi me-2" width="16" height="16"><use xlink:href="#cancel_icon" /></svg>';
            }
        });

        $('#update-profile-btn').on('click', function() {
            EnableFields(false);
            this.style.visibility = 'hidden';
            savebtn.style.visibility = 'visible';
            cancelbtn.style.visibility = 'visible';
        });

        $('#cancel-profile-btn').on('click', function() {
            EnableFields(true);
            cancelbtn.style.visibility = 'hidden';
            savebtn.style.visibility = 'hidden';
            profileupdatebtn.style.visibility = 'visible';
        });

        function EnableFields(status) {
            var inputs = document.getElementById('personal-info-container').querySelectorAll('input,select');
            for (var i = 0; i < inputs.length; ++i) {
                inputs[i].disabled = status;
            }
        }

        function DeleteProfile(user_id) {
            ConfirmDeleteAccount('Unregister', 'Confirm to remove your account permanently?', 'Yes', 'Cancel', user_id);
        }
    </script>
</body>

</html>