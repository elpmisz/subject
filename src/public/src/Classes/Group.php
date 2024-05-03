<?php

namespace App\Classes;

use PDO;

class Group
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "GROUP CLASS";
  }

  public function count($data)
  {
    $sql = "SELECT COUNT(*) FROM subject.group WHERE text = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function export()
  {
    $sql = "SELECT text,
    (
      CASE
        WHEN status = 1 THEN 'ACTIVE'
        WHEN status = 2 THEN 'INACTIVE'
        WHEN status = 0 THEN 'DELETE' 
        ELSE NULL
      END
    ) status
    FROM subject.group
    WHERE status IN (1,2)";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_NUM);
  }

  public function view($data)
  {
    $sql = "SELECT * FROM subject.group WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function insert($data)
  {
    $sql = "INSERT INTO subject.group(`uuid`, `text`) VALUES(uuid(),?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function update($data)
  {
    $sql = "UPDATE subject.group SET 
    text = ?,
    status = ?
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function delete($data)
  {
    $sql = "UPDATE subject.group SET 
    status = 0
    WHERE uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function directory_group()
  {
    $sql = "SELECT group FROM subject.directory_request GROUP BY group";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function data()
  {
    $sql = "SELECT COUNT(*) FROM subject.group";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.status", "a.text"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : "");
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_REQUEST['draw']) ? $_REQUEST['draw'] : "");

    $sql = "SELECT a.id,a.uuid,replace(a.text, '\r\n', '<br>') text,
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
    FROM subject.group a
    WHERE a.status IN (1,2) ";

    if (!empty($keyword)) {
      $sql .= " AND (a.text LIKE '%{$keyword}%') ";
    }

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.status ASC, a.text ASC ";
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
      $action = "<a href='/group/view/{$row['uuid']}' class='badge badge-{$row['color']} font-weight-light'>{$row['status']}</a> <a href='javascript:void(0)' class='badge badge-danger font-weight-light btn-delete' id='{$row['uuid']}'>ลบ</a>";

      if (!empty($row['text'])) {
        $data[] = [
          $action,
          $row['text'],
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
