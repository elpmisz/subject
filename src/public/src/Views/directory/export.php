<?php
session_start();
ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
include_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Classes\Subject;
use App\Classes\Directory;
use App\Classes\Validation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$SUBJECT = new Subject();
$DIRECTORY = new Directory();
$VALIDATION = new Validation();
$SPREADSHEET = new Spreadsheet();
$WRITER = new Xlsx($SPREADSHEET);

$param = (isset($params) ? explode("/", $params) : header("location: /error"));
$position = (!empty($param[0]) ? urldecode($param[0]) : "");
$department = (!empty($param[1]) ? urldecode($param[1]) : "");
$zone = (!empty($param[2]) ? urldecode($param[2]) : "");
$area = (!empty($param[3]) ? urldecode($param[3]) : "");
$field = (!empty($param[4]) ? urldecode($param[4]) : "");
$group = (!empty($param[5]) ? urldecode($param[5]) : "");
$ability = (!empty($param[6]) ? urldecode($param[6]) : "");
$subject = (!empty($param[7]) ? urldecode($param[7]) : "");

$result = $DIRECTORY->read($position, $department, $zone, $area, $field, $group, $ability, $subject);

$data = [];
foreach ($result as $row) {
  $subject = [];
  $subjects = $DIRECTORY->subject_read([$row['id']]);
  foreach ($subjects as $sub) {
    $subject[] = $sub['text'];
  }

  $text = [];
  $text = [
    $row['email'],
    $row['position'],
    $row['department'],
    $row['zone'],
    $row['area'],
    $row['field'],
    $row['group'],
    $row['ability'],
  ];
  $data[] = (COUNT(array_filter($subjects)) !== 0 ? array_merge($text, $subject) : $text);
}

$SPREADSHEET->setActiveSheetIndex(0);
$ACTIVESHEET = $SPREADSHEET->getActiveSheet();

$STYLEHEADER = [
  'font' => [
    'bold' => true,
  ],
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
  ]
];

$subject_count = $SUBJECT->total();
$column = ["EMAIL", "POSITION", "DEPARTMENT", "ZONE", "AREA", "FIELD", "GROUP", "ABILITY"];
$subject = [];
for ($i = 1; $i <= $subject_count; $i++) {
  $subject[] = "SUBJECT {$i}";
}
$columns = array_merge($column, $subject);

ob_start();
$date = date('Y-m-d');
$filename = "{$date}_directory.csv";
header("Content-Encoding: UTF-8");
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$filename}");
ob_end_clean();

$output = fopen("php://output", "w");
fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
fputcsv($output, $columns);

foreach ($data as $value) {
  fputcsv($output, $value);
}

fclose($output);
die();
