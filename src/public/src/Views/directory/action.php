<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\User;
use App\Classes\Ability;
use App\Classes\Area;
use App\Classes\Field;
use App\Classes\Group;
use App\Classes\Department;
use App\Classes\Subject;
use App\Classes\Position;
use App\Classes\Directory;

use App\Classes\Validation;
use App\Classes\Zone;
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
$ZONE = new Zone();
$AREA = new Area();
$FIELD = new Field();
$GROUP = new Group();
$ABILITY = new Ability();
$SUBJECT = new Subject();
$POSITION = new Position();
$DEPARTMENT = new Department();
$DIRECTORY = new Directory();
$VALIDATION = new Validation();

$user = $USER->user_view_email([$email]);

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : die(header("Location: /error")));
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

if ($action === "import") {
  $start = microtime(true);
  $file_name = (isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '');
  $file_tmp = (isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '');
  $file_allow = ["xls", "xlsx", "csv"];
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

  if (!in_array($file_extension, $file_allow)) :
    $VALIDATION->alert("danger", "ONLY XLS XLSX CSV!", "/directory");
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
    if (!in_array($key, [0])) {
      $email = (!empty($value[0]) ? $value[0] : "");
      $position = (!empty($value[1]) ? $value[1] : "");
      $department = (!empty($value[2]) ? $value[2] : "");
      $zone = (!empty($value[3]) ? $value[3] : "");
      $area = (!empty($value[4]) ? $value[4] : "");
      $field = (!empty($value[5]) ? $value[5] : "");
      $group = (!empty($value[6]) ? $value[6] : "");
      $ability = (!empty($value[7]) ? explode(" ", preg_replace("/\d+\. /", "", $value[7])) : []);

      $count = $DIRECTORY->count([$email]);
      if (!empty($email) && intval($count) === 0) {
        $DIRECTORY->insert([$email, $position, $department, $zone, $area, $field, $group]);
        $request_id = $DIRECTORY->last_insert_id();

        if (COUNT(array_filter($ability)) > 0) {
          foreach (array_filter($ability) as $text) {
            $DIRECTORY->ability_insert([$request_id, $text]);
          }
        }

        for ($i = 8; $i <= $columnsIndex; $i++) {
          $subject = (!empty($value[$i]) ? $value[$i] : "");
          if (!empty($subject)) {
            $DIRECTORY->subject_insert([$request_id, $subject]);
          }
        }
      }
    }
  }

  $abilitys = $ABILITY->directory_ability();
  foreach ($abilitys as $value) {
    $count = $ABILITY->count([$value['text']]);
    if (!empty($value['text']) && intval($count) === 0) {
      $ABILITY->insert([$value['text']]);
    }
  }

  $subjects = $SUBJECT->directory_subject();
  foreach ($subjects as $value) {
    $count = $SUBJECT->count([$value['text']]);
    if (!empty($value['text']) && intval($count) === 0) {
      $SUBJECT->insert([$value['text']]);
    }
  }

  $positions = $POSITION->directory_position();
  foreach ($positions as $value) {
    $count = $POSITION->count([$value['position']]);
    if (!empty($value['position']) && intval($count) === 0) {
      $POSITION->insert([$value['position']]);
    }
  }

  $departments = $DEPARTMENT->directory_department();
  foreach ($departments as $value) {
    $count = $DEPARTMENT->count([$value['department']]);
    if (!empty($value['department']) && intval($count) === 0) {
      $DEPARTMENT->insert([$value['department']]);
    }
  }

  $zones = $ZONE->directory_zone();
  foreach ($zones as $value) {
    $count = $ZONE->count([$value['zone']]);
    if (!empty($value['zone']) && intval($count) === 0) {
      $ZONE->insert([$value['zone']]);
    }
  }

  $areas = $AREA->directory_area();
  foreach ($areas as $value) {
    $count = $AREA->count([$value['area']]);
    if (!empty($value['area']) && intval($count) === 0) {
      $AREA->insert([$value['area']]);
    }
  }

  $fields = $FIELD->directory_field();
  foreach ($fields as $value) {
    $count = $FIELD->count([$value['field']]);
    if (!empty($value['field']) && intval($count) === 0) {
      $FIELD->insert([$value['field']]);
    }
  }

  $groups = $GROUP->directory_group();
  foreach ($groups as $value) {
    $count = $GROUP->count([$value['group']]);
    if (!empty($value['group']) && intval($count) === 0) {
      $GROUP->insert([$value['group']]);
    }
  }

  $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/directory");
  $end = microtime(true);
  // echo "Query Time :" . ($end - $start) . " sec.";
}

