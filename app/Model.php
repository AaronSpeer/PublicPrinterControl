<?php
namespace App;
class Model {
  public function sanitiseData($data)
  {
    global $conn;
    $data = mysqli_real_escape_string($conn, $data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function getMessages()
  {
    return json_decode($_COOKIE["messages"]);
    $_SESSION["messages"] = "";
  }

  public function setMessage($name, $content, $type)
  {
    global $_SESSION;
    $passed = [$name, $content, $type];
    $messages = json_decode($_SESSION["messages"]);
    $messages[] = $passed;
    $_SESSION["messages"] = json_encode($messages);
    setcookie("messages", json_encode($messages), time() + 1, "/");
  }
}
