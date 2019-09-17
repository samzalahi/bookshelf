<?php

class Magazines extends Controller
{
    public function __construct()
    {
        // Init database
        $this->magModel = $this->model('Magazine');
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
            if ($search_type == 'Magazines') {
                // Get magazines
                if ($magazines = $this->magModel->getMagazinesByKey($key)) {
                    $data = [
                        'magazine' => $magazines
                    ];
                } else {
                    // If results not found
                    flash('Error!', 'No magazines found', 'alert alert-danger');
                    $magazines = $this->magModel->getAllMagazines();
                    $data = [
                        'magazine' => $magazines
                    ];
                }
            } else {
                // Get magazine
                if ($magazines = $this->magModel->getMagazinesByTag($key)) {
                    $data = [
                        'tag' => $magazines
                    ];
                } else {
                    // If results not found
                    flash('Error!', 'No magazine found for this tag', 'alert alert-danger');
                    $magazines = $this->magModel->getAllMagazines();
                    $data = [
                        'magazine' => $magazines
                    ];
                }
            }

            //Load view
            $this->view('magazines/index', $data);
        } else {
            // If not isset search
            $magazines = $this->magModel->getAllMagazines();

            $data = [
                'magazine' => $magazines
            ];
            $this->view('magazines/index', $data);
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
            // Process add magazine form

            // Sanitize POST data\
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get upload file info
            $mag = $_FILES['select_mag']['name'][0];
            $cover = $_FILES['select_mag']['name'][1];
            $magExt = pathinfo($mag, PATHINFO_EXTENSION);
            $coverExt = pathinfo($cover, PATHINFO_EXTENSION);
            $filename = ltrim($_POST['mag_no'], '0') . "_" . trim($_POST['year']) . "_" . trim($_POST['issue_no']) . '.' . $magExt;
            $covername = trim($_POST['mag_no'], '0') . "_" . trim($_POST['year']) . "_" . trim($_POST['issue_no']) . '.' . $coverExt;
            $tmpFilename = $_FILES['select_mag']['tmp_name'][0];
            $tmpCovername = $_FILES['select_mag']['tmp_name'][1];
            $targetDir = MAGAZINEROOT;
            $picDir = MAGCOVERROOT;
            $fileTarget = $targetDir . $filename;
            $picTarget = $picDir . $covername;
            $fileMimeType = [
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword'
            ];
            $picMimeType = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ];

            // die(var_dump($_FILES['select_mag']));
            $tags = strtolower($_POST['tag']);
            $tags = explode(',', trim($tags));
            $tag = array_map('trim', $tags);

            // Init data
            $data = [
                'mag_no' => trim($_POST['mag_no']),
                'title' => trim($_POST['title']),
                'issue_no' => trim($_POST['issue_no']),
                'year' => trim($_POST['year']),
                'date' => trim($_POST['date']),
                'publication' => trim($_POST['publication']),
                'container' => trim($_POST['container']),
                'description' => trim($_POST['description']),
                'availability' => trim($_POST['availability']),
                'file_type' => $magExt,
                'cover_img_type' => $coverExt,
                'mag_no_err' => '',
                'title_err' => '',
                'year_err' => '',
                'issue_no_err' => '',
                'date_err' => '',
                'publication_err' => '',
                'container_err' => '',
                'select_mag_err' => '',
                'select_mag_cover_err' => ''
            ];

