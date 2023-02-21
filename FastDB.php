<?php class DB
{
  static function connect(){
    $c = [
      "DB_DRIVER"   => $_ENV["DB_DRIVER"],
      "DB_HOST"     => $_ENV["DB_HOST"],
      "DB_NAME"     => $_ENV["DB_NAME"],
      "DB_USERNAME" => $_ENV["DB_USERNAME"],
      "DB_PASSWORD" => $_ENV["DB_PASSWORD"],
      "DB_OPTIONS"  => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
      ]
    ];
    try {
      $db = new PDO($c['DB_DRIVER'].':host='.$c['DB_HOST'].';dbname='.$c['DB_NAME'].';'.$c['DB_OPTIONS'],$c['DB_USERNAME'],$c['DB_PASSWORD']);
      return $db;
    } catch (PDOException $e) {
      exit('DB Connection Failed: '.$e->getMessage());
    }
  }

	static function get($q, $p=[], $m=PDO::FETCH_ASSOC)
	{
		$s = DB::connect()->prepare($q);
		foreach ($p as $k => $v) $s->bindValue("$k", $v);
		if (!$s->execute()) {
      printf($s->error);
    } else {
      $s->store_result();
      return ($s->num_rows > 0) ? $s->fetchAll($m) : json_encode(["message"=>"no data"]);
    }
    $s->close();
	}
		
	static function add($t, $d)
	{
		ksort($d);
		$fn = implode('`, `', array_keys($d));
		$fv = ':' . implode(', :', array_keys($d));
		$s = DB::connect()->prepare("INSERT INTO $t (`$fn`) VALUES ($fv)");
		foreach ($d as $k => $v) $s->bindValue(":$k", $v);
		return $s->execute();
	}

	static function set($t, $d, $w)
	{
		ksort($d);$f = NULL;
		foreach($d as $k=> $v) $f .= "`$k`=:$k,";
		$f = rtrim($f, ',');
		$s = DB::connect()->prepare("UPDATE $t SET $f WHERE $w");
		foreach ($d as $k => $v) $s->bindValue(":$k", $v);
		return $s->execute();
	}

	static function del($t, $w, $l=1)
	{
		return DB::connect()->exec("DELETE FROM $t WHERE $w LIMIT $l");
	}
  
	static function show($t)
	{
		return DB::connect()->exec("SHOW TABLES");
	}
  
	static function repair($t)
	{
		return DB::connect()->exec("REPAIR TABLE $t");
	}
  
	static function drop($t)
	{
		return DB::connect()->exec("DROP TABLE IF EXISTS $t");
	}
}
