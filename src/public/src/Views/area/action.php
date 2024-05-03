<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\Area;
use App\Classes\User;
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
$AREA = new Area();
$VALIDATION = new Validation();

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "create") {
  try {
    $text = (isset($_POST['text']) ? $VALIDATION->input($_POST['text']) : "");

    $AREA->insert([$text]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/area");
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

    $AREA->update([$text, $status, $uuid]);
    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/area");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}


if ($action === "delete") {
  try {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    if (!empty($id)) {
      $AREA->delete([$id]);
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

if ($action === "data") {
  try {
    $result = $AREA->data();
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
