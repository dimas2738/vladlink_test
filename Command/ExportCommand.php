<?php
declare(strict_types=1);
namespace App\Command;

use App\CatalogAction;

class ExportCommand
{
    protected $catalogAction;

    public function __construct(CatalogAction $catalogAction)
    {
        $this->catalogAction = $catalogAction;
    }

    public function runExport(array $argv)
    {
        echo("\nType: \n [1] Full \n [2] Short\n");
        $input = (int)readline();
        if(in_array($input,[1,2]))
        {
            try {
                $this->catalogAction->export($input);
            } catch (\Exception $e) {
                echo($e->getMessage());
            }
        }
        else
        {
            echo("\n[Only '1' or '2' must be chosen]");
            $this->runExport($argv);
        }
    }

}