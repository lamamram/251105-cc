<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestDbTables extends BaseCommand
{
    protected $group       = 'Test';
    protected $name        = 'test:tables';
    protected $description = 'display the db tables';

    public function run(array $params)
    {
      $db = db_connect();
      $tables = $db->listTables();
      foreach ($params as $param) {
        if (in_array($param, $tables)) {
          CLI::write(CLI::color($param, 'green'));
          CLI::write(CLI::color(var_export($db->getFieldData('accounts'), true), 'green'));
        } else {
          CLI::write(CLI::color($param, 'red'));
          throw new \RuntimeException("Table $param does not exist");
        }
      }
    }
}