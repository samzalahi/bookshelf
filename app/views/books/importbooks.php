<?php require APPROOT . '/views/include/header.php'?>
  <div class="content-body">
      <div class="sidebar">
          <?php require APPROOT . '/views/books/sidebar.php'?>
      </div>
      <div class="main">
          <div class="col-md-12">
          <?php echo flash('flash_message'); ?>
              <div class="card card-body bg-light mt-4">
                  <div class="row col-md-12">
                      <h1 class="">Import Books</h1>
                  </div>
                  <form action="<?php echo URLROOT;?>/books/import/" enctype="multipart/form-data" method="post">
                      <div class="form-group">
                          <label for="select_file" class="col-form-label">Select File:</label>
                          <input type="file" name="select_file" class="col-sm-12 form-control-file
                          <?php echo (!empty($data['select_file_err'])) ? 'is-invalid' : '' ?>">
                          <div class="ml-auto">
                              <small class="text-muted">File must be in excel format.</small>
                              <span class="invalid-feedback d-block"><?php echo $data['select_file_err'] ?></span>
                          </div>    
                      </div>
                      <div class="row">
                          <div class="col-md-3">
                              <button type="submit" name="importbook" class="btn btn-success btn-block"><i class="fas fa-cloud-upload-alt"></i> Import books</button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
<?php require APPROOT . '/views/include/footer.php'?>