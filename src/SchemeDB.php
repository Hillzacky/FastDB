<?php
namespace 'Hillzacky/MariaDB';

class MariaDB
{
    private $table;
    private $fields = [];

    public static function execute($sql) {
        DB::exec($sql);
    }

    public static function drop($tableName) {
        $sql = "DROP TABLE IF EXISTS `$tableName`";
    }

    public static function table($tableName) {
        $instance = new self();
        $instance->table = $tableName;
        return $instance;
    }

    public function increments($fieldName, $length=255) {
        $this->fields[] = "$fieldName INT($length) AUTO_INCREMENT";
        return $this;
    }

    public function char($fieldName, $length=255) {
        $this->fields[] = "$fieldName CHAR($length)";
        return $this;
    }

    public function string($fieldName, $length=255) {
        $this->fields[] = "$fieldName VARCHAR($length)";
        return $this;
    }

    public function integer($fieldName, $length=255) {
        $this->fields[] = "$fieldName INT($length)";
        return $this;
    }

    public function BigInteger($column)
    {
        return 'bigint';
    }

    public function MediumInteger($column)
    {
        return 'mediumint';
    }

    public function TinyInteger($column)
    {
        return 'tinyint';
    }

    public function SmallInteger($column)
    {
        return 'smallint';
    }

    public function Float($column)
    {
        if ($column->precision) {
            return "float({$column->precision})";
        }

        return 'float';
    }

    public function Double($column)
    {
        return 'double';
    }

    public function Decimal($column)
    {
        return "decimal({$column->total}, {$column->places})";
    }

    public function dateTime($fieldName) {
        $this->fields[] = "$fieldName DATETIME";
        return $this;
    }

    public function timeStamps($fieldName) {
        $this->fields[] = "$fieldName TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    public function tinyText($column)
    {
        return 'tinytext';
    }

    public function text($fieldName) {
        $this->fields[] = "$fieldName TEXT";
        return $this;
    }

    public function mediumText($column)
    {
        return 'mediumtext';
    }

    public function longText($column)
    {
        return 'longtext';
    }

    public function binary($fieldName) {
        $this->fields[] = "$fieldName BLOB";
        return $this;
    }

    
    public function boolean($fieldName)
    {
        return 'tinyint(1)';
    }

    public function enum($column)
    {
        return sprintf('enum(%s)', $this->quoteString($column->allowed));
    }

    public function Set($column)
    {
        return sprintf('set(%s)', $this->quoteString($column->allowed));
    }

    public function Json($column)
    {
        return 'json';
    }

    public function Jsonb($column)
    {
        return 'json';
    }

    public function DateTime($column)
    {
        $current = $column->precision ? "CURRENT_TIMESTAMP($column->precision)" : 'CURRENT_TIMESTAMP';

        if ($column->useCurrent) {
            $column->default(new Expression($current));
        }

        if ($column->useCurrentOnUpdate) {
            $column->onUpdate(new Expression($current));
        }

        return $column->precision ? "datetime($column->precision)" : 'datetime';
    }

    public function DateTimeTz($column)
    {
        return $this->DateTime($column);
    }

    public function Time($column)
    {
        return $column->precision ? "time($column->precision)" : 'time';
    }

    public function TimeTz($column)
    {
        return $this->Time($column);
    }

    public function Timestamp($column)
    {
        $current = $column->precision ? "CURRENT_TIMESTAMP($column->precision)" : 'CURRENT_TIMESTAMP';

        if ($column->useCurrent) {
            $column->default(new Expression($current));
        }

        if ($column->useCurrentOnUpdate) {
            $column->onUpdate(new Expression($current));
        }

        return $column->precision ? "timestamp($column->precision)" : 'timestamp';
    }

    public function TimestampTz($column)
    {
        return $this->Timestamp($column);
    }

    public function year($fieldName)
    {
        return 'year';
    }

    public function uuid($fieldName)
    {
        return 'char(36)';
    }

    public function ipAddress($fieldName)
    {
        return 'varchar(45)';
    }

    public function macAddress($fieldName)
    {
        return 'varchar(17)';
    }

    public function unique($fieldName) {
        $this->fields[count($this->fields) - 1] .= " UNIQUE";
        return $this;
    }

    public function foreign($fieldName) {
        $this->fields[count($this->fields) - 1] .= " FOREIGN KEY";
        return $this;
    }

    public function nullable() {
        $this->fields[count($this->fields) - 1] .= " NULL";
        return $this;
    }

    public function default($val) {
        $this->fields[count($this->fields) - 1] .= " DEFAULT $val";
        return $this;
    }

    public function primary() {
        $this->fields[count($this->fields) - 1] .= " PRIMARY KEY";
        return $this;
    }

    public function create($engine='InnoDB') {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (";
        $sql .= implode(", ", $this->fields);
        $sql .= ") ENGINE=$engine;";

        self::execute($sql);
    }

    public function replace($engine='InnoDB') {
        $sql = "CREATE OR REPLACE TABLE $this->tableName (";
        $sql .= implode(", ", $this->fields);
        $sql .= ") ENGINE=$engine DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";

        self::execute($sql);
    }
}

/** Contoh penggunaan
$fields = MariaDB::table("products");
$fields->increments("productId")->primary();
$fields->char("name", 30)->nullable();
$fields->integer("price", 255)->default(1000);
$fields->dateTime("created_at");
$fields->create();
**/
?>