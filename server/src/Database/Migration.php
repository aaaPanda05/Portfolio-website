<?php
namespace App\Database;

use App\Data\Database;
use PDO;
use Exception;

class Migration {
    /**
     * Create a table from the given name and column definitions
     *
     * @param string $table
     * @param array $columns Array of columns like:
     *   [
     *     ['name'=>'id','type'=>'int','required'=>true,'unique'=>true,'primary'=>true,'autoIncrement'=>true],
     *     ['name'=>'name','type'=>'string','required'=>true]
     *   ]
     */
    public static function createTable(string $table, array $columns) {
        $pdo = Database::getConnection();

        $cols = [];
        $primaryKeys = [];
        $autoIncrementSet = false; // track auto increment

        foreach ($columns as $options) {
            $colName = $options['name'];
            $col = "`$colName`";

            // Type mapping
            switch (strtolower($options['type'])) {
                case 'int': $col .= " INT"; break;
                case 'string': $len = $options['length'] ?? 255; $col .= " VARCHAR($len)"; break;
                case 'text': $col .= " TEXT"; break;
                case 'bool': $col .= " TINYINT(1)"; break;
                case 'date': $col .= " TIMESTAMP"; break;
                default: throw new Exception("Unsupported type: " . $options['type']);
            }

            // Constraints
            if (!empty($options['autoIncrement'])) {
                if ($autoIncrementSet) {
                    throw new Exception("Only one autoIncrement column allowed per table");
                }
                $col .= " AUTO_INCREMENT";
                $autoIncrementSet = true;

                // ensure it's part of primary key
                if (empty($options['primary'])) {
                    $options['primary'] = true;
                }
            }

            if (!empty($options['required'])) $col .= " NOT NULL";
            if (!empty($options['unique'])) $col .= " UNIQUE";
            if (isset($options['default'])) {
                $default = is_string($options['default']) ? "'{$options['default']}'" : $options['default'];
                $col .= " DEFAULT $default";
            }

            $cols[] = $col;

            if (!empty($options['primary'])) {
                $primaryKeys[] = "`$colName`";
            }
        }

        if (!empty($primaryKeys)) {
            $cols[] = "PRIMARY KEY (" . implode(", ", $primaryKeys) . ")";
        }

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (" . implode(", ", $cols) . ")";
        $pdo->exec($sql);
    }

}
