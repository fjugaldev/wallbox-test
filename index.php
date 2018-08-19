<?php

require_once realpath(dirname(__FILE__) . '/vendor') . DIRECTORY_SEPARATOR . 'autoload.php';

use WallboxApp\PhpTest\Controller\ApiController;

define('WALLBOX_APP_DATA_FOLDER', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'resources/data'));

// Front page for the request.
$api = new ApiController();
$api->getUsersAction($_REQUEST);
