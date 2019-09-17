<?php require APPROOT . '/views/include/header.php'?>
<div class="content-body">
    <div class="sidebar">
        <?php require APPROOT . '/views/users/sidebar.php'?>
    </div>
    <div class="main">
        <div class="col-sm-12">
            <div class="card card-body bg-light mt-5">
                <h2>Create An Account</h2>
                <p>Please fill out this form to register</p>
                <form action="<?php echo URLROOT;?>/users/register" method="post">
                    <div class="col-sm-5 mr-3 form-left">
                        <div class="form-group">
                            <label for="name">Name: <sup>*</sup></label>
                            <input type="text" name="name" class="col-sm-12 form-control
                            <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter name"
                            value="<?php echo $data['name']; ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email: <sup>*</sup></label>
                            <input type="email" name="email" class="col-sm-12 form-control
                            <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter email"
                            value="<?php echo $data['email']; ?>">
                            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password: <sup>*</sup></label>
                            <input type="password" name="password" class="col-sm-12 form-control
                            <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter password"
                            value="<?php echo $data['password']; ?>">
                            <small class="text-muted">Must be more than 6 characters.</small>
                            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                            <input type="password" name="confirm_password" class="col-sm-12 form-control
                            <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter confirm password"
                            value="<?php echo $data['confirm_password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="user_type">User Type: <sup>*</sup></label>
                            <select name="user_type" class="col-sm-12 form-control">
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-user-plus"></i> Register</button>
                            </div>
                            <div class="col">
                                <a href="<?php echo URLROOT; ?>/books/index" class="btn btn-success btn-block">
                                    <i class="fas fa-backward"></i> Go Back
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/include/footer.php'?>