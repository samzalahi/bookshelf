<?php require APPROOT . '/views/include/header.php'?>
    <div class="container">
        <div class="col-md-12">
            <div class="card card-body bg-light mt-5">
                <div class="row col-md-12">
                    <h1 class="">Edit <?php echo $data['title'] ?> Magazine</h1>
                    <!-- <div class="align-self-center ml-auto">
                        <button onclick="goBack()" class="btn btn-success"><i class="fas fa-backward"></i> Go back</button>
                    </div> -->
                </div>
                <form action="<?php echo URLROOT;?>/magazines/edit/<?php echo $data['id']; ?>" enctype="multipart/form-data" method="post" class="d-flex">
                    <div class="col-sm-5 mr-3 form-left">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title:<sup>*</sup></label>
                            <input type="text" name="title" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['title_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter magazine title"
                            value="<?php echo $data['title']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['title_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="issue_no" class="col-form-label">Issue No:</label>
                            <input type="text" name="issue_no" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['issue_no_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book issue number"
                            value="<?php echo $data['issue_no']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['issue_no_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="year" class="col-form-label">Year/Vol:</label>
                            <input type="text" name="year" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['year_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book year"
                            value="<?php echo $data['year']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['year_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="date" class="col-form-label">Date:<sup>*</sup></label>
                            <input type="text" name="date" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['date_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter magazine date"
                            value="<?php echo $data['date']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['date_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="publication" class="col-form-label">Publication Info:<sup>*</sup></label>
                            <input type="text" name="publication" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['publication_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book publication"
                            value="<?php echo $data['publication']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['publication_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="tag" class="col-form-label">Tag:</label>
                            <input type="text" name="tag" id="tokenfield" class="col-sm-12 form-control
                            <?php echo (!empty($data['tag_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book tag"
                            value="<?php //echo $data['tag']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['tag_err'] ?></span>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="col-sm-5 ml-3 form-right">
                        <div class="form-group">
                            <label for="mag_no" class="col-form-label">Magazine No:<sup>*</sup></label>
                            <input type="text" readonly name="mag_no" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['mag_no_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter magazine number"
                            value="<?php echo $data['mag_no']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['mag_no_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="availability" class="col-form-label">Availability:</label>
                            <select name="availability" id="availability" class="col-md-12 form-control form-control">
                            <?php if ($data['availability'] == 1) : ?>
                                <option value="Available">Available</option>
                                <option value="Not Available">Not Available</option>
                            <?php else: ?>
                                <option value="Not Available">Not Available</option>
                                <option value="Available">Available</option>
                            <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="container" class="col-form-label">Container:</label>
                            <input type="text" name="container" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['container_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book container"
                            value="<?php echo $data['container']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['container_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-form-label">Description:</label>
                            <textarea name="description" id="" class="col-sm-12 form-control form-control" placeholder="Enter description" rows="3"><?php echo $data['description']?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="select_mag" class="col-form-label">Select Magazine:</label>
                            <input type="file" name="select_mag[]" class="col-sm-12 form-control-file
                            <?php echo (!empty($data['select_mag_err'])) ? 'is-invalid' : '' ?>">
                            <div class="ml-auto">
                                <small class="text-muted">Files format must be pdf/doc and size below 1GB.</small>
                                <span class="invalid-feedback d-block"><?php echo $data['select_mag_err'] ?></span>
                            </div>    
                        </div>
                        <div class="form-group">
                            <label for="select_mag_cover" class="col-form-label">Select Magazine Cover:</label>
                            <input type="file" name="select_mag[]" class="col-sm-12 form-control-file
                            <?php echo (!empty($data['select_mag_cover_err'])) ? 'is-invalid' : '' ?>">
                            <div class="ml-auto">
                                <small class="text-muted">Files format must be jpg and size below 6MB.</small>
                                <span class="invalid-feedback d-block"><?php echo $data['select_mag_cover_err'] ?></span>
                            </div>    
                        </div>
                    </div>
                    <div class="addbook">
                        <button type="submit" name="addbook" class="btn btn-success btn-block"><i class="fas fa-cloud-upload-alt"></i> Update Magazine</button>
                    </div>    
                </form>
             </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>