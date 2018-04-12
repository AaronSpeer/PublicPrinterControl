<?php
namespace App\Controllers;

use App\Models\HomeModel;
use App\Controller;
use App\Model;

class HomeController extends Controller {

  public function __construct($twig)
  {
    $this->twig = $twig;
    $this->model = new HomeModel;
  }

  public function index(){
    echo $this->twig->render('home.twig', array(
			"messages" => $this->model->getMessages(),
    ));
  }
}