            // Validate magazine no
            if (empty($data['mag_no'])) {
                $data['mag_no_err'] = 'Please enter magazine number';
            } elseif (!is_numeric($data['mag_no'])) {
                $data['mag_no_err'] = 'Please enter numeric value';
            } elseif ($this->magModel->findMagByMagNo($data['mag_no'])) {
                $data['mag_no_err'] = 'This magazine number is already exists on database';
            }

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter magazine title';
            }

            // Validate Issue no
            if (empty($data['issue_no'])) {
                $data['issue_no_err'] = 'Please enter issue number';
            } elseif (!is_numeric($data['issue_no'])) {
                $data['issue_no_err'] = 'Please enter numeric value';
            }

            // Validate year
            if (!empty($data['year']) && !is_numeric($data['year'])) {
                $data['year_err'] = 'Please enter numeric value';
            }

            // Validate date
            if (empty($data['date'])) {
                $data['date_err'] = 'Please enter magazine date';
            }

            // Validate publication
            if (empty($data['publication'])) {
                $data['publication_err'] = 'Please enter magazine publication';
            }

            // Validate magazine upload by availability
            if (trim($_POST['availability'] === 'Available')) {
                $data['availability'] = true;
                // Validate container
                if (empty($data['container'])) {
                    $data['container_err'] = 'Please enter magazine container';
                }
            } else {
                $data['availability'] = false;
            }

            // Validate magazine upload
            if (!empty($tmpFilename)) {
                if ($this->validateMimeType($tmpFilename, $fileMimeType)) {
                    $data['select_mag_err'] = 'Please select valid file type';
                } elseif ($_FILES['select_mag']['size'][0] > 1000000000) {
                    $data['select_mag_err'] = 'Files shoud be below 1GB';
                } elseif (file_exists($fileTarget)) {
                    $data['select_mag_err'] = 'This magazine is already exists on local folder';
                }
            }

            // Validate pic upload
            if (!empty($tmpCovername)) {
                if ($this->validateMimeType($tmpCovername, $picMimeType)) {
                    $data['select_mag_cover_err'] = 'Please select valid image type';
                } elseif ($_FILES['select_mag']['size'][1] > 6000000) {
                    $data['select_mag_cover_err'] = 'Image shoud be below 6MB';
                } elseif (file_exists($picTarget)) {
                    $data['select_mag_cover_err'] = 'This cover image is already exists on local folder';
                }
            }

            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['mag_no_err']) && empty($data['issue_no_err']) && empty($data['year_err']) && empty($data['date_err']) && empty($data['publication_err']) && empty($data['container_err']) && empty($data['select_mag_err']) && empty($data['select_mag_cover_err'])) {
                // Process add magazine
                // Check folder already exists or not if not create folder
   
                if ($this->magModel->add($data)) {
                    // Upload data into database
                    // Get last Id
                    $lastId = $this->magModel->getLastId();
                    
                    // Insert tags
                    if (!empty($_POST['tag'])) {
                        $this->magModel->addTag($tag, $lastId);
                    }

                    // Move file into local file server
                    if (!empty($tmpFilename)) {
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        move_uploaded_file($tmpFilename, $fileTarget);
                    }

                    // Move magazine cover into local file server
                    if (!empty($tmpCovername)) {
                        move_uploaded_file($tmpCovername, $picTarget);
                    }

                    flash('Success!', "{$data['title']} magazine uploaded successfuly", 'alert alert-success');
                    redirect('magazines/add');
                } else {
                    flash('Error!', 'Could not upload magazine', 'alert alert-danger');
                    redirect('magazines/index');
                }
            } else {
                // Load add magazine view
                $this->view('magazines/addmagazine', $data);
            }
        } else {
            // Load add magazine view
            // Init data
            $data = [
              'mag_no' => '',
              'title' => '',
              'year' => '',
              'issue_no' => '',
              'date' => '',
              'publication' => '',
              'container' => '',
              'description' => '',
              'availability' => '',
              'file_type' => '',
              'cover_img_type' => '',
              'mag_no_err' => '',
              'title_err' => '',
              'year_err' => '',
              'issue_no_err' => '',
              'date_err' => '',
              'publication_err' => '',
              'container_err' => '',
              'select_mag_err' => '',
              'select_mag_cover_err' => ''
            ];
            // Load add magazine view
            $this->view('magazines/addmagazine', $data);
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
            // Process edit magazine form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get upload file info
            $mag = $_FILES['select_mag']['name'][0];
            $cover = $_FILES['select_mag']['name'][1];
            $magExt = pathinfo($mag, PATHINFO_EXTENSION);
            $coverExt = pathinfo($cover, PATHINFO_EXTENSION);
            $filename = ltrim($_POST['mag_no'], '0') . "_" . trim($_POST['year']) . "_" . trim($_POST['issue_no']) . '.' . $magExt;
            $covername = trim($_POST['mag_no'], '0') . "_" . trim($_POST['year']) . "_" . trim($_POST['issue_no']) . '.' . $coverExt;
            $tmpFilename = $_FILES['select_mag']['tmp_name'][0];
            $tmpCovername = $_FILES['select_mag']['tmp_name'][1];
            $targetDir = MAGAZINEROOT;
            $picDir = MAGCOVERROOT;
            $fileTarget = $targetDir . $filename;
            $picTarget = $picDir . $covername;
            $fileMimeType = [
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword'
            ];
            $picMimeType = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ];

            // die(var_dump($_FILES['select_mag']));
            $tags = strtolower($_POST['tag']);
            $tags = explode(',', trim($tags));
            $tag = array_map('trim', $tags);

            // Init data
            $data = [
                'id' => $id,
                'mag_no' => trim($_POST['mag_no']),
                'title' => trim($_POST['title']),
                'year' => trim($_POST['year']),
                'issue_no' => trim($_POST['issue_no']),
                'date' => trim($_POST['date']),
                'publication' => trim($_POST['publication']),
                'container' => trim($_POST['container']),
                'description' => trim($_POST['description']),
                'availability' => trim($_POST['availability']),
                'file_type' => $magExt,
                'cover_img_type' => $coverExt,
                'mag_no_err' => '',
                'title_err' => '',
                'year_err' => '',
                'issue_no_err' => '',
                'date_err' => '',
                'publication_err' => '',
                'container_err' => '',
                'select_mag_err' => '',
                'select_mag_cover_err' => ''
            ];

            // Validate magazine no
            if (empty($data['mag_no'])) {
                $data['mag_no_err'] = 'Please enter magazine number';
            } elseif (!is_numeric($data['mag_no'])) {
                $data['mag_no_err'] = 'Please enter numeric value';
            }

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter magazine title';
            }

            // Validate Issue no
            if (empty($data['issue_no'])) {
                $data['issue_no_err'] = 'Please enter issue number';
            } elseif (!is_numeric($data['issue_no'])) {
                $data['issue_no_err'] = 'Please enter numeric value';
            }

            // Validate year
            if (!empty($data['year']) && !is_numeric($data['year'])) {
                $data['year_err'] = 'Please enter numeric value';
            }

            // Validate date
            if (empty($data['date'])) {
                $data['date_err'] = 'Please enter magazine date';
            }

            // Validate publication
            if (empty($data['publication'])) {
                $data['publication_err'] = 'Please enter magazine publication';
            }

            // Validate magazine upload by availability
            if (trim($_POST['availability'] === 'Available')) {
                $data['availability'] = true;

                // Validate container
                if (empty($data['container'])) {
                    $data['container_err'] = 'Please enter magazine container';
                }
            } else {
                $data['availability'] = false;
            }

            // Validate magazine upload
            if (!empty($tmpFilename)) {
                if ($this->validateMimeType($tmpFilename, $fileMimeType)) {
                    $data['select_mag_err'] = 'Please select valid file type';
                } elseif ($_FILES['select_mag']['size'][0] > 1000000000) {
                    $data['select_mag_err'] = 'Files shoud be below 1GB';
                } elseif (file_exists($fileTarget)) {
                    $data['select_mag_err'] = 'This magazine is already exists';
                }
            }

            // Validate pic upload
            if (!empty($tmpCovername)) {
                if ($this->validateMimeType($tmpCovername, $picMimeType)) {
                    $data['select_mag_cover_err'] = 'Please select valid image type';
                } elseif ($_FILES['select_mag']['size'][1] > 6000000) {
                    $data['select_mag_cover_err'] = 'Image shoud be below 6MB';
                } elseif (file_exists($picTarget)) {
                    $data['select_mag_cover_err'] = 'This cover image is already exists on local folder';
                }
            }

            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['mag_no_err']) && empty($data['issue_no_err']) && empty($data['year_err']) && empty($data['date_err']) && empty($data['publication_err']) && empty($data['container_err']) && empty($data['select_mag_err']) && empty($data['select_mag_cover_err'])) {
                // Process add magazine
                // Move file into local file server
                // Check folder already exists or not if not create folder
                // Update data into database
                if ($this->magModel->update($data) === true) {
                    // Insert tags
                    if (!empty($_POST['tag'])) {
                        $this->magModel->updateTag($tag, $id);
                    }

                    // Update magazine into local file server
                    if (!empty($tmpFilename)) {
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        move_uploaded_file($tmpFilename, $fileTarget);
                    }

                    // Move magazine cover into local file server
                    if (!empty($tmpCovername)) {
                        if (file_exists($picDir)) {
                            move_uploaded_file($tmpCovername, $picTarget);
                        } else {
                            flash('Error: ', 'Folder not exixts please contact admin', 'alert alert-danger');
                            redirect('magazines/edit/' . $id);
                        }
                    }
                    flash('Success!', "{$data['title']} magazine updated successfuly", 'alert alert-success');
                    redirect('magazines/info/' . $id);
                } else {
                    flash('Error:', "Could not update {$data['title']} magazine", 'alert alert-danger');
                    redirect('magazines/index');
                }
            } else {
                // Load add magazine view
                $this->view('magazines/editmagazine', $data);
            }
        } else {
            // Fetch data from database
            $data = $this->magModel->getMagazineById($id);

            // Init data
            $data = [
                'id' => $data->id,
                'mag_no' => $data->mag_no,
                'title' => $data->title,
                'year' => $data->year,
                'issue_no' => $data->issue_no,
                'date' => $data->date,
                'publication' => $data->publication,
                'container' => $data->container,
                'description' => $data->description,
                'availability' => $data->status,
                'mag_no_err' => '',
                'title_err' => '',
                'year_err' => '',
                'issue_no_err' => '',
                'date_err' => '',
                'publication_err' => '',
                'container_err' => '',
                'select_mag_err' => '',
                'select_mag_cover_err' => ''
              ];

            // Load edit magazine view
            $this->view('magazines/editmagazine', $data);
        }
    }

    public function import()
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Init file
            $filename = $_FILES['select_file']['name'];
            $magExt = pathinfo($_FILES['select_file']['name'], PATHINFO_EXTENSION);
            $filename = $_FILES['select_file']['name'].$magExt;
            $tmpFilename = $_FILES['select_file']['tmp_name'];
            $fileMimeType = [
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'xls' => 'text/xls',
                'ms-excel' => 'application/vnd.ms-excel'
            ];

                
            // Init data
            $data = [
                'select_file_err' => ''
            ];

            // Validate file
            if ($_FILES['select_file']['error'] == 4) {
                $data['select_file_err'] = 'Please select magazine file';
                // die("Please select file");
            } elseif ($this->validateMimeType($tmpFilename, $fileMimeType)) {
                $data['select_file_err'] = 'Please select valid file type';
            }
            if (empty($data['select_file_err'])) {
                // $Reader = new SpreadsheetReader($filename);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpFilename);
                $spreadsheet = $reader->getActiveSheet()->toArray(null, true, true, true);
                $nr = count($spreadsheet); //number of rows

                if ($this->magModel->import($spreadsheet, $nr)) {
                    flash('Success!', "magazines info imported successfuly", 'alert alert-success');
                    redirect('magazines/import');
                } else {
                    flash('Error!', "could not import magazines", 'alert alert-danger');
                    redirect('magazines/import');
                }
            } else {
                $this->view('magazines/importmagazines', $data);
            }
        } else {
            $data = [
                'select_file_err' => ''
            ];
            // Load import magazine view
            $this->view('magazines/importmagazines', $data);
        }
    }

    public function addissue($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process edit magazine form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get upload file info
            $mag = $_FILES['select_mag']['name'][0];
            $cover = $_FILES['select_mag']['name'][1];
            $magExt = pathinfo($mag, PATHINFO_EXTENSION);
            $coverExt = pathinfo($cover, PATHINFO_EXTENSION);
            $filename = ltrim($_POST['mag_no'], '0') . "_" . trim($_POST['year']) . "_" . trim($_POST['issue_no']) . '.' . $magExt;
            $covername = trim($_POST['mag_no'], '0') . "_" . trim($_POST['year']) . "_" . trim($_POST['issue_no']) . '.' . $coverExt;
            $tmpFilename = $_FILES['select_mag']['tmp_name'][0];
            $tmpCovername = $_FILES['select_mag']['tmp_name'][1];
            $targetDir = MAGAZINEROOT;
            $picDir = MAGCOVERROOT;
            $fileTarget = $targetDir . $filename;
            $picTarget = $picDir . $covername;
            $fileMimeType = [
                'pdf' => 'application/pdf',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'doc' => 'application/msword'
            ];
            $picMimeType = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ];

            // die(var_dump($_FILES['select_mag']));
            $tags = strtolower($_POST['tag']);
            $tags = explode(',', trim($tags));
            $tag = array_map('trim', $tags);

            // Init data
            $data = [
                'id' => $id,
                'mag_no' => trim($_POST['mag_no']),
                'title' => trim($_POST['title']),
                'year' => trim($_POST['year']),
                'issue_no' => trim($_POST['issue_no']),
                'date' => trim($_POST['date']),
                'publication' => trim($_POST['publication']),
                'container' => trim($_POST['container']),
                'description' => trim($_POST['description']),
                'availability' => trim($_POST['availability']),
                'file_type' => $magExt,
                'cover_img_type' => $coverExt,
                'mag_no_err' => '',
                'title_err' => '',
                'year_err' => '',
                'issue_no_err' => '',
                'date_err' => '',
                'publication_err' => '',
                'container_err' => '',
                'select_mag_err' => '',
                'select_mag_cover_err' => ''
            ];

            // Validate magazine no
            if (empty($data['mag_no'])) {
                $data['mag_no_err'] = 'Magazine number cannot be empty';
            } elseif (!is_numeric($data['mag_no'])) {
                $data['mag_no_err'] = 'Please enter numeric value';
            }

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Title cannot be empty';
            }

            // Validate Issue no
            if (empty($data['issue_no'])) {
                $data['issue_no_err'] = 'Please enter issue number';
            } elseif (!is_numeric($data['issue_no'])) {
                $data['issue_no_err'] = 'Please enter numeric value';
            }

            // Validate year
            if (!empty($data['year']) && !is_numeric($data['year'])) {
                $data['year_err'] = 'Please enter numeric value';
            }

            // Validate date
            if (empty($data['date'])) {
                $data['date_err'] = 'Please enter magazine date';
            }

            // Validate publication
            if (empty($data['publication'])) {
                $data['publication_err'] = 'Please enter magazine publication';
            }

            // Validate magazine upload by availability
            if (trim($_POST['availability'] === 'Available')) {
                $data['availability'] = true;

                // Validate container
                if (empty($data['container'])) {
                    $data['container_err'] = 'Please enter magazine container';
                }
            } else {
                $data['availability'] = false;
            }

            // Validate magazine upload
            if (!empty($tmpFilename)) {
                if ($this->validateMimeType($tmpFilename, $fileMimeType)) {
                    $data['select_mag_err'] = 'Please select valid file type';
                } elseif ($_FILES['select_mag']['size'][0] > 1000000000) {
                    $data['select_mag_err'] = 'Files shoud be below 1GB';
                } elseif (file_exists($fileTarget)) {
                    $data['select_mag_err'] = 'This magazine is already exists';
                }
            }

            // Validate pic upload
            if (!empty($tmpCovername)) {
                if ($this->validateMimeType($tmpCovername, $picMimeType)) {
                    $data['select_mag_cover_err'] = 'Please select valid image type';
                } elseif ($_FILES['select_mag']['size'][1] > 6000000) {
                    $data['select_mag_cover_err'] = 'Image shoud be below 6MB';
                } elseif (file_exists($picTarget)) {
                    $data['select_mag_cover_err'] = 'This cover image is already exists on local folder';
                }
            }

            // Make sure errors are empty
            if (empty($data['title_err']) && empty($data['mag_no_err']) && empty($data['issue_no_err']) && empty($data['year_err']) && empty($data['date_err']) && empty($data['publication_err']) && empty($data['container_err']) && empty($data['select_mag_err']) && empty($data['select_mag_cover_err'])) {
                // Process add magazine
                // Move file into local file server
                // Check folder already exists or not if not create folder
                // Update data into database
                if ($this->magModel->add($data)) {
                     // Get last Id
                     $lastId = $this->magModel->getLastId();

                    // Insert tags
                    if (!empty($_POST['tag'])) {
                        $this->magModel->updateTag($tag, $lastId);
                    }

                    // Add issue magazine into local file server
                    if (!empty($tmpFilename)) {
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        move_uploaded_file($tmpFilename, $fileTarget);
                    }

                    // Move magazine cover into local file server
                    if (!empty($tmpCovername)) {
                        if (file_exists($picDir)) {
                            move_uploaded_file($tmpCovername, $picTarget);
                        } else {
                            flash('Error: ', 'Folder not exixts please contact admin', 'alert alert-danger');
                            redirect('magazines/edit/' . $id);
                        }
                    }
                    
                    flash('Success!', "{$data['title']} magazine updated successfuly", 'alert alert-success');
                    redirect('magazines/info/' . $id);
                } else {
                    flash('Error:', "Could not update {$data['title']} magazine", 'alert alert-danger');
                    redirect('magazines/index');
                }
            } else {
                // Load add magazine view
                $this->view('magazines/addissue', $data);
            }
        } else {
            // Fetch data from database
            $data = $this->magModel->getMagazineById($id);

            // Init data
            $data = [
                'id' => $data->id,
                'mag_no' => $data->mag_no,
                'title' => $data->title,
                'year' => '',
                'issue_no' => '',
                'date' => '',
                'publication' => '',
                'container' => '',
                'description' => '',
                'availability' => '',
                'file_type' => '',
                'cover_img_type' => '',
                'mag_no_err' => '',
                'title_err' => '',
                'year_err' => '',
                'issue_no_err' => '',
                'date_err' => '',
                'publication_err' => '',
                'container_err' => '',
                'select_mag_err' => '',
                'select_mag_cover_err' => ''
              ];

            // Load edit magazine view
            $this->view('magazines/addissue', $data);
        }
    }

    public function info($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Fetch data from database
        $data = $this->magModel->findMagazineById($id);

        // Init data
        $data = [
            'id' => $data->id,
            'mag_no' => $data->mag_no,
            'title' => $data->title,
            'year' => $data->year,
            'issue_no' => $data->issue_no,
            'date' => $data->date,
            'publication' => $data->publication,
            'container' => $data->container,
            'description' => $data->description,
            'availability' => $data->status,
            'file_type' => $data->file_type,
            'cover_img_type' => $data->cover_img_type,
            'created_date' => $data->created_at,
            'updated_date' => $data->updated_at,
            'created_by' => $data->name,
            'updated_by' => $data->name
        ];

        // Load magazine info
        $this->view('magazines/magazineinfo', $data);
    }

    public function delete($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Execute query
            $magazine = $this->magModel->getMagazineById($id);
            $path[] = MAGAZINEROOT . $magazine->mag_no ."_". $magazine->year ."_". $magazine->issue_no .".". $magazine->file_type;
            $path[] = MAGCOVERROOT . $magazine->mag_no ."_". $magazine->year ."_". $magazine->issue_no .".". $magazine->cover_img_type;
            if ($this->magModel->delete($id)) {
                $this->magModel->deleteTag($id);
                foreach ($path as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                flash('Success!', "{$magazine->title} Successfully deleted", 'alert alert-success');
                redirect('magazines/index/');
            } else {
                flash('Error!', 'Could not delete', 'alert alert-danger');
                redirect('magazines/info/' . $id);
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
        $data = $this->magModel->getMagazineById($id);
        // Get filename
        $filename = $data->mag_no . '_' . $data->year . '_' . $data->issue_no . '.' . $data->file_type;
        $basename = $data->title . '.' . $data->file_type;
        // File path
        $fileTarget = MAGAZINEROOT . $filename;

        if (file_exists($fileTarget) && is_file($fileTarget)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($basename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileTarget));
            ob_clean();
            flush();
            readfile($fileTarget);
            exit;
        } else {
            flash('Error!', 'The file '. $data->title . ' does not exists to download!', 'alert alert-danger');
            redirect('magazines/info/' . $id);
        }
    }

    public function read($id)
    {
        // Check users
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        // Get data from database
        $data = $this->magModel->getMagazineById($id);
        // Get file type
        $filename = $data->mag_no . '_' . $data->year . '_' . $data->issue_no .  '.' . $data->file_type;
        // File path
        $fileTarget = MAGAZINEROOT . $filename;

        if (file_exists($fileTarget) && is_file($fileTarget)) {
            header('Content-Description: File Read');
            header('Content-Type: application/' . $data->file_type);
            header('Content-Disposition: inline; filename='.basename($fileTarget));
            header('Content-Transfer-Encoding: binary');
            readfile($fileTarget);
        } else {
            flash('Error!', 'The file '. $data->title . ' does not exists to read!', 'alert alert-danger');
            redirect('magazines/info/' . $id);
        }
    }

    public function validateMimeType($tmpFilename, $mimeType)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $allowedMimeArray = $mimeType;

        if (false === $ext = array_search(
            $finfo->file($tmpFilename),
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
