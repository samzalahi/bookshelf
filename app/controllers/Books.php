<?php

class Books extends Controller
{
    public function __construct()
    {
        // Init database
        $this->bookModel = $this->model('Book');
    }

    public function index()
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get search kek
            $key = $_POST['search'];
            $search_type = $_POST['type'];
            // echo $key . " " . $search_type;
            if ($search_type == 'Books') {
                // Get books
                if ($books = $this->bookModel->getBooksByKey($key)) {
                    $data = [
                        'books' => $books
                    ];
                } else {
                    // If results not found
                    flash('Error!', 'No books found', 'alert alert-danger');
                    $books = $this->bookModel->getAllBooks();
                    $data = [
                        'books' => $books
                    ];
                }
            } else {
                // Get books
                if ($books = $this->bookModel->getBooksByTag($key)) {
                    $data = [
                        'tag' => $books
                    ];
                } else {
                    // If results not found
                    flash('Error!', 'No books found for this tag', 'alert alert-danger');
                    $books = $this->bookModel->getAllBooks();
                    $data = [
                        'books' => $books
                    ];
                }
            }

            //Load view
            $this->view('books/index', $data);
        } else {
            // If not isset search
            $books = $this->bookModel->getAllBooks();

            $data = [
                'books' => $books
            ];
            $this->view('books/index', $data);
        }
    }

    public function add()
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process addbook form

            // Sanitize POST data\
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get upload book info
            $bookExt = pathinfo($_FILES['select_book']['name'][0], PATHINFO_EXTENSION);
            $coverExt = pathinfo($_FILES['select_book']['name'][1], PATHINFO_EXTENSION);
            $bookname = ltrim($_POST['book_no'], '0') . '.' . $bookExt; /* $_FILES['select_book']['name'];  */
            $covername = trim($_POST['book_no'], '0') . '.' . $coverExt;
            $tmpbookname = $_FILES['select_book']['tmp_name'][0];
            $tmpCovername = $_FILES['select_book']['tmp_name'][1];
            $targetDir = BOOKROOT;
            $picDir = BOOKCOVERROOT;
            $bookTarget = $targetDir . $bookname;
            $picTarget = $picDir . $covername;
            $bookMimeType = [
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword'
            ];
            $picMimeType = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ];

            // die(var_dump($_FILES['select_book']));
            $tags = strtolower($_POST['tag']);
            $tags = explode(',', trim($tags));
            $tag = array_map('trim', $tags);

            // Init data
            $data = [
                'book_no' => trim($_POST['book_no']),
                'title' => trim($_POST['title']),
                'author' => trim($_POST['author']),
                'edition' => trim($_POST['edition']),
                'publication' => trim($_POST['publication']),
                'no_of_books' => trim($_POST['no_of_books']),
                'container' => trim($_POST['container']),
                'description' => trim($_POST['description']),
                'file_type' => $bookExt,
                'cover_img_type' => $coverExt,
                'availability' => trim($_POST['availability']),
                'tag' => $tags,
                'copyright' => trim($_POST['copyright']),
                'user_id' => $_SESSION['user_id'],
                'book_no_err' => '',
                'title_err' => '',
                'author_err' => '',
                'edition_err' => '',
                'publication_err' => '',
                'no_of_books_err' => '',
                'container_err' => '',
                'tag_err' => '',
                'copyright_err' => '',
                'select_book_err' => '',
                'select_book_cover_err' => ''
            ];

            // Validate book no
            if (empty($data['book_no'])) {
                $data['book_no_err'] = 'Please enter book number';
            } elseif (!is_numeric($data['book_no'])) {
                $data['book_no_err'] = 'Please enter numeric value';
            } elseif ($this->bookModel->findBookByBookNo($data['book_no'])) {
                $data['book_no_err'] = 'This book number is already exists on database';
            }

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter book title';
            }

            // Validate author
            if (empty($data['author'])) {
                $data['author_err'] = 'Please enter book author name';
            }

            // Validate publication
            if (empty($data['publication'])) {
                $data['publication_err'] = 'Please enter book publication';
            }

            //Validate no of books
            if (!empty($data['no_of_books']) && !is_numeric($data['no_of_books'])) {
                $data['no_of_books_err'] = 'Please enter numeric value';
            }

            // Validate book upload by availability
            if (trim($_POST['availability'] === 'Available')) {
                $data['availability'] = true;

                // Validate container
                if (empty($data['container'])) {
                    $data['container_err'] = 'Please enter book container if available';
                }
            } else {
                $data['availability'] = false;
            }
            
            // Validate book upload
            if (!empty($tmpbookname)) {
                if ($this->validateMimeType($tmpbookname, $bookMimeType)) {
                    $data['select_book_err'] = 'Please select valid book type';
                } elseif ($_FILES['select_book']['size'][0] > 1000000000) {
                    $data['select_book_err'] = 'data shoud be below 1GB';
                } elseif (file_exists($bookTarget)) {
                    $data['select_book_err'] = 'This book is already exists on local folder';
                }
            }
            
            // Validate pic upload
            if (!empty($tmpCovername)) {
                if ($this->validateMimeType($tmpCovername, $picMimeType)) {
                    $data['select_book_cover_err'] = 'Please select valid image type';
                } elseif ($_FILES['select_book']['size'][1] > 6000000) {
                    $data['select_book_cover_err'] = 'Image shoud be below 6MB';
                } elseif (file_exists($picTarget)) {
                    $data['select_book_cover_err'] = 'This cover image is already exists on local folder';
                }
            }

            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['book_no_err']) && empty($data['author_err']) && empty($data['publication_err']) && empty($data['no_of_books_err']) && empty($data['container_err']) && empty($data['select_book_err']) && empty($data['select_book_cover_err'])) {
                // Process add books
                // Check folder already exists or not if not create folder

                if ($this->bookModel->add($data)) {
                    // Upload data into database
                    // Get last Id
                    $lastId = $this->bookModel->getLastId();
                    
                    // Insert tags
                    if (!empty($_POST['tag'])) {
                        $this->bookModel->addTag($tag, $lastId);
                    }

                     // Move book into local book server
                    if (!empty($tmpbookname)) {
                        // if (!is_dir($targetDir)) {
                        //     mkdir($targetDir, 0777, true);
                        // }
                        move_uploaded_file($tmpbookname, $bookTarget);
                    }

                    // Move book cover into local book server
                    if (!empty($tmpCovername)) {
                        move_uploaded_file($tmpCovername, $picTarget);
                    }

                    flash('Success!', "{$data['title']} book uploaded successfuly", 'alert alert-success');
                    redirect('books/add');
                } else {
                    flash('Error!', 'Could not upload book', 'alert alert-danger');
                    redirect('books/index');
                }
            } else {
                // Load addbook view
                $this->view('books/addbook', $data);
            }
        } else {
            // Load addbook view
            // Init data
            $data = [
                'book_no' => '',
                'title' => '',
                'author' => '',
                'edition' => '',
                'publication' => '',
                'no_of_books' => '',
                'container' => '',
                'description' => '',
                'file_type' => '',
                'cover_img_type' => '',
                'availability' => '',
                'tag' => '',
                'copyright' => '',
                'user_id' => '',
                'book_no_err' => '',
                'title_err' => '',
                'author_err' => '',
                'edition_err' => '',
                'publication_err' => '',
                'no_of_books_err' => '',
                'container_err' => '',
                'tag_err' => '',
                'copyright_err' => '',
                'select_book_err' => '',
                'select_book_cover_err' => ''
                
            ];
            // Load addseries view
            $this->view('books/addbook', $data);
        }
    }

    public function edit($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process addbook form
            // exec("net use X: \\\\10.102.101.5\\Qatar_History\\TQ_Books /persistent:yes");
            // die();
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get upload book info
            $bookExt = pathinfo($_FILES['select_book']['name'][0], PATHINFO_EXTENSION);
            $coverExt = pathinfo($_FILES['select_book']['name'][1], PATHINFO_EXTENSION);
            $bookname = ltrim($_POST['book_no'], '0') . '.' . $bookExt; /* $_FILES['select_book']['name'];  */
            $covername = trim($_POST['book_no'], '0') . '.' . $coverExt;
            $tmpbookname = $_FILES['select_book']['tmp_name'][0];
            $tmpCovername = $_FILES['select_book']['tmp_name'][1];
            $targetDir = BOOKROOT;
            $picDir = BOOKCOVERROOT;
            $bookTarget = $targetDir . $bookname;
            $picTarget = $picDir . $covername;
            $bookMimeType = [
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword'
            ];
            $picMimeType = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ];

            // Get book data from database
            // $Bdata = $this->bookModel->getBookById($id);

            // die(var_dump($_FILES['select_book']));
            $tags = strtolower($_POST['tag']);
            $tags = explode(',', trim($tags));
            $tag = array_map('trim', $tags);
            
            // Directory checking - test
            // $dh = opendir($targetDir);
            // echo "<pre>\n";
            // die(var_dump($dh, error_get_last()));
            // echo  "\n</pre>";
            // Init data
            $data = [
                'id' => $id,
                'book_no' => trim($_POST['book_no']),
                'title' => trim($_POST['title']),
                'author' => trim($_POST['author']),
                'edition' => trim($_POST['edition']),
                'publication' => trim($_POST['publication']),
                'no_of_books' => trim($_POST['no_of_books']),
                'container' => trim($_POST['container']),
                'description' => trim($_POST['description']),
                'file_type' => $bookExt,
                'cover_img_type' => $coverExt,
                'availability' => trim($_POST['availability']),
                'tag' => $tag,
                'copyright' => trim($_POST['copyright']),
                'user_id' => $_SESSION['user_id'],
                'book_no_err' => '',
                'title_err' => '',
                'author_err' => '',
                'edition_err' => '',
                'publication_err' => '',
                'no_of_books_err' => '',
                'container_err' => '',
                'tag_err' => '',
                'copyright_err' => '',
                'select_book_err' => '',
                'select_book_cover_err' => ''
            ];

            // Validate book no
            if (empty($data['book_no'])) {
                $data['book_no_err'] = 'Book number cannot be empty';
            } elseif (!is_numeric($data['book_no'])) {
                $data['book_no_err'] = 'Please enter numeric value';
            }

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter book title';
            }

            // Validate author
            if (empty($data['author'])) {
                $data['author_err'] = 'Please enter book author name';
            }

            // Validate publication
            if (empty($data['publication'])) {
                $data['publication_err'] = 'Please enter book publication';
            }

            // // Validate no of books
            if (!empty($data['no_of_books']) && !is_numeric($data['no_of_books'])) {
                $data['no_of_books_err'] = 'Please enter numeric value';
            }

            // Validate book upload by availability
            if (trim($_POST['availability'] === 'Available')) {
                $data['availability'] = true;

                // Validate container
                if (empty($data['container'])) {
                    $data['container_err'] = 'Please enter book container';
                }
            } else {
                $data['availability'] = false;
                // $path[] = BOOKROOT . $Bdata->book_no . '.' . $Bdata->file_type;
                // $path[] = BOOKCOVERROOT . $Bdata->book_no . '.jpg';
                
                // foreach ($path as $book) {
                //     if (file_exists($book)) {
                //         unlink($book);
                //     }
                // }
            }
            
            // Validate book upload
            if (!empty($tmpbookname)) {
                if ($this->validateMimeType($tmpbookname, $bookMimeType)) {
                    $data['select_book_err'] = 'Please select valid book type';
                } elseif ($_FILES['select_book']['size'][0] > 1000000000) {
                    $data['select_book_err'] = 'data shoud be below 1GB';
                } elseif (file_exists($bookTarget)) {
                    $data['select_book_err'] = 'This book is already exists on local folder';
                }
            }
            // Validate pic upload
            if (!empty($tmpCovername)) {
                if ($this->validateMimeType($tmpCovername, $picMimeType)) {
                    $data['select_book_cover_err'] = 'Please select valid image type';
                } elseif ($_FILES['select_book']['size'][1] > 6000000) {
                    $data['select_book_cover_err'] = 'Image shoud be below 6MB';
                } elseif (file_exists($picTarget)) {
                    $data['select_book_cover_err'] = 'This cover image is already exists on local folder';
                }
            }

            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['book_no_err']) && empty($data['author_err']) && empty($data['publication_err']) && empty($data['no_of_books_err']) && empty($data['container_err']) && empty($data['select_book_err']) && empty($data['select_book_cover_err'])) {
                // Process add books
                // Move book into local book server
                // Check folder already exists or not if not create folder
                // Update data into database
                if ($this->bookModel->update($data)) {
                    // Insert tags
                    if (!empty($_POST['tag'])) {
                        $this->bookModel->updateTag($tag, $id);
                    }

                    // Update book into cocal book server
                    if (!empty($tmpbookname)) {
                        // $path = BOOKROOT . $Bdata->book_no . '.' . $Bdata->file_type;
                        // if (file_exists($path)) {
                        //     unlink($path);
                        // }
                        // if (!is_dir($targetDir)) {
                        //     mkdir($targetDir, 0777, true);
                        // }
                        if (!move_uploaded_file($tmpbookname, $bookTarget)) {
                            die('Not working');
                        }
                    }
                    
                    // Update book cover into local book server
                    if (!empty($tmpCovername)) {
                        // $path = BOOKCOVERROOT . $Bdata->book_no . '.jpg';
                        if (file_exists($picDir)) {
                            move_uploaded_file($tmpCovername, $picTarget);
                        } else {
                            flash('Error: ', 'Folder not exixts please contact admin', 'alert alert-danger');
                            redirect('books/edit/' . $id);
                        }
                    }
                    flash('Success!', "{$data['title']} book updated successfuly", 'alert alert-success');
                    redirect('books/info/' . $id);
                } else {
                    flash('Error:', "Could not update {$data['title']} book", 'alert alert-danger');
                    redirect('books/index');
                }
            } else {
                // Load addbook view
                $this->view('books/editbook', $data);
            }
        } else {
            // Fetch data from database
            $data = $this->bookModel->getBookById($id);

            // Init data
            $data = [
                'id' => $id,
                'title' => $data->title,
                'book_no' => $data->book_no,
                'author' => $data->author,
                'edition' => $data->edition,
                'publication' => $data->publication,
                'no_of_books' => $data->no_of_books,
                'tag' => $data->tag,
                'copyright' => $data->copyright,
                'container' => $data->container,
                'description' => $data->description,
                'availability' => $data->status,
                'select_book_err' =>'',
                'select_book_cover_err' => ''
            ];

            // Load addbook view
            $this->view('books/editbook', $data);
        }
    }

    public function import()
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Init book
            $bookname = $_FILES['select_file']['name'];
            $bookExt = pathinfo($_FILES['select_file']['name'], PATHINFO_EXTENSION);
            $bookname = $_FILES['select_file']['name'].$bookExt;
            $tmpbookname = $_FILES['select_file']['tmp_name'];
            $bookMimeType = [
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'xls' => 'text/xls',
                'ms-excel' => 'application/vnd.ms-excel'
            ];

                
            // Init data
            $data = [
                'select_file_err' => ''
            ];

            // Validate book
            if ($_FILES['select_file']['error'] == 4) {
                $data['select_file_err'] = 'Please select book';
                // die("Please select book");
            } elseif ($this->validateMimeType($tmpbookname, $bookMimeType)) {
                $data['select_file_err'] = 'Please select valid book type';
            }

            if (empty($data['select_file_err'])) {
                // $Reader = new SpreadsheetReader($bookname);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpbookname);
                // $spreadsheet = $reader->getActiveSheet()->toArray(null, true, true, true);
                $no_of_sheets = $reader->getSheetCount(); // number of sheets
                $sheet_names = $reader->getSheetNames(); // names of sheets
                $tables = ['new_books', 'new_books_en', 'new_magazine', 'new_research'];

                for ($i=0; $i<$no_of_sheets; $i++) {
                    $spreadsheet = $reader->getSheet($i)->toArray(null, true, true, true);
                    $no_of_rows = count($spreadsheet); //number of rows

                    if ($this->bookModel->importNew($spreadsheet, $no_of_rows, $tables[$i])) {
                        flash('Success!', "books info imported successfuly", 'alert alert-success');
                        redirect('books/import');
                    } else {
                        flash('Error!', "could not import books", 'alert alert-danger');
                        redirect('books/import');
                    }

                    // if ($i == 1) {
                    //     $spreadsheet = $reader->getSheet(0)->toArray(null, true, true, true);
                    //     $no_of_rows = count($spreadsheet); //number of rows

                    //     if ($this->bookModel->importArBooks($spreadsheet, $no_of_rows)) {
                    //         // flash('Success!', "books info imported successfuly", 'alert alert-success');
                    //         // redirect('books/import');
                    //     } else {
                    //         flash('Error!', "could not import books", 'alert alert-danger');
                    //         redirect('books/import');
                    //     }
                    // } elseif ($i == 2) {
                    //     $spreadsheet = $reader->getSheet(1)->toArray(null, true, true, true);
                    //     $no_of_rows = count($spreadsheet); //number of rows

                    //     if ($this->bookModel->importEnBooks($spreadsheet, $no_of_rows)) {
                    //         // flash('Success!', "books info imported successfuly", 'alert alert-success');
                    //         // redirect('books/import');
                    //     } else {
                    //         flash('Error!', "could not import books", 'alert alert-danger');
                    //         redirect('books/import');
                    //     }
                    // } elseif ($i == 3) {
                    //     // $spreadsheet = $reader->getSheet(2)->toArray(null, true, true, true);
                    //     // $no_of_rows = count($spreadsheet); //number of rows

                    //     // if ($this->bookModel->importMagazine($spreadsheet, $no_of_rows)) {
                    //     //     flash('Success!', "books info imported successfuly", 'alert alert-success');
                    //     //     redirect('books/import');
                    //     // } else {
                    //     //     flash('Error!', "could not import books", 'alert alert-danger');
                    //     //     redirect('books/import');
                    //     // }
                    // } elseif ($i == 4) {
                    //     $spreadsheet = $reader->getSheet(3)->toArray(null, true, true, true);
                    //     $no_of_rows = count($spreadsheet); //number of rows

                    //     if ($this->bookModel->importResearch($spreadsheet, $no_of_rows)) {
                    //         // flash('Success!', "books info imported successfuly", 'alert alert-success');
                    //         // redirect('books/import');
                    //     } else {
                    //         flash('Error!', "could not import books", 'alert alert-danger');
                    //         redirect('books/import');
                    //     }
                    // }
                }

                // if ($this->bookModel->importArBooks($spreadsheet, $no_of_rows)) {
                //     flash('Success!', "books info imported successfuly", 'alert alert-success');
                //     redirect('books/import');
                // } else {
                //     flash('Error!', "could not import books", 'alert alert-danger');
                //     redirect('books/import');
                // }
            } else {
                $this->view('books/importbooks', $data);
            }
        } else {
            $data = [
                'select_file_err' => ''
            ];
            // Load addbook view
            $this->view('books/importbooks', $data);
        }
    }

    public function search()
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Check for post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $key = trim($_POST['search']);
            // var_dump($key);
            $path = BOOKROOT;

            if (isset($key)) {
                $books = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
                $data = array();

                foreach ($books as $book) {
                    if ($book->isDir()) {
                        continue;
                    }
                    
                    if (strripos($book->getFilename(), $key) !== false) {
                        $data[] = $book->getFilename();
                    }
                    // var_dump($data);
                }

                // Load view
                $this->view('books/searchpdf', $data);
            }
        } else {
            // Load view
            $path = BOOKROOT;
            $books = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
            $data = array();
            
            foreach ($books as $book) {
                if ($book->isDir()) {
                    continue;
                }

                $data[] = $book->getFilename();
            }

            // var_dump($data);

            $this->view('books/searchpdf', $data);
        }
    }

    public function info($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Fetch data from database
        $data = $this->bookModel->findBookById($id);

        // Init data
        $data = [
            'id' => $data->id,
            'title' => $data->title,
            'book_no' => $data->book_no,
            'author' => $data->author,
            'edition' => $data->edition,
            'publication' => $data->publication,
            'no_of_books' => $data->no_of_books,
            'container' => $data->container,
            'description' => $data->description,
            'cover_img_type' => $data->cover_img_type,
            'availability' => $data->status,
            'tag' => $data->tag,
            'copyright' => $data->copyright,
            'created_date' => $data->created_at,
            'updated_date' => $data->updated_at,
            'created_by' => $data->name,
            'updated_by' => $data->name
        ];

        // Load book info
        $this->view('books/bookinfo', $data);
    }

    public function delete($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Execute query
            $book = $this->bookModel->getBookById($id);
            $path[] = BOOKROOT . $book->book_no . '.' . $book->file_type;
            $path[] = BOOKCOVERROOT . $book->book_no . '.' . $book->cover_img_type;
            
            if ($this->bookModel->delete($id)) {
                $this->bookModel->deleteTag($id);
                foreach ($path as $book) {
                    if (file_exists($book)) {
                        unlink($book);
                    }
                }
                flash('Success!', "{$book->title} successfully deleted", 'alert alert-success');
                redirect('books/index');
            } else {
                flash('Error!', 'could not delete', 'alert alert-danger');
                redirect('books/info' . $id);
            }
        } else {
            // redirect to login
            redirect('users/login');
        }
    }

    public function download($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Get data from database
        $data = $this->bookModel->getBookById($id);
        // Get bookname
        $bookname = $data->book_no . '.' . $data->file_type;
        $basename = $data->title . '.' . $data->file_type;
        // book path
        $bookTarget = BOOKROOT . $bookname;

        if (file_exists($bookTarget) && is_file($bookTarget)) {
            header('Content-Description: book Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($basename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . dataize($bookTarget));
            ob_clean();
            flush();
            readbook($bookTarget);
            exit;
        } else {
            flash('Error!', 'The book '. $data->title . ' does not exists to download!', 'alert alert-danger');
            redirect('books/info/' . $id);
        }
    }

    public function read($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Get data from database
        $data = $this->bookModel->getBookById($id);
        // Get book type
        $bookname = $data->book_no . '.' . $data->file_type;
        // book path
        $bookTarget = BOOKROOT . $bookname;

        if (file_exists($bookTarget) && is_file($bookTarget)) {
            header('Content-Description: book Read');
            header('Content-Type: application/' . $data->file_type);
            header('Content-Disposition: inline; filename='.basename($bookTarget));
            header('Content-Transfer-Encoding: binary');
            readfile($bookTarget);
        } else {
            flash('Error!', 'The book '. $data->title . ' does not exists to read!', 'alert alert-danger');
            redirect('books/info/' . $id);
        }
    }

    public function readPDF($key)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }
        $b = $key;
        // die($key . "  " . $b);
        // $b = substr(strrchr($b, '/'), 1);

        // book path
        $path = BOOKROOT;
        $bookTarget = $path . $b;
        $books = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        foreach ($books as $book) {
            if ($book->isDir()) {
                continue;
            }
            // die($book->getFilename() . " / " . $b . " / " . $key);
            if (strripos($book->getFilename(), $b) !== false) {
                $file_path = $book->getPathname();
                // die($file_path . " / " . $bookTarget);
                header('Content-Description: Read Books');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename='.basename("$file_path"));
                header('Content-Transfer-Encoding: binary');
                readfile($file_path);
            }
        }
    }

    public function downloadPDF($key)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }
        $a = $key;
        $b = $_GET['url'];
        $b = substr(strrchr($b, '/'), 1);
        // die($a . "  " . $b);

        // book path
        $path = BOOKROOT;
        $bookTarget = $path . $b;
        $books = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        foreach ($books as $book) {
            if ($book->isDir()) {
                continue;
            }
            // die($book->getFilename() . " / " . $b . " / " . $key);
            if (strripos($book->getFilename(), $b) !== false) {
                $file_path = $book->getPathname();
                // die($file_path . " / " . $bookTarget);
                header('Content-Description: book Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($b));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . dataize($file_path));
                ob_clean();
                flush();
                readbook($file_path);
                exit;
            }
        }
    }

    public function validateMimeType($tmpbookname, $mimeType)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $allowedMimeArray = $mimeType;

        if (false === $ext = array_search(
            $finfo->file($tmpbookname),
            $allowedMimeArray,
            true
        )) {
            // Not valid formate
            return true;
        } else {
            // Valid formate
            return false;
        }
    }
}