if ($action === "update") {
  try {
    $id = (isset($_POST['id']) ? $VALIDATION->input($_POST['id']) : "");
    $uuid = (isset($_POST['uuid']) ? $VALIDATION->input($_POST['uuid']) : "");
    $email = (isset($_POST['email']) ? $VALIDATION->input($_POST['email']) : "");
    $position = (isset($_POST['position']) ? $VALIDATION->input($_POST['position']) : "");
    $department = (isset($_POST['department']) ? $VALIDATION->input($_POST['department']) : "");
    $area = (isset($_POST['area']) ? $VALIDATION->input($_POST['area']) : "");
    $field = (isset($_POST['field']) ? $VALIDATION->input($_POST['field']) : "");
    $group = (isset($_POST['group']) ? $VALIDATION->input($_POST['group']) : "");
    $ability = (isset($_POST['ability']) ? array_filter($_POST['ability']) : "");
    $ability = (!empty($ability) ? $ability : []);
    $subject = (isset($_POST['subject']) ? array_filter($_POST['subject']) : "");
    $subject = (!empty($subject) ? $subject : []);

    $abilitys = $DIRECTORY->ability_view([$uuid]);
    $ab_arr = [];
    foreach ($abilitys as $ab) {
      $ab_arr[] = $ab['text'];
    }

    $ab_diff = array_diff($ab_arr, $ability);
    if (COUNT($ab_diff) > 0) {
      foreach ($ab_diff as $value) {
        $DIRECTORY->ability_delete([$id, $value]);
      }
    }

    foreach ($ability as $value) {
      $count = $DIRECTORY->ability_count([$id, $value]);
      if (intval($count) === 0) {
        $DIRECTORY->ability_insert([$id, $value]);
      }
    }

    $subjects = $DIRECTORY->subject_view([$uuid]);
    $sub_arr = [];
    foreach ($subjects as $sub) {
      $sub_arr[] = $sub['text'];
    }
    $sub_diff = array_diff($sub_arr, $subject);
    if (COUNT($sub_diff) > 0) {
      foreach ($sub_diff as $value) {
        $DIRECTORY->subject_delete([$id, $value]);
      }
    }

    foreach ($subject as $value) {
      $count = $DIRECTORY->subject_count([$id, $value]);
      if (intval($count) === 0) {
        $DIRECTORY->subject_insert([$id, $value]);
      }
    }

    $VALIDATION->alert("success", "ดำเนินการเรียบร้อย!", "/directory/edit/{$uuid}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "data") {
  try {
    $position = (isset($_POST['position']) ? $VALIDATION->input($_POST['position']) : "");
    $department = (isset($_POST['department']) ? $VALIDATION->input($_POST['department']) : "");
    $zone = (isset($_POST['zone']) ? $VALIDATION->input($_POST['zone']) : "");
    $area = (isset($_POST['area']) ? $VALIDATION->input($_POST['area']) : "");
    $field = (isset($_POST['field']) ? $VALIDATION->input($_POST['field']) : "");
    $group = (isset($_POST['group']) ? $VALIDATION->input($_POST['group']) : "");
    $ability = (isset($_POST['ability']) ? $VALIDATION->input($_POST['ability']) : "");
    $subject = (isset($_POST['subject']) ? $VALIDATION->input($_POST['subject']) : "");

    $result = $DIRECTORY->data($position, $department, $zone, $area, $field, $group, $ability, $subject, $user['level']);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "email-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->email_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "position-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->position_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "department-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->department_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "zone-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->zone_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "area-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->area_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "field-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->field_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "group-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->group_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "ability-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->ability_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}

if ($action === "subject-select") {
  try {
    $keyword = (isset($_POST['q']) ? $VALIDATION->input($_POST['q']) : "");
    $result = $DIRECTORY->subject_select($keyword);
    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
