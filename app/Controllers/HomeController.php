<?php
/**
 * Public Printer Control System
 *
 * Copyright © 2018 - 2019, Aaron Speer, aaron.speerfamily.ie ajamesspeer@gmail.com.
 * All Rights Reserved.
 */

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
    global $config;
    echo $this->twig->render('home.twig', array(
			"messages" => $this->model->getMessages(),
      "instr" => $config["step2instructions"],
      "name" => $config["appname"]
    ));
  }

  public function upload(){
    $name = md5(random_int(1, 10000) . microtime()).md5(random_int(1, 10000) . microtime());
    $address = "/tmp/".$name."/".str_replace(" ", " ", basename( $_FILES['file']['name']));
    $address = substr_replace($address , 'pdf', strrpos($address , '.') +1);
    $targetfolder = __DIR__."/../../public/tmp/".$name ."/";

    exec("mkdir ".$targetfolder);
    $targetfolder = addslashes($targetfolder);
    $targetfolder = $targetfolder . basename( $_FILES['file']['name']) ;

    if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder)){
      $targetfolder = str_replace(" ", "\ ", $targetfolder);
      $tf = $targetfolder;
      $cmd = "sudo /usr/bin/unoconv --output=".$targetfolder." -f pdf ".$targetfolder;

      $file_parts = pathinfo($tf);
      if($file_parts["extension"] == "pdf")
      {
        exec("ls");
      }
      else{
        exec($cmd);
      }

      global $config;

      echo $this->twig->render('upload.twig', array(
  			"messages" => $this->model->getMessages(),
        "link" => $address,
        "printers" => $config["printers"],
        "path" => escapeshellcmd($address),
        "instr" => $config["failedinstructions"],
        "name" => $config["appname"]
      ));
    }
    else {
      echo "Problem uploading file";
    }
  }

  public function printt(){
    $printer = $_POST["printer"];
    $copies = $_POST["copies"];
    $path = $_POST["path"];
    $printer=escapeshellcmd($printer);
    $copies=escapeshellcmd($copies);
    $path=str_replace(" ", "\ ", escapeshellcmd($path));
    $path = __DIR__."/../../public".$path;

    $cmd = "lp -d ".$printer." -n ".$copies." ".$path;
    echo $cmd;
    echo exec($cmd);
    $this->model->setMessage("Printing Successful!", "That file was printed ".$copies." time(s) on printer ".$printer, "success");
    header("Location: /");

  }
}
