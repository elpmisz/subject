<?php

namespace App\Classes;

use PDO;

class Directory
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "DIRECTORY CLASS";
  }

  public function count($data)
  {
    $sql = "SELECT COUNT(*) FROM subject.directory_request WHERE email = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function ability_count($data)
  {
    $sql = "SELECT COUNT(*) FROM subject.directory_ability WHERE request_id = ? AND text = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }

  public function subject_count($data)
  {
    $sql = "SELECT COUNT(*) FROM subject.directory_subject WHERE request_id = ? AND text = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchColumn();
  }


  public function insert($data)
  {
    $sql = "INSERT INTO subject.directory_request(`uuid`, `email`, `position`, `department`, `zone`, `area`, `field`, `group`) VALUES(uuid(),?,?,?,?,?,?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function ability_insert($data)
  {
    $sql = "INSERT INTO subject.directory_ability(`request_id`, `text`) VALUES(?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_insert($data)
  {
    $sql = "INSERT INTO subject.directory_subject(`request_id`, `text`) VALUES(?,?)";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function view($data)
  {
    $sql = "SELECT *
    FROM subject.directory_request a
    WHERE a.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function ability_view($data)
  {
    $sql = "SELECT text
    FROM subject.directory_ability a
    LEFT JOIN subject.directory_request b
    ON a.request_id = b.id
    WHERE b.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function subject_view($data)
  {
    $sql = "SELECT text
    FROM subject.directory_subject a
    LEFT JOIN subject.directory_request b
    ON a.request_id = b.id
    WHERE b.uuid = ?";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function ability_delete($data)
  {
    $sql = "DELETE FROM subject.directory_ability
    WHERE request_id = ? AND text = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_delete($data)
  {
    $sql = "DELETE FROM subject.directory_subject
    WHERE request_id = ? AND text = ?";
    $stmt = $this->dbcon->prepare($sql);
    return $stmt->execute($data);
  }

  public function subject_read($data)
  {
    $sql = "SELECT a.id,a.text 
    FROM subject.directory_subject a
    WHERE a.request_id = ?
    ORDER BY a.text";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function read($position, $department, $zone, $area, $field, $group, $ability, $subject)
  {
    $sql = "SELECT a.id,a.email,a.`position`,a.department,a.zone,a.area,a.`field`,a.`group`,
    GROUP_CONCAT(DISTINCT b.text) ability,
    GROUP_CONCAT(DISTINCT c.text) subject
    FROM subject.directory_request a
    LEFT JOIN subject.directory_ability b
    ON a.id = b.request_id
    LEFT JOIN subject.directory_subject c
    ON a.id = c.request_id
    WHERE a.id != '' ";

    if (!empty($position)) {
      $sql .= " AND a.position = '{$position}' ";
    }
    if (!empty($department)) {
      $sql .= " AND a.department = '{$department}' ";
    }
    if (!empty($zone)) {
      $sql .= " AND a.zone = '{$zone}' ";
    }
    if (!empty($area)) {
      $sql .= " AND a.area = '{$area}' ";
    }
    if (!empty($field)) {
      $sql .= " AND a.field = '{$field}' ";
    }
    if (!empty($group)) {
      $sql .= " AND a.group = '{$group}' ";
    }
    if (!empty($ability)) {
      $sql .= " AND b.text = '{$ability}' ";
    }
    if (!empty($subject)) {
      $sql .= " AND c.text = '{$subject}' ";
    }
    $sql .= " GROUP BY a.id";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function ability_select($keyword)
  {
    $sql = "SELECT text id,text 
    FROM subject.ability ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC ";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function subject_select($keyword)
  {
    $sql = "SELECT a.id,CONCAT('[',a.code,'] ',a.text) text
    FROM subject.subject a
    WHERE a.type = 2 ";
    if (!empty($keyword)) {
      $sql .= " AND (a.code LIKE '%{$keyword}%' OR a.text LIKE '%{$keyword}%') ";
    }
    $sql .= " ORDER BY a.code ASC ";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function email_select($keyword)
  {
    $sql = "SELECT email id, email text 
    FROM subject.directory_request ";
    if (!empty($keyword)) {
      $sql .= " WHERE email LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY email ORDER BY email ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function position_select($keyword)
  {
    $sql = "SELECT text id, text 
    FROM subject.position ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function department_select($keyword)
  {
    $sql = "SELECT text id, text 
    FROM subject.department ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function zone_select($keyword)
  {
    $sql = "SELECT text id, text 
    FROM subject.zone ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function area_select($keyword)
  {
    $sql = "SELECT text id, text 
    FROM subject.area ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function field_select($keyword)
  {
    $sql = "SELECT text id, text 
    FROM subject.field ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function group_select($keyword)
  {
    $sql = "SELECT text id, text 
    FROM subject.group ";
    if (!empty($keyword)) {
      $sql .= " WHERE text LIKE '%{$keyword}%' ";
    }
    $sql .= " GROUP BY text ORDER BY text ASC LIMIT 50";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function data($position, $department, $zone, $area, $field, $group, $ability, $subject, $level)
  {
    $sql = "SELECT COUNT(*) FROM subject.directory_request";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    $column = ["a.id", "a.email", "a.position", "a.department", "a.zone", "a.area", "a.field", "a.group", "b.text", "c.text"];

    $keyword = (isset($_POST['search']['value']) ? trim($_POST['search']['value']) : "");
    $filter_order = (isset($_POST['order']) ? $_POST['order'] : "");
    $order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
    $order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
    $limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
    $limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
    $draw = (isset($_REQUEST['draw']) ? $_REQUEST['draw'] : "");

    $sql = "SELECT a.id,a.uuid,a.email,a.`position`,a.department,a.zone,a.area,a.`field`,a.`group`,
    GROUP_CONCAT(DISTINCT b.text) ability,
    GROUP_CONCAT(DISTINCT c.text) subject
    FROM subject.directory_request a
    LEFT JOIN subject.directory_ability b
    ON a.id = b.request_id
    LEFT JOIN subject.directory_subject c
    ON a.id = c.request_id
    WHERE a.id != '' ";

    if (!empty($position)) {
      $sql .= " AND a.position = '{$position}' ";
    }
    if (!empty($department)) {
      $sql .= " AND a.department = '{$department}' ";
    }
    if (!empty($zone)) {
      $sql .= " AND a.zone = '{$zone}' ";
    }
    if (!empty($area)) {
      $sql .= " AND a.area = '{$area}' ";
    }
    if (!empty($field)) {
      $sql .= " AND a.field = '{$field}' ";
    }
    if (!empty($group)) {
      $sql .= " AND a.group = '{$group}' ";
    }
    if (!empty($ability)) {
      $sql .= " AND b.text = '{$ability}' ";
    }
    if (!empty($subject)) {
      $sql .= " AND c.text = '{$subject}' ";
    }
    if (!empty($keyword)) {
      $sql .= " AND (a.email LIKE '%{$keyword}%' OR a.position LIKE '%{$keyword}%' OR a.department LIKE '%{$keyword}%' OR a.zone LIKE '%{$keyword}%' OR a.area LIKE '%{$keyword}%' OR a.field LIKE '%{$keyword}%' OR a.group LIKE '%{$keyword}%' OR b.text LIKE '%{$keyword}%' OR c.text LIKE '%{$keyword}%') ";
    }

    $sql .= " GROUP BY a.id ";

    if ($filter_order) {
      $sql .= " ORDER BY {$column[$order_column]} {$order_dir} ";
    } else {
      $sql .= " ORDER BY a.id ASC, a.department DESC ";
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
      $page = (intval($level) === 9 ? "edit" : "view");
      $action = "<a href='/directory/{$page}/{$row['uuid']}' class='badge badge-primary font-weight-light'>รายละเอียด</a>";

      if (!empty($row['email'])) {
        $data[] = [
          $action,
          $row['email'],
          $row['position'],
          $row['department'],
          $row['zone'],
          $row['area'],
          $row['field'],
          $row['group'],
          str_replace(",", ",<br>", $row['ability']),
          str_replace(",", ",<br>", $row['subject']),
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

  public function last_insert_id()
  {
    return $this->dbcon->lastInsertId();
  }
}
