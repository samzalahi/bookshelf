<?php require APPROOT . '/views/include/header.php'?>
    <div class="container">
        <div class="col-md-12">
            <div class="card card-body bg-light mt-5">
                <div class="row col-md-12">
                    <h1 class="">Edit <?php echo $data['title'] ?></h1>
                    <!-- <div class="align-self-center ml-auto">
                        <button onclick="goBack()" class="btn btn-success"><i class="fas fa-backward"></i> Go back</button>
                    </div> -->
                </div>
                <form action="<?php echo URLROOT;?>/books/edit/<?php echo $data['id']; ?>" enctype="multipart/form-data" method="post" class="d-flex">
                    <div class="col-sm-5 mr-3 form-left">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Book Title:<sup>*</sup></label>
                            <input type="text" name="title" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['title_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book title"
                            value="<?php echo $data['title']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['title_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="author" class="col-form-label">Book Author:<sup>*</sup></label>
                            <input type="text" name="author" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['author_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book author"
                            value="<?php echo $data['author']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['author_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="edition" class="col-form-label">Book Edition:</label>
                            <input type="text" name="edition" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['edition_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book edition"
                            value="<?php echo $data['edition']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['edition_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="publication" class="col-form-label">Book Publication Info:<sup>*</sup></label>
                            <input type="text" name="publication" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['publication_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book publication"
                            value="<?php echo $data['publication']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['publication_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="no_of_books" class="col-form-label">Series:</label>
                            <input type="text" name="no_of_books" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['no_of_books_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter no of books"
                            value="<?php echo $data['no_of_books']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['no_of_books_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="tag" class="col-form-label">Tag:</label>
                            <input type="text" name="tag" id="tokenfield" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['tag_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book tags"
                            value="<?php //echo $data['tag']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['tag_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="copyright" class="col-form-label">Copyright:</label>
                            <input type="text" name="copyright" autocomplete="off" class="col-sm-12 form-control
                            <?php echo (!empty($data['copyright_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter copyright"
                            value="<?php echo $data['copyright']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['copyright_err'] ?></span>
                        </div>
                    </div>   
                    <div class="divider"></div>
                    <div class="col-sm-5 mr-3 form-right">
                        <div class="form-group">
                            <label for="book_no" class="col-form-label">Book No:<sup>*</sup></label>
                            <input type="text" name="book_no" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['book_no_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book number"
                            value="<?php echo $data['book_no']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['book_no_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="availability" class="col-form-label">Book Availability:</label>
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
                            <label for="container" class="col-form-label">Book Container:</label>
                            <input type="text" name="container" autocomplete="off" class="col-sm-12 form-control form-control
                            <?php echo (!empty($data['container_err'])) ? 'is-invalid' : '' ?>" placeholder="Enter book container"
                            value="<?php echo $data['container']?>">
                            <span class="invalid-feedback mr-auto"><?php echo $data['container_err'] ?></span>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-form-label">Description:</label>
                            <textarea name="description" id="" class="col-sm-12 form-control form-control" placeholder="Enter description" rows="3"><?php echo $data['description']?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="select_book" class="col-form-label">Select Book:</label>
                            <input type="file" name="select_book[]" class="col-sm-12 form-control-file
                            <?php echo (!empty($data['select_book_err'])) ? 'is-invalid' : '' ?>">
                            <div class="ml-auto">
                                <small class="text-muted">Files format must be pdf/doc and size below 1GB.</small>
                                <span class="invalid-feedback d-block"><?php echo $data['select_book_err'] ?></span>
                            </div>    
                        </div>
                        <div class="form-group">
                            <label for="select_book_cover" class="col-form-label">Select Book Cover:</label>
                            <input type="file" name="select_book[]" class="col-sm-12 form-control-file
                            <?php echo (!empty($data['select_book_cover_err'])) ? 'is-invalid' : '' ?>">
                            <div class="ml-auto">
                                <small class="text-muted">Files format must be jpg and size below 5MB.</small>
                                <span class="invalid-feedback d-block"><?php echo $data['select_book_cover_err'] ?></span>
                            </div>    
                        </div>
                    </div>
                    <div class="addbook">
                        <button type="submit" name="addbook" class="btn btn-success btn-block"><i class="fas fa-cloud-upload-alt"></i> Update Book</button>
                    </div>    
                </form>
             </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>