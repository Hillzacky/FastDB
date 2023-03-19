# FastDB
Fast Code Helper for Integration Database PHP

Show Data
```php
DB::get("select query", ["key" => "value"], PDO::FETCH_ASSOC);
```

Insert Data
```php
DB::add("table_name", ["field_name" => "value"], PDO::FETCH_ASSOC);
```

Update Data
```php
DB::set("table_name", ["field_name" => "value"], "id=1");
```

Delete Data
```php
DB::del("table_name", "id=1");
```

Show
```php
DB::show("tables");
```
Repair
```php
DB::repair("table_name");
```
Drop
```php
DB::drop("table_name");
```
