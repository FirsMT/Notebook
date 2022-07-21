
<?php
spl_autoload_register('autoloader');
function autoloader(string $name) {

    if (file_exists('../../models/'.$name.'.php')){
        require_once '../../models/'.$name.'.php';
    }
}

require('../../vendor/autoload.php');
$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'] . '/models']);
header('Content-Type: application/json');
echo $openapi->toJson();
