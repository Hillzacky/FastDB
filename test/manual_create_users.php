<?php require_once '../src/FastDB.php';

$sql = "CREATE TABLE users (
  userid INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  email VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_at TIMESTAMP
  )";

try {
  DB::connect()->exec($sql);
  echo "Table users created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}