<?php require APPROOT . '/views/include/header.php'?>
    <div class="content-body">
        <div class="sidebar">
            <?php require APPROOT . '/views/books/sidebar.php'?>
        </div>
        <div class="main">
            <h1 class="text-center mt-3">Search Books</h1>
            <?php echo flash('flash_message'); ?>
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <form action="<?php echo URLROOT; ?>/books/index" method="post" class="col-md-10 d-flex">
                        <div class="col-md-2 input-group">
                            <select name="type" id="search_type" class="form-control form-control-sm">
                                <option>Books</option>
                                <!-- <option>Tag</option> disabled -->  
                            </select>
                        </div>
                        <!-- <div class="col-md-10 form-group mb-5 align-items-end" id="search-box">
                            <input id="search" name="search" type="text" placeholder="Search books" autocomplete="off">
                            <input id="search_submit" value="Rechercher" type="submit">
                        </div> -->
                        <!-- <div class="col-md-10 form-group mb-5 align-items-end" id="tag-search-box">
                            <input id="tokenfield" name="search" type="submit" placeholder="Search books" autocomplete="off">
                        </div> -->
                        <div class="input-group mb-4">
                            <input type="text" class="form-control search-box" name="search" placeholder="Search books" aria-label="Search books" aria-describedby="basic-addon2">  <!-- id="tokenfield" disabled -->
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" id="result_table">
                <table id="books-table" class="table table-striped ml-3">
                <?php if (isset($data['books'])) : ?>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 6%;">Book No.</th>
                            <th scope="col" style="width: 13%;">Title</th>
                            <th scope="col" style="width: 10%;">Author</th>
                            <th scope="col" style="width: 1%;">Edtion</th>
                            <th scope="col" style="width: 8%;">Publication Info.</th>
                            <th scope="col" style="width: 8%;">Container</th>
                            <th scope="col" style="width: 3%;">Availability</th>
                            <th scope="col" style="width: 11%;">Actions</th>
                        </tr>   
                    </thead>
                    <tbody>
                    <?php foreach ($data['books'] as $book) : ?>
                        <tr>
                            <td><?php echo $book->book_no; ?></td>
                            <td><?php echo $book->title; ?></td>
                            <td><?php echo $book->author; ?></td>
                            <td><?php echo $book->edition ?: 'N/A'; // this is equal to?></td>
                            <td><?php echo $book->publication; ?></td>
                            <td><?php echo ($book->container) ? $book->container : 'N/A'; // this ?></td>
                            <td><?php echo ($book->status == 1) ? 'Available' : 'Not available'; ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/books/edit/<?php echo $book->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-primary btn-sm" data-toggle="tooltip" title="Edit book">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/books/info/<?php echo $book->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="Book info">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </a>
                                <?php if (!empty($book->file_type)) : ?>    
                                <a href="<?php echo URLROOT;?>/books/read/<?php echo $book->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Read book">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/books/download/<?php echo $book->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Download book">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </a>
                                <?php endif; ?>
                                <form action="<?php echo URLROOT; ?>/books/delete/<?php echo $book->id; ?>" method="POST" class="d-inline-block">
                                    <button class="btn btn-outline-danger btn-sm" data-toggle="confirmation" data-placement="top">
                                        <span data-toggle="tooltip" title="Delete book">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                <?php else : ?>
                </table>
                <!-- <div class="col-md-12 d-inline-flex mb-2">
                    <strong class="col">Total books: <?php //echo $tb = $data['tag'][0]->total_books; ?></strong>
                    <strong class="col">Available books: <?php //echo $tab = $data['tag'][0]->total_available_books; ?></strong>
                    <strong class="col">Not available books: <?php //echo $tnab = $tb-$tab; ?></strong>
                </div> -->
                <table class="table table-striped ml-3" id="tag_table">
                    <thead>
                        <tr>
                            <th scope="col">Book No.</th>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">Edtion</th>
                            <th scope="col">Publication Info.</th>
                            <th scope="col">Container</th>
                            <th scope="col">Availability</th>
                            <th scope="col">Actions</th>
                        </tr>   
                    </thead>
                    <tbody>
                    <?php foreach ($data['tag'] as $tag) : ?>
                        <tr>
                            <td><?php echo $tag->book_no; ?></td>
                            <td><?php echo $tag->title; ?></td>
                            <td><?php echo $tag->author; ?></td>
                            <td><?php echo ($tag->edition) ? $tag->edition : 'N/A'; ?></td>
                            <td><?php echo $tag->publication; ?></td>
                            <td><?php echo ($tag->container) ? $tag->container : 'N/A'; ?></td>
                            <td><?php echo ($tag->status == 1) ? 'Available' : 'Not available'; ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/books/edit/<?php echo $tag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-primary btn-sm mr-2" data-toggle="tooltip" title="Edit book">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/books/info/<?php echo $tag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-info btn-sm mr-2" data-toggle="tooltip" title="Book info">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </a>
                                <form action="<?php echo URLROOT; ?>/books/delete/<?php echo $tag->id; ?>" target="_blank" method="POST" class="d-inline-block">
                                    <button class="btn btn-outline-danger btn-sm mr-2" data-toggle="confirmation" data-placement="top">
                                        <span data-toggle="tooltip" title="Delete book">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                <?php endif; ?>
                </table>
                <div class="t-footer d-flex">
                    <!-- <div class="align-self-center ml-auto">
                        Uploaded by Mohammed on 12-03-2018
                    </div> -->
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/include/footer.php'?>
<script>
// $("#tag-search-box").hide();
// $("#search_type").change(function() {
//     $("#search-box, #tag-search-box").toggle();
//     $(".align-items-end:hidden").find('input').prop('disabled', true);
//     $(".align-items-end:visible").find('input').prop('disabled', false);
// });
// $("#tag-search-box").hide().find('input:not([type="submit"])').prop('disabled', true);
//   $("#search_type").change(function() {
//     $("#tag-search-box,#search-box").toggle();
//     $(".align-items-end:hidden").find('input:not([type="submit"])').prop('disabled', true);
//     $(".align-items-end:visible").find('input:not([type="submit"])').prop('disabled', false);
//  });
$("#search_type").change(function() {
    var search_value = $("#search_type option:selected").text();
    
    if (search_value == "Tag") {
        $(".search-box").attr('id', 'tokenfield')
    }
    // $(".search-box").toggle

    console.log(search_value);
});
</script>