<?php

namespace App\Classes;

use PDO;

class Subject
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "SUBJECT CLASS";
  }

  public function total()
  {
    $sql = "SELECT COUNT(*) FROM subject.subject";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  public function count($data)
  {
    $sql = "SELECT COUNT(*) FROM subject.subject WHERE code = ? AND text = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function export()
  {
    $sql = "SELECT b.code primary_code,b.text primary_text,a.code secondary_code,a.text secondary_text,
    (
      CASE
        WHEN a.status = 1 THEN 'ACTIVE'
        WHEN a.status = 2 THEN 'INACTIVE'
        WHEN a.status = 0 THEN 'DELETE' 
        ELSE NULL
      END
    ) status
    FROM subject.subject a
    LEFT JOIN subject.subject b
    ON a.reference = b.id
    WHERE a.type = 2
    AND a.status IN (1,2)
    ORDER BY a.id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_NUM);
  }

  public function view($data)
  {
    $sql = "SELECT * FROM subject.subject WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function insert($data)
  {
    $sql = "INSERT INTO subject.subject(`uuid`, `code`, `text`, `type`, `reference`) VALUES(uuid(),?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function update($data)
  {
    $sql = "UPDATE subject.subject SET 
    text = ?,
    status = ?
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function delete($data)
  {
    $sql = "UPDATE subject.subject SET 
    status = 0
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function primary_select($keyword)
  {
    $sql = "SELECT a.id,CONCAT('[',a.code,'] ',a.text) text
    FROM subject.subject a
    WHERE a.type = 1";
    if (!empty($keyword)) {
      $sql .= " AND (a.code LIKE '%{$keyword}%' OR a.text LIKE '%{$keyword}%') ";
    }
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function directory_subject()
  {
    $sql = "SELECT text FROM subject.directory_subject GROUP BY text";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function data()
  {
    $sql = "SELECT COUNT(*) FROM subject.subject";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.id", "a.email", "a.position", "a.department", "a.area", "a.field", "a.group", "b.text", "c.text"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : "");
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_REQUEST['draw']) ? $_REQUEST['draw'] : "");

    $sql = "SELECT a.id,a.uuid,a.code,replace(a.text, '\r\n', '<br>') text,
    a.type,IF(a.type = 1,'หลัก','รอง') type_name,
    a.reference,replace(b.text, '\r\n', '<br>') reference_name,
    (
      CASE
        WHEN a.status = 1 THEN 'ใช้งาน'
        WHEN a.status = 2 THEN 'ระงับการใช้งาน'
        WHEN a.status = 0 THEN 'ลบ' 
        ELSE NULL
      END
    ) status,
    (
      CASE
        WHEN a.status = 1 THEN 'success'
        WHEN a.status = 2 THEN 'danger'
        WHEN a.status = 0 THEN 'warning' 
        ELSE NULL
      END
    ) color
    FROM subject.subject a
    LEFT JOIN subject.subject b
    ON a.reference = b.id
    WHERE a.status IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (a.text LIKE '%{$keyword}%' OR b.text LIKE '%{$keyword}%') ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.type ASC,a.code ASC ";
    }

    $sql2 = "";
    if ($limit_length) {
      $sql2 .= "LIMIT {$limit_start}, {$limit_length}";
    }

    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $filter = $stmt->rowCount();
    $stmt = $this->dbcon->prepare($sql . $sql2);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($result as $row) {
      $action = "<a href='/subject/view/{$row['uuid']}' class='badge badge-{$row['color']} font-weight-light'>{$row['status']}</a> <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['uuid']}'>ลบ</a>";

      if (!empty($row['text'])) {
        $data[] = [
          $action,
          $row['code'],
          $row['text'],
          $row['reference_name'],
        ];
      }
    }

    $output = [
      "draw"    => $draw,
      "recordsTotal"  =>  $total,
      "recordsFiltered" => $filter,
      "data"    => $data
    ];
    return $output;
  }
}
