<?php class Mysql {

private static $host = $_ENV['DB_HOST'];
private static $database = $_ENV['DB_NAME'];
private static $user = $_ENV['DB_USER'];
private static $pass = $_ENV['DB_PASS'];

 static function dump($output){
  exec("mysqldump --user={self::$user} --password={self::$pass} --host={self::$host} {self::$database} --result-file={$dir} 2>&1", $output);
  return var_dump($output);
 }


}