<?php

class Pages extends Controller
{
    public function __construct()
    {
        // echo 'Page Loaded' . '<br>';
    }

    public function index()
    {
        // echo 'This is index page';
        $data =  [
          'title' => 'INDEX'
        ];

        $this->view('pages/index', $data);
    }

    public function about()
    {
        // echo 'This is about page';
        $data =  [
            'title' => 'ABOUT US'
        ];

        $this->view('pages/about', $data);
    }
}
