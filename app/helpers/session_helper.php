<?php

session_start();

// Flash message helper
function flash($alert = '', $message = '', $class = '')
{
    $name = 'flash_message';
    if (!empty($alert) && !empty($message) && !empty($class) && empty($_SESSION[$name])) {
        // If flash message start
        $_SESSION[$name] = "<strong>{$alert} </strong>" . $message;
        $_SESSION[$name . '_class'] = $class;
    } elseif (empty($message) && !empty($_SESSION[$name])) {
        $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
        echo '<div class="' . $class . ' alert-dismissible fade show" id="msg-flash">' . $_SESSION[$name] . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';

        // If flash message already there unset session
        unset($_SESSION[$name]);
        unset($_SESSION[$name . '_class']);
    }
}

function isAdminLoggedIn()
{
    if (isset($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == 'admin') {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function isUserLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}
