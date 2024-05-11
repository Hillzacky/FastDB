<?php class Table
{

 static function create($table){
  $sql = "CREATE TABLE IF NOT EXISTS `$table`(";
  $sql.= ") ENGINE=InnoDB utf-8";
  return Table::create($table);
 }

 static function drop($table){
  return "DROP TABLE IF EXISTS $table";
 }
}