<?php
declare(strict_types=1);

namespace App;

use App\CatalogDВ;

class CatalogAction
{
    private $catalogDВ;
    private $fileAction;

    public function __construct(CatalogDВ $catalogDВ, FileAction $fileAction = null)
    {
        $this->catalogDВ = $catalogDВ;
        $this->fileAction = $fileAction;
    }

    public function import(string $path)
    {
        $source = file_get_contents($path);

        if (!$source) {
            throw new \Exception('File not found');
        }

        $content = json_decode($source, true);
        $this->saveCatalog($content);
    }

    public function writeArray(array $list, string $mode = '')
    {
        foreach ($list as $item) {
            $formattedArray[] = $this->getFormattedArray($item, $mode);
        }
        $fileName = "./type_a.txt";

        if ($mode == 'short') {
            $fileName = "./type_b.txt";
        }

        $stream = fopen($fileName, "w");

        foreach ($formattedArray as $item) {
            if (!empty($item)){
                $string = implode(' ', $item) . "\n";
                fwrite($stream, $string);
            }
        }
        fclose($stream);

        die("\nDone!\n");
    }

    private function saveCatalog(array $items, string $url = '', $parent_id = 0, $depth = 1)
    {

        foreach ($items as $item) {
            $row = [
                "id" => $item["id"],
                "name" => $item["name"],
                "alias" => $item["alias"],
                "url" => $url . '/' . $item["alias"],
                "parent_id" => $parent_id,
                "depth" => $depth
            ];
            $this->catalogDВ->add($row);
            $currentDepth = $depth;

            if (array_key_exists("childrens", $item)) {
                $this->saveCatalog($item["childrens"],
                    $url . '/' . $item["alias"],
                    $item["id"],
                    ++$depth);
            }

            $depth = $currentDepth;
        }

    }

    public function export(int $mode)
    {
        $list = $this->catalogDВ->getList();
        if ($mode == 2) {
            $this->writeArray($list, 'short');
        } else {
            $this->writeArray($list);
        }

    }

    public function getFormattedArray(array $item, string $mode)
    {
        $padding = str_repeat("\t", (int)$item["depth"]);
        $item["name"] = substr_replace($item["name"], $padding, 0, 0);
        if ($mode == 'short') {
            if (!in_array($item["depth"],[1,2])) {
                unset($item);
            }
            if (isset($item)){
            unset($item["url"]);
            unset($item["parent_id"]);
            unset($item["depth"]);
            }
        }
        if ($mode == 'web') {
                unset($item["url"]);
                unset($item["parent_id"]);
            }
        if ($mode !== 'web' && $mode !== 'short') {
            if (isset($item)){
                unset($item["parent_id"]);
                unset($item["depth"]);
            }
        }

        return isset($item)?$item:null;
    }

    public function getMenu(): array
    {
        $list = $this->catalogDВ->getList();
        $menu = [];

        foreach ($list as $item) {
            if ((in_array($item["depth"],[1,2]))){
                $menu[] = $this->getFormattedArray($item, 'web');
            }
        }
        return $menu;
    }
}