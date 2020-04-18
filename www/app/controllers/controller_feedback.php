<?php
class Controller_Feedback extends Controller
{
    private static $view_page = "feedback_view.php";
    function __construct()
    {
        $this->model = new Model_Feedback();
        $this->view = new View();
    }

    public function action_index($param = null)
	{
		$data = $this->model->index();
		$this->view->generate(Controller_Feedback::$view_page, Controller::$template, $data);
	}
}