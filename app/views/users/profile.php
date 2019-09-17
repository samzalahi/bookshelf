<?php require APPROOT . '/views/include/header.php'?>
    <div class="content-body">
        <div class="sidebar">
            <?php require APPROOT . '/views/users/sidebar.php'?>
        </div>
        <div class="main">
        <?php echo flash('flash_message'); ?>
            <div class="col-md-12">
                <div class="card card-body bg-light mt-4 position-relative">
                    <div class="row col-md-12">
                        <h1 class="">Profile settings</h1>
                    </div>
                    <form action="<?php echo URLROOT;?>/users/profile/<?php echo $_SESSION['user_id'] ?>" method="post" class="d-flex">
                        <div class="col-sm-5 mr-3 form-left">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Name:<sup>*</sup></label>
                                <input type="text" name="name" autocomplete="off" class="col-sm-12 form-control
                                <?php echo (!empty($data['name_err'])) ? 'is-invalid' : '' ?>"
                                value="<?php echo $data['name']?>">
                                <span class="invalid-feedback mr-auto"><?php echo $data['name_err'] ?></span>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email:<sup>*</sup></label>
                                <input type="text" readonly name="email" autocomplete="off" class="col-sm-12 form-control
                                <?php echo (!empty($data['email_err'])) ? 'is-invalid' : '' ?>"
                                value="<?php echo $data['email']?>">
                                <span class="invalid-feedback mr-auto"><?php echo $data['email_err'] ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-form-label">New Password:<sup>*</sup></label>
                                <input type="password" name="password" autocomplete="off" class="col-sm-12 form-control
                                <?php echo (!empty($data['password_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter new password"
                                value="<?php echo $data['password']?>">
                                <span class="invalid-feedback mr-auto"><?php echo $data['password_err'] ?></span>
                            </div>
                            <div class="form-group">
                                <label for="user_type" class="col-form-label">User Type:<sup>*</sup></label>
                                <select name="user_type" class="form-control form-control-md">
                                    <?php if ($data['user_type'] == 'admin') : ?>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    <?php else : ?>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="update">
                            <button name="save" class="btn btn-success btn-block"><i class="fas fa-cloud-upload-alt"></i> Save</button>
                        </div>                       
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>
