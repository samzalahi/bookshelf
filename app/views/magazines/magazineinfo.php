<?php require APPROOT . '/views/include/header.php'?>
    <div class="container">
        <h1 class=""><?php echo $data['title'] . ' ' . 'Info'; ?></h1>
        <?php echo flash('flash_message'); ?>
        <div class="row">
            <div class="col-md-4">
                <div class="book-cover">
                    <?php
                    $path = MAGCOVERROOT . $data['mag_no'] . '_' . $data['year'] . '_' . $data['issue_no'] . '.' . $data['cover_img_type'];
                    $defImg = URLROOT . '/img/mag_covers/def_cover.png';
                    $selImg = URLROOT . "/img/mag_covers/{$data['mag_no']}_{$data['year']}_{$data['issue_no']}.{$data['cover_img_type']}";

                    if (!file_exists($path)) {
                        // die($data['book_no'] . ' file not exists');
                        $coverImg = $defImg;
                    } else {
                        // die('file exists');
                        $coverImg = $selImg;
                    }
                    ?>
                    <img src=<?php echo $coverImg; ?> alt="Book Cover" class="img-thumbnail img-fit">
                </div>
            </div>
            <div class="col-md-6 magazine-info">
                <div class="form-group row">
                    <label for="mag_no" class="col-sm-5 col-form-label text-right">Magazine No:</label>
                    <label for="mag_no" class="col-sm-7 col-form-label"><?php echo (!$data['mag_no']) ? 'N/A' : $data['mag_no']; ?></label>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-5 col-form-label text-right">Title:</label>
                    <label for="title" class="col-sm-7 col-form-label"><?php echo $data['title']?></label>
                </div>
                <div class="form-group row">
                    <label for="issue_no" class="col-sm-5 col-form-label text-right">Issue No:</label>
                    <label for="issue_no" class="col-sm-7 col-form-label"><?php echo $data['issue_no']?></label>
                </div>
                <div class="form-group row">
                    <label for="year" class="col-sm-5 col-form-label text-right">Year/Vol:</label>
                    <label for="year" class="col-sm-7 col-form-label"><?php echo ($data['year']) ? $data['year'] : 'N/A'?></label>
                </div>
                <div class="form-group row">
                    <label for="date" class="col-sm-5 col-form-label text-right">Date:</label>
                    <label for="date" class="col-sm-7 col-form-label"><?php echo $data['date']?></label>
                </div>
                <div class="form-group row">
                    <label for="publication" class="col-sm-5 col-form-label text-right">Publication:</label>
                    <label for="publication" class="col-sm-7 col-form-label"><?php echo $data['publication']?></label>
                </div>
                <div class="form-group row">
                    <label for="publication" class="col-sm-5 col-form-label text-right">Container:</label>
                    <label for="publication" class="col-sm-7 col-form-label"><?php echo (!empty($data['container'])) ? $data['container']: 'N/A'; ?></label>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-5 col-form-label text-right">Description:</label>
                    <label for="description" class="col-sm-7 col-form-label"><?php echo (!empty($data['description'])) ? $data['description'] : 'N/A'; ?></label>
                </div>
                <div class="form-group row">
                    <label for="availability" class="col-sm-5 col-form-label text-right">Availability:</label>
                    <label for="availability" class="col-sm-7 col-form-label"><?php echo ($data['availability'] == 1 ) ? 'Available' : 'N/A' ?></label>
                </div>
                <div class="form-group row">
                    <label for="created_by" class="col-sm-5 col-form-label text-right">Uplaoded By:</label>
                    <label for="created_by" class="col-sm-7 col-form-label"><?php echo $data['created_by']?></label>
                </div>
                <div class="form-group row">
                    <label for="created_date" class="col-sm-5 col-form-label text-right">Uplaoded Date:</label>
                    <label for="created_date" class="col-sm-7 col-form-label"><?php echo $data['created_date']?></label>
                </div>
                <div class="form-group row">
                    <label for="created_date" class="col-sm-5 col-form-label text-right">Updated By:</label>
                    <label for="created_date" class="col-sm-7 col-form-label"><?php echo (!$data['updated_by']) ? 'Not updated' : $data['updated_by']; ?></label>
                </div>
                <div class="form-group row">
                    <label for="created_date" class="col-sm-5 col-form-label text-right">Updated Date:</label>
                    <label for="created_date" class="col-sm-7 col-form-label"><?php echo (!$data['updated_date']) ? 'Not updated' : $data['updated_date']; ?></label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-2">
                    <a href="<?php echo URLROOT; ?>/magazines/read/<?php echo $data['id'] ?>" target="_blank">
                        <button class="btn btn-success btn-block"><i class="fas fa-eye"></i> Read Magazines</button>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="<?php echo URLROOT; ?>/magazines/download/<?php echo $data['id'] ?>">
                        <button class="btn btn-success btn-block"><i class="fas fa-download"></i> Download</button>
                    </a>   
                </div>
                <div class="mb-2">
                    <a href="<?php echo URLROOT; ?>/magazines/edit/<?php echo $data['id']; ?>">
                        <button class="btn btn-success btn-block"><i class="fas fa-edit"></i> Edit Magazines</button>
                    </a>
                </div>
                <div class="mb-2">
                    <form action="<?php echo URLROOT; ?>/magazines/delete/<?php echo $data['id']; ?>" method="POST">
                        <button class="btn btn-success btn-block" data-toggle="confirmation" data-placement="right">
                            <i class="fas fa-trash"></i> Delete Magazines
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>