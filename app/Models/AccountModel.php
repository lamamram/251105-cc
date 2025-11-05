<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Account;

class AccountModel extends Model
{
    protected $table            = 'accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Account::class;
    
    protected $allowedFields    = ['numero', 'balance', 'overdraft', 'client_id'];
    
    // Validation
    protected $validationRules = [
        'numero'    => 'required|is_unique[accounts.numero]',
        'balance'   => 'numeric',
        'overdraft' => 'numeric',
        'client_id' => 'required|numeric'
    ];
    
    protected $validationMessages = [
        'numero' => [
            'required' => 'Le numéro de compte est obligatoire',
            'is_unique' => 'Ce numéro de compte existe déjà'
        ],
        'client_id' => [
            'required' => 'L\'identifiant du client est obligatoire'
        ]
    ];
    
    /**
     * Définit la relation avec le modèle Client
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function client()
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }
}