<?php

class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        // Check user admin
        if (!isAdminLoggedIn()) {
            redirect('users/login');
        }
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Proccess register form

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //init data
            $data = [
              'name' => trim($_POST['name']),
              'email' => trim($_POST['email']),
              'password' => trim($_POST['password']),
              'confirm_password' => trim($_POST['confirm_password']),
              'user_type' => trim($_POST['user_type']),
              'name_err' => '',
              'email_err' => '',
              'password_err' => '',
              'confirm_password_err' => ''
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                // Check this email already teken
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            } else {
                $data['email_err'] = 'Please enter valid email';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at lease 6 characters';
            }

            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please enter confirm password';
            }

            // Validate password match
            if ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }

            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) &&
            empty($data['confirm_password_err'])) {
                // Process register user
                // Hash the paasword
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Upload data into database
                if ($this->userModel->register($data)) {
                    flash('Success!', 'You are registered and can login', 'alert alert-success');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view
                $this->view('users/register', $data);
            }
        } else {
            //init data
            $data = [
              'name' => '',
              'email' => '',
              'password' => '',
              'confirm_password' => '',
              'name_err' => '',
              'email_err' => '',
              'password_err' => '',
              'confirm_password_err' => ''
            ];
            //load register view
            $this->view('users/register', $data);
        }
    }

    public function login()
    {
        // Check for user login
        if (isUserLoggedIn()) {
            redirect('books/index');
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Proccess register form

            // Sanitize input POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'password' => trim($_POST['password']),
                'name_err' => '',
                'password_err' => ''
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check user by name
            if ($this->userModel->findUserByName($data['name'])) {
                // User found
            } else {
                $data['name_err'] = 'User not found';
            }

            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['password_err'])) {
                // Register user
                $loggedInUser = $this->userModel->login($data);

                if ($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                // Load data
                $this->view('users/login', $data);
            }
        } else {
            //init data
            $data = [
              'name' => '',
              'password' => '',
              'name_err' => '',
              'password_err' => ''
            ];
            //load register view
            $this->view('users/login', $data);
        }
    }

    public function manageusers()
    {
        // Check user admin
        if (!isAdminLoggedIn()) {
            redirect('users/login');
        }

        // Init data
        $data = [
            'text' => 'Manage user'
        ];

        // Load view
        $this->view('users/manageuser', $data);
    }

    public function profile($id)
    {
        // Check user admin
        if (!isUserLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Init data
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'user_type' => trim($_POST['user_type'])
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at lease 6 characters';
            }

            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['password_err'])) {
                // Process register user
                // Hash the paasword
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Upload data into database
                if ($this->userModel->update($data)) {
                    flash('Success!', 'Info saved', 'alert alert-success');
                    redirect('users/login/');
                } else {
                    flash('Error!', 'Something went wrong', 'alert alert-danger');
                    redirect('users/profile/' . $id);
                }
            } else {
                // Load view
                $this->view('users/profile', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'password' => '',
                'user_type' => $_SESSION['user_type']
            ];

            // Load view
            $this->view('users/profile', $data);
        }
    }

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_type'] = $user->type;
        redirect('books');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_type']);
        session_destroy();
        redirect('users/login');
    }
}
