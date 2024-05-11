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

    public function text($fieldName) {
        $this->fields[] = "$fieldName TEXT";
        return $this;
    }

    public function binary($fieldName) {
        $this->fields[] = "$fieldName BLOB";
        return $this;
    }

    public function create() {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (";
        $sql .= implode(", ", $this->fields);
        $sql .= ");";

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
