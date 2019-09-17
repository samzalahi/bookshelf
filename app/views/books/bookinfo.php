<?php require APPROOT . '/views/include/header.php'?>
    <div class="container">
        <h1 class=""><?php echo $data['title'] . ' ' . 'Info'; ?></h1>
        <?php echo flash('flash_message'); ?>
        <div class="row">
            <div class="col-md-4">
                <div class="book-cover">
                    <?php
                    $path = BOOKCOVERROOT . $data['book_no'] . '.' . $data['cover_img_type'];
                    $defImg = URLROOT . '/img/book_covers/def_cover.png';
                    $selImg = URLROOT . "/img/book_covers/{$data['book_no']}.{$data['cover_img_type']}";

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
            <div class="col-md-6 book-info">
                <div class="form-group row">
                    <label for="title" class="col-sm-5 col-form-label text-right">Title:</label>
                    <label for="title" class="col-sm-7 col-form-label"><?php echo $data['title']?></label>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-5 col-form-label text-right">Book No:</label>
                    <label for="title" class="col-sm-7 col-form-label"><?php echo (!$data['book_no']) ? 'N/A' : $data['book_no']; ?></label>
                </div>
                <div class="form-group row">
                    <label for="author" class="col-sm-5 col-form-label text-right">Author:</label>
                    <label for="author" class="col-sm-7 col-form-label"><?php echo $data['author']?></label>
                </div>
                <div class="form-group row">
                    <label for="edition" class="col-sm-5 col-form-label text-right">Edition:</label>
                    <label for="edition" class="col-sm-7 col-form-label"><?php echo ($data['edition']) ? $data['edition'] : 'N/A'?></label>
                </div>
                <div class="form-group row">
                    <label for="publication" class="col-sm-5 col-form-label text-right">Publication:</label>
                    <label for="publication" class="col-sm-7 col-form-label"><?php echo $data['publication']?></label>
                </div>
                <div class="form-group row">
                    <label for="no_of_books" class="col-sm-5 col-form-label text-right">Series:</label>
                    <label for="no_of_books" class="col-sm-7 col-form-label"><?php echo ($data['no_of_books']) ? $data['no_of_books'] : 'N/A'?></label>
                </div>
                <div class="form-group row">
                    <label for="availability" class="col-sm-5 col-form-label text-right">Availability:</label>
                    <label for="availability" class="col-sm-7 col-form-label"><?php echo ($data['availability'] == 1 ) ? 'Available' : 'N/A' ?></label>
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
                    <label for="tag" class="col-sm-5 col-form-label text-right">Copyright:</label>
                    <label for="tag" class="col-sm-7 col-form-label"><?php echo (!empty($data['copyright'])) ? $data['copyright'] : 'N/A'; ?></label>
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
                <!-- <div class="mb-2">
                    <a href="<?php //echo URLROOT; ?>/books/addbook/<?php //echo $data['seriesID'] ?>">
                        <button class="btn btn-success btn-block"><i class="fas fa-plus-square"></i> Add More Book</button>
                    </a>
                </div> -->
                <div class="mb-2">
                    <a href="<?php echo URLROOT; ?>/books/read/<?php echo $data['id'] ?>" target="_blank">
                        <button class="btn btn-success btn-block"><i class="fas fa-eye"></i> Read Book</button>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="<?php echo URLROOT; ?>/books/download/<?php echo $data['id'] ?>">
                        <button class="btn btn-success btn-block"><i class="fas fa-download"></i> Download</button>
                    </a>   
                </div>
                <div class="mb-2">
                    <a href="<?php echo URLROOT; ?>/books/edit/<?php echo $data['id']; ?>">
                        <button class="btn btn-success btn-block"><i class="fas fa-edit"></i> Edit Book</button>
                    </a>
                </div>
                <!-- <div class="mb-2">
                    <a href="<?php //echo URLROOT; ?>/books/seriesinfo/<?php //echo $data['seriesID']; ?>">
                        <button class="btn btn-success btn-block"><i class="fas fa-arrow-circle-left"></i> Go to Series</button>
                    </a>
                </div> -->
                <div class="mb-2">
                    <form action="<?php echo URLROOT; ?>/books/delete/<?php echo $data['id']; ?>" method="POST">
                        <button class="btn btn-success btn-block" data-toggle="confirmation" data-placement="right">
                            <i class="fas fa-trash"></i> Delete Book
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>