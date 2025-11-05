<?php

namespace App\Models;

use CodeIgniter\Model;

class TestClientModel extends Model
{
    protected $table            = 'test_clients';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ["name", "phone_number", "created_at"];

}
