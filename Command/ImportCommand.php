<?php
declare(strict_types=1);
namespace App\Command;

use App\CatalogAction;

class ImportCommand
{
    protected $catalogAction;

    public function __construct(CatalogAction $catalogAction)
    {
        $this->catalogAction = $catalogAction;
    }


    public function runImport(array $argv)
    {
    try {
        $this->catalogAction->import('./categories.json');
        echo "Done\n";
        die();

    } catch (\Exception $e) {
        echo($e->getMessage());
        }
    }
}