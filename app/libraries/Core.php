<?php
/*
* App Core Class
* Create URL & loads core controller
* URL FORMAT - /controller/method/params
*/

class Core
{
    protected $currentContoller = 'Books';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        // print_r($this->getUrl());

        $url = $this->getUrl();

        // Look in controllers for first value
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // If exists, set as current controller
            $this->currentContoller = ucwords($url[0]);
            // Unset url[0]
            unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentContoller . '.php';

        // Instantiate controller class
        $this->currentContoller = new $this->currentContoller;

        // Check for second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            // $url = (string)$url[1];
            if (method_exists($this->currentContoller, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset index 1
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentContoller, $this->currentMethod], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url); // Returns array of strings
            return $url;
        }
    }
}
