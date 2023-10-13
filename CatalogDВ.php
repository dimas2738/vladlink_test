<?php

declare(strict_types=1);

namespace App;

class CatalogDВ
{
    private const dsn = 'mysql:host=localhost:3306;dbname=catalogDB';
    private const user = 'root';
    private const password = '';
    private $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO(self::dsn, self::user, self::password);
        $this->pdo->exec('SET NAMES "utf8mb4"');
    }

    public function add(array $item): bool
    {
        $item["r_key"] = $this->getMaxRightKey($item);
        $item["l_key"] = $item["r_key"];
        $this->updateBeforeInsert($item);
        ++$item["r_key"];
        $query = 'INSERT INTO catalog (id, name, alias, url, parent_id, depth, l_key, r_key)
                  VALUES (:id, :name, :alias, :url, :parent_id, :depth, :l_key, :r_key)';
        $pdo = $this->pdo->prepare($query);
        return $pdo->execute(array(':id' => $item["id"],
            ':name' => $item["name"],
            ':alias' => $item["alias"],
            ':url' => $item["url"],
            ':parent_id' => $item["parent_id"],
            ':depth' => $item["depth"],
            ':l_key' => $item["l_key"],
            ':r_key' => $item["r_key"]
        ));
    }

    private function getMaxRightKey(array $item): int
    {
        if ($item["parent_id"] > 0) {
            $query = 'SELECT r_key FROM catalog WHERE id = ' . $item["parent_id"];
        } else {
            $query = 'SELECT MAX(r_key) FROM catalog';
        }
        $statement = $this->pdo->query($query);
        foreach ($statement as $array) {
            $result = is_null($array[0]) ? 1 : $array[0];
            $result = array_key_exists('MAX(r_key)', $array) && $array[0] ? ++$result : $result;
        }
        $statement->closeCursor();
        return (int)$result;
    }

    private function updateBeforeInsert(array $item)
    {
        $query = 'UPDATE catalog SET r_key = r_key + 2, l_key = IF(l_key > :r_key, l_key + 2, l_key) WHERE r_key >= :r_key ';
        $pdo = $this->pdo->prepare($query);
        $pdo->execute(array(':r_key' => $item["r_key"]));
    }

    public function getList(): array
    {
        $query = 'SELECT name, url, depth,parent_id FROM catalog ORDER BY l_key';
        $statement = $this->pdo->query($query);
        $result = [];
        foreach ($statement as $array) {
            array_push($result, array(
                    "name" => $array["name"],
                    "url" => $array["url"],
                    "depth" => $array["depth"],
                    "parent_id" => $array["parent_id"]
                )
            );
        }
        return $result;
    }
}