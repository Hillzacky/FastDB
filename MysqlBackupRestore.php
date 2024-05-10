<?php class Mysql {

private static $host = $_ENV['DB_HOST'];
private static $database = $_ENV['DB_NAME'];
private static $user = $_ENV['DB_USER'];
private static $pass = $_ENV['DB_PASS'];
private static $dir = dirname(__FILE__);

 static function dump($output){
  exec("mysqldump --user={self::$user} --password={self::$pass} --host={self::$host} {self::$database} --result-file={$dir} 2>&1", $output);
  return var_dump($output);
 }

 static function connect(){
  $conn = mysqli_connect(self::$host, self::$user, self::$pass, self::$database);
  $conn->set_charset("utf8");
  return $conn ?? new Database();
 }

 static function getAllTable(){
  $tables = array();
  $sql = "SHOW TABLES";
  $result = mysqli_query(self::connect(), $sql);
  while ($row = mysqli_fetch_row($result))
   $tables[] = $row[0];
  return $tables;
 }

 static function backupDatabase($tables){
  $script = "";
  foreach ($tables as $table) {
   if(!empty($table)) $script .= self::getScript($table);
  }
  if(strlen($script) > 0) self::saveToBackupFile($script);
 }

 static function getScript($tableName){
  $sqlShowQuery = "SHOW CREATE TABLE $tableName";
  $result = mysqli_query(self::connect(), $sqlShowQuery);
  $row = mysqli_fetch_row($result);
  $fileString .= "\n\n" . $row[1] . ";\n\n"; 
  $sqlQuery = "SELECT * FROM " . $tableName;
  $result = mysqli_query(self::connect(), $sqlQuery);     
       $fieldCount = mysqli_num_fields($result);
        for ($i = 0; $i < $fieldCount; $i ++) {
         while ($row = mysqli_fetch_row($result)) {
          $fileString .= "INSERT INTO $tableName VALUES(";
           for ($j = 0; $j < $fieldCount; $j ++) {
            $row[$j] = $row[$j];   
             if (isset($row[$j])) {
              $fileString .= '"' . $row[$j] . '"';
             } else {
               $fileString .= '""';
             }
             if ($j < ($fieldCount - 1)) {
              $fileString .= ',';
             }
           }
           $fileString .= ");\n";
          }
        }
  $fileString .= "\n";
  return $fileString;
 }

 static function saveToBackupFile($fileString){
  $filename = self::$database.'_backup_'.time().'.sql';
  $fileHandler = fopen($filename, 'w+');
  fwrite($fileHandler, $fileString);
  fclose($fileHandler);
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment;filename='.basename($filename));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($filename));
  ob_clean();
  flush();
  readfile($filename);
  exec('rm ' . $filename); 
 }

 static function restore($dbfile = 'dbfile.sql'){
  $query = '';
  $sqlScript = file($dbfile);
  foreach ($sqlScript as $line)	{	
	  $startWith = substr(trim($line), 0 ,2);
	  $endWith = substr(trim($line), -1 ,1);
	  if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
   	 continue;
	  }
   $query = $query . $line;
   if ($endWith == ';') {
    mysqli_query(self::connect(),$query)
    or die('Problem : <b>'.$query.'</b>');
    $query= '';		
	  }
  }
 }

static function restoreMysqlDB($filePath){
 $sql = '';
 $error = '';    
 if (file_exists($filePath)) {
  $lines = file($filePath);        
  foreach ($lines as $line) {
   if (substr($line, 0, 2) == '--' || $line == '') {
    continue;
   }    
   $sql .= $line;      
   if (substr(trim($line), - 1, 1) == ';') {
    $result = mysqli_query($conn, $sql);
     if (! $result) {
      $error .= mysqli_error(self::connection()) . "\n";
     }
    $sql = '';
   }
  }
  if ($error) {
   $response = ["type" => "error","message" => $error ];
  } else {
   $response = [
    "code" => 200,
    "type" => "success",
    "message" => "Database Restore Completed Successfully."
   ];
  }
 }
 return $response;
}

static function receive(){
 if (! empty($_FILES)) {
  if (! in_array(strtolower(pathinfo($_FILES["backup_file"]["name"], PATHINFO_EXTENSION)), array(
        "sql"
    ))) {
        $response = array(
            "type" => "error",
            "message" => "Invalid File Type"
        );
    } else {
        if (is_uploaded_file($_FILES["backup_file"]["tmp_name"])) {
            move_uploaded_file($_FILES["backup_file"]["tmp_name"], $_FILES["backup_file"]["name"]);
            $response = restoreMysqlDB($_FILES["backup_file"]["name"]);
        }
    }
 }
}

}