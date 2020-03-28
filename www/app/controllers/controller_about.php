<?php
class Controller_About extends Controller
{
    private static $view_page = "about_view.php";
    function __construct()
    {
        $this->view = new View();
    }
        function action_index($param = null)
    {
        $this->view->generate(Controller_About::$view_page, Controller::$template);
    }

}