<?php

/* Database Config file */

return array (
  'type' => 'pdo_mysql',
  'schema' => 'dbal_gateway',
  'user' => 'root',
  'password' => '',
  'host' => 'localhost',
  'port' => 3306,
  'migration_table' => 'migrations_data',
  'socket' => false,
  'path' => NULL,
  'memory' => NULL,
  'charset' => false,
);


/* End of Config File */
