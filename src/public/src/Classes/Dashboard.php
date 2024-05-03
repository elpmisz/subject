<?php

namespace App\Classes;

use PDO;

class Dashboard
{
  private $dbcon;

  public function __construct()
  {
    $db = new Database();
    $this->dbcon = $db->getConnection();
  }

  public function hello()
  {
    return "DASHBOARD CLASS";
  }

  public function card()
  {
    $sql = "SELECT FORMAT(COUNT(a.email),0) email,
    FORMAT(COUNT(DISTINCT a.`position`),0) `position`,
    FORMAT(COUNT(DISTINCT a.department),0) department,
    FORMAT(COUNT(DISTINCT a.zone),0) `zone`,
    FORMAT(COUNT(DISTINCT a.area),0) `area`,
    FORMAT(COUNT(DISTINCT a.`field`),0) `field`,
    FORMAT(COUNT(DISTINCT a.`group`),0) `group`,
    (SELECT FORMAT(COUNT(*),0) FROM subject.user) `user`,
    (SELECT FORMAT(COUNT(*),0) FROM subject.ability) ability,
    (SELECT FORMAT(COUNT(*),0) FROM subject.subject) subject
    FROM subject.directory_request a
    ";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function ability_read()
  {
    $sql = "SELECT text
    FROM subject.ability a
    WHERE a.`status` = 1
    ORDER BY a.text";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function subject_read()
  {
    $sql = "SELECT text
    FROM subject.subject a
    WHERE a.`status` = 1
    ORDER BY a.text";
    $stmt = $this->dbcon->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
