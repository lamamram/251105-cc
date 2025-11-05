<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ["name", "phone_number", "created_at", "user_id"];
    
    // Définir la relation avec le modèle User
    public function user()
    {
        // Supposant que vous avez un modèle User
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }
}
