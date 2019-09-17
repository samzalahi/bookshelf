<?php require APPROOT . '/views/include/header.php'?>
    <div class="content-body">
        <div class="sidebar">
            <?php require APPROOT . '/views/books/sidebar.php'?>
        </div>
        <div class="main">
            <h1 class="text-center mt-3">Search PDFs</h1>
            <?php echo flash('flash_message'); ?>
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <form action="<?php echo URLROOT; ?>/books/search" method="post" class="col-md-10 d-flex">
                        <div class="input-group mb-4">
                            <input type="text" class="form-control search-box" name="search" placeholder="Search books" aria-label="Search books" aria-describedby="basic-addon2">  
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" id="result_table">
                <table id="books-table" class="table table-striped ml-3">
                <?php //if (isset($data['books'])) : ?>
                    <thead>
                        <tr>
                            <th scope="col" style="">Title</th>
                            <th scope="col" style="width: 10%;">Actions</th>
                        </tr>   
                    </thead>
                    <tbody>
                    <?php foreach ($data as $book) : ?>
                        <tr>
                            <td><?php echo $book; ?></td>
                            <td>  
                                <a href="<?php echo URLROOT;?>/books/readPDF/<?php echo $book; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Read book">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/books/downloadPDF/<?php echo $book; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Download book">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                  <?php //endif; ?>
                </table>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>
<script>
</script>