<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="/manager/user/user_crud.php" class="box" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-outline mb-2">
                        <label class="form-label" for="username-form">Username<span style="color:red">*</span></label>
                        <input type="text" id="username-form" class="form-control" name="username" required />
                    </div>
                    <div class="form-outline mb-2">
                        <label class="form-label" for="password-form">Password<span style="color:red">*</span>  </label>
                        <input type="password" id="password-form" class="form-control" name="password" required />
                    </div>
                    <input type="button" id="login-form-btn" class="btn btn-primary btn-block mb-2 mt-4" name="login" value="Login" />
                </div>
            </div>
        </form>
    </div>
</div>
