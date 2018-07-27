<?php

//create a new Logger instance.
$logger = new \Monolog\Logger($config->get('log.name'));
$logger->pushHandler(new \Monolog\Handler\StreamHandler($config->get('log.log_file')));
