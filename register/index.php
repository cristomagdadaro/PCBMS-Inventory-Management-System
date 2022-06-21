<!-- Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registration</h5>
                <button type="reset" class="close" id="close_reg" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="box" method="POST">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-outline mb-2">
                                <label class="form-label" for="fname">First Name<span style="color:red">*</span></label>
                                <input type="text" id="fname" class="form-control" name="fname" required />
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label" for="mname">Middle Name</label>
                                <input type="text" id="mname" class="form-control" name="mname" />
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label" for="lname">Last Name<span style="color:red">*</span></label>
                                <input type="text" id="lname" class="form-control" name="lname" required />
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-outline mb-2">
                                <label class="form-label" for="position">Position<span style="color:red">*</span></label>
                                <select name="position" id="position" class="form-control" required>
                                    <option value="" selected hidden>choose</option>
                                    <option value="Store Manager">Store Manager</option>
                                    <option value="Cashier">Cashier</option>
                                </select>
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label" for="username">Username<span style="color:red">*</span></label>
                                <input type="text" autocomplete="off" id="username" class="form-control" name="username" required />
                            </div>
                            <div class="form-outline mb-2">
                                <label class="form-label" for="password">Password<span style="color:red">*</span></label>
                                <input type="password" autocomplete="off" id="password" class="form-control" name="password" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-outline mt-3">
                        <input type="button" id="register-form-btn" class="btn btn-primary btn-block mb-4" name="register" value="Register" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>