<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\User;
use App\Classes\Subject;
use App\Classes\Validation;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
  define("JWT_SECRET", "SECRET-KEY");
  define("JWT_ALGO", "HS512");
  $jwt = (isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : "");
  if (empty($jwt)) {
    die(header("Location: /"));
  }
  $decode = JWT::decode($jwt, new Key(JWT_SECRET, JWT_ALGO));
  $email = (isset($decode->data) ? $decode->data : "");
} catch (Exception $e) {
  $msg = $e->getMessage();
  if ($msg === "Expired token") {
    die(header("Location: /logout"));
  }
}

$USER = new User();
$SUBJECT = new Subject();
$VALIDATION = new Validation();

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    $text = (isset($_POST['text']) ? $VALIDATION->input($_POST['text']) : "");

    $SUBJECT->insert([$text]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/subject");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "update") {
  try {
    $id = (isset($_POST['id']) ? $VALIDATION->input($_POST['id']) : "");
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $text = (isset($_POST['text']) ? $VALIDATION->input($_POST['text']) : "");
    $status = (isset($_POST['status']) ? $VALIDATION->input($_POST['status']) : "");

    $SUBJECT->update([$text, $status, $uuid]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/subject");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "delete") {
  try {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    if (!empty($id)) {
      $SUBJECT->delete([$id]);
      $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!");
      echo json_encode(200);
    } else {
      $VALIDATION->alert("danger", "ระบบมีปัญหา ลองใหม่อีกครั้ง!");
      echo json_encode(500);
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "primary-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $SUBJECT->primary_select($keyword);

    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "data") {
  try {
    $result = $SUBJECT->data();
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "import") {
  $start = microtime(true);
  $file_name = (isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '');
  $file_tmp = (isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '');
  $file_allow = ["xls", "xlsx", "csv"];
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

  if (!in_array($file_extension, $file_allow)) :
    $VALIDATION->alert("danger", "ONLY XLS XLSX CSV!", "/subject");
  endif;

  if ($file_extension === "xls") {
    $READER = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
  } elseif ($file_extension === "xlsx") {
    $READER = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  } else {
    $READER = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
  }

  $READ = $READER->load($file_tmp);
  $result = $READ->getActiveSheet()->toArray();
  $columns = $READ->getActiveSheet()->getHighestColumn();
  $columnsIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columns);

  $data = [];
  foreach ($result as $value) {
    $data[] = array_map("trim", $value);
  }

  foreach ($data as $key => $value) {
    $code = (!empty($value[0]) ? $value[0] : "");
    $text = (!empty($value[1]) ? $value[1] : "");
    $type = (!empty($value[2]) ? $value[2] : "");
    $ref = (!empty($value[3]) ? $value[3] : "");

    $count = $SUBJECT->count([$code, $text]);
    if (intval($count) === 0) {
      $SUBJECT->insert([$code, $text, $type, $ref]);
    }
  }


  $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/subject");
  // $end = microtime(true);
  // echo "Query Time :" . ($end - $start) . " sec.";
}
