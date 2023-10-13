<?php

spl_autoload_register(function ($class) {
    $file = substr(str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php', 4);
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});

$action = new \App\CatalogAction(
    new \App\CatalogDВ()
);

$menu = $action->getMenu();

foreach ($menu as $item) {
?>
    <div style="margin-left:<?= $item['depth'] ?>em"><?= $item['name'] ?></div>
<?php
}
?>

