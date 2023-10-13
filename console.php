<?php
spl_autoload_register(function ($class) {
    $file = substr(str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php', 4);
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});



$commands = ['import', 'export'];

$commandName = $argv[1] ?? null;
if ($commandName === null) {
    echo "Wrong command, please input 'php console.php export' or 'php console.php import'";
    return;
}
unset($argv[0]);
unset($argv[1]);
foreach ($commands as $command) {
    if ($commandName === 'import') {
        $command = new App\Command\ImportCommand(
            new \App\CatalogAction(
                new \App\CatalogDВ()
            )
        );
        $command->runImport($argv);
    }
    if ($commandName === 'export') {
        $command = new App\Command\ExportCommand(
            new \App\CatalogAction(
                new \App\CatalogDВ()
            )
        );
        $command->runExport($argv);
    }
}
