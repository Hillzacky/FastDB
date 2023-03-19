# FastDB
Fast Code Helper for Integration Database PHP


### Configuration
Example .env.example
```
DB_DRIVER=mysql
DB_HOST=localhost
DB_NAME=information_schema
DB_USERNAME=root
DB_PASSWORD=
```

### Use
Show Data
```php
DB::get("select query", ["key" => "value"], PDO::FETCH_ASSOC);
```

Insert Data
```php
DB::add("table_name", ["field_name" => "value"]);
```

Update Data
```php
DB::set("table_name", ["field_name" => "value"], "id=1");
```

Delete Data
```php
DB::del("table_name", "id=1");
```

Show for Audit
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
