<?php

defined('SITE_PATH') || exit('Forbidden');

$sqlFilePath = APPS_PATH.'/project/Appinfo/uninstall.sql';
D()->executeSqlFile($sqlFilePath);
