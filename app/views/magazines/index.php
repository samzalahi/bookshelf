<?php require APPROOT . '/views/include/header.php'?>
    <div class="content-body">
        <div class="sidebar">
            <?php require APPROOT . '/views/magazines/sidebar.php'?>
        </div>
        <div class="main">
            <h1 class="text-center mt-3">Search Magazines</h1>
            <?php echo flash('flash_message'); ?>
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <form action="<?php echo URLROOT; ?>/magazines/index" method="post" class="col-md-10 d-flex">
                        <div class="col-md-2 form-group row">
                            <select name="type" id="search_type" class="form-control form-control-sm">
                                <option>Magazines</option>
                                <option>Tag</option>
                            </select>
                        </div>
                        <!-- <div class="col-md-10 form-group mb-5 align-items-end" id="search-box">
                            <input id="search" name="search" type="text" placeholder="Search books" autocomplete="off">
                            <input id="search_submit" value="Rechercher" type="submit">
                        </div> -->
                        <div class="col-md-10 form-group mb-5 align-items-end" id="tag-search-box">
                            <input id="tokenfield" name="search" type="submit" placeholder="Search magazines" autocomplete="off">
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" id="result_table">
                <table id="magazines-table" class="table table-striped ml-3">
                <?php if (isset($data['magazine'])) : ?>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 6%;">Mag No.</th>
                            <th scope="col" style="width: 15%;">Title</th>
                            <th scope="col" style="width: 6%;">Issue No.</th>
                            <th scope="col" style="width: 1%;">Year/Vol.</th>
                            <th scope="col" style="width: 7%;">Date</th>
                            <th scope="col" style="width: 9%;">Publication Info.</th>
                            <th scope="col" style="width: 10%;">Container</th>
                            <th scope="col" style="width: 3%;">Availability</th>
                            <th scope="col" style="width: 15%;">Actions</th>
                        </tr>   
                    </thead>
                    <tbody>
                    <?php foreach ($data['magazine'] as $mag) : ?>
                        <tr>
                            <td><?php echo $mag->mag_no; ?></td>
                            <td><?php echo $mag->title; ?></td>
                            <td><?php echo $mag->issue_no; ?></td>
                            <td><?php echo ($mag->year) ? $mag->year : 'N/A'; ?></td>
                            <td><?php echo $mag->date; ?></td>
                            <td><?php echo $mag->publication; ?></td>
                            <td><?php echo ($mag->container) ? $mag->container : 'N/A'; ?></td>
                            <td><?php echo ($mag->status == 1) ? 'Available' : 'Not available'; ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/magazines/addissue/<?php echo $mag->id; ?>" target="_blank"     class="d-inline-block">
                                    <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Add issues">
                                        <i class="fas fa-plus-square"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/magazines/edit/<?php echo $mag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-primary btn-sm" data-toggle="tooltip" title="Edit magazine">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/magazines/info/<?php echo $mag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="Magazine info">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </a>
                                <?php if (!empty($mag->file_type)) : ?>    
                                <a href="<?php echo URLROOT;?>/magazines/read/<?php echo $mag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Read magazine">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/magazines/download/<?php echo $mag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Download magazine">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </a>
                                <?php endif; ?>
                                <form action="<?php echo URLROOT; ?>/magazines/delete/<?php echo $mag->id; ?>" method="POST" class="d-inline-block">
                                    <button class="btn btn-outline-danger btn-sm" data-toggle="confirmation" data-placement="top">
                                        <span data-toggle="tooltip" title="Delete magazine">
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
                <table class="table table-striped ml-3" id="tag_table">
                    <thead>
                        <tr>
                            <th scope="col">Mag No.</th>
                            <th scope="col">Title</th>
                            <th scope="col">Issue No.</th>
                            <th scope="col">Year/Vol.</th>
                            <th scope="col">Date</th>
                            <th scope="col">Publication Info.</th>
                            <th scope="col">Container</th>
                            <th scope="col">Availability</th>
                            <th scope="col">Actions</th>
                        </tr>   
                    </thead>
                    <tbody>
                    <?php foreach ($data['tag'] as $tag) : ?>
                        <tr>
                            <td><?php echo $tag->mag_no; ?></td>
                            <td><?php echo $tag->title; ?></td>
                            <td><?php echo $tag->issue_no; ?></td>
                            <td><?php echo ($tag->year) ? $tag->year : 'N/A'; ?></td>
                            <td><?php echo $tag->date; ?></td>
                            <td><?php echo $tag->publication; ?></td>
                            <td><?php echo ($tag->container) ? $tag->container : 'N/A'; ?></td>
                            <td><?php echo ($tag->status == 1) ? 'Available' : 'Not available'; ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/magazines/addissue/<?php echo $tag->id; ?>" target="_blank"     class="d-inline-block">
                                    <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Add issues">
                                        <i class="fas fa-plus-square"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/magazines/edit/<?php echo $tag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-primary btn-sm mr-2" data-toggle="tooltip" title="Edit book">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/magazines/info/<?php echo $tag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-info btn-sm mr-2" data-toggle="tooltip" title="Magazines info">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </a>
                                <?php if (!empty($tag->file_type)) : ?>    
                                <a href="<?php echo URLROOT;?>/magazines/read/<?php echo $tag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-success btn-sm" data-toggle="tooltip" title="Read magazine">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                                <a href="<?php echo URLROOT; ?>/magazines/download/<?php echo $tag->id; ?>" target="_blank" class="d-inline-block">
                                    <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Download magazine">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </a>
                                <?php endif; ?>
                                <form action="<?php echo URLROOT; ?>/magazines/delete/<?php echo $tag->id; ?>" target="_blank" method="POST" class="d-inline-block">
                                    <button class="btn btn-outline-danger btn-sm mr-2" data-toggle="confirmation" data-placement="top">
                                        <span data-toggle="tooltip" title="Delete magazines">
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
</script>