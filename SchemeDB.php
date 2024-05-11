<?php class MariaDB {
    public static function table($tableName) {
        return new Fields($tableName);
    }
}

class Fields {
    private $tableName;
    private $fields = [];

    public function __construct($tableName) {
        $this->tableName = $tableName;
    }

    public function increments($fieldName, $length=255) {
        $this->fields[] = "$fieldName INT($length) AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function char($fieldName, $length=255) {
        $this->fields[] = "$fieldName VARCHAR($length)";
        return $this;
    }

    public function integer($fieldName, $length=255) {
        $this->fields[] = "$fieldName INT($length)";
        return $this;
    }

    public function dateTime($fieldName) {
        $this->fields[] = "$fieldName DATETIME";
        return $this;
    }

    public function timeStamp($fieldName) {
        $this->fields[] = "$fieldName TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    public function text($fieldName) {
        $this->fields[] = "$fieldName TEXT";
        return $this;
    }

    public function binary($fieldName) {
        $this->fields[] = "$fieldName BLOB";
        return $this;
    }

    public function unique($fieldName) {
        $this->fields[] = "$fieldName UNIQUE";
        return $this;
    }

    public function foreign($fieldName) {
        $this->fields[] = "$fieldName FOREIGN KEY";
        return $this;
    }

    public function create($engine='InnoDB') {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (";
        $sql .= implode(", ", $this->fields);
        $sql .= ") ENGINE=$engine;";

        DB::exec($sql);

        echo "SQL Query: $sql";
    }

    public function replace($engine='InnoDB') {
        $sql = "CREATE OR REPLACE TABLE $this->tableName (";
        $sql .= implode(", ", $this->fields);
        $sql .= ") ENGINE=$engine CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";

        DB::exec($sql);

        echo "SQL Query: $sql";
    }
}

/** Contoh penggunaan
$fields = MariaDB::table("products");
$fields->increments("productId");
$fields->char("name", 30);
$fields->integer("price", 255);
$fields->dateTime("created_at");
$fields->create();
**/
?>
