<?php
namespace App\Database;

use App\Data\Database;
use PDO;

class Migration {
    public static function createTable(string $table, array $columns) {
        $pdo = Database::getConnection();

        $cols = [];
        $primaryKeys = [];

        foreach ($columns as $name => $options) {
            $col = "`$name`";

            // Type mapping
            switch ($options['type']) {
                case 'int':
                    $col .= " INT";
                    break;
                case 'string':
                    $len = $options['length'] ?? 255;
                    $col .= " VARCHAR($len)";
                    break;
                case 'text':
                    $col .= " TEXT";
                    break;
                case 'bool':
                    $col .= " TINYINT(1)";
                    break;
                default:
                    throw new \Exception("Unsupported type: " . $options['type']);
            }

            // Constraints
            if (!empty($options['auto_increment'])) {
                $col .= " AUTO_INCREMENT";
            }
            if (!empty($options['not_null'])) {
                $col .= " NOT NULL";
            }
            if (isset($options['default'])) {
                $default = is_string($options['default']) ? "'{$options['default']}'" : $options['default'];
                $col .= " DEFAULT $default";
            }

            $cols[] = $col;

            // Primary key
            if (!empty($options['primary'])) {
                $primaryKeys[] = "`$name`";
            }
        }

        if (!empty($primaryKeys)) {
            $cols[] = "PRIMARY KEY (" . implode(", ", $primaryKeys) . ")";
        }

        $sql = "CREATE TABLE IF NOT EXISTS `$table` (" . implode(", ", $cols) . ")";
        $pdo->exec($sql);
    }
}
