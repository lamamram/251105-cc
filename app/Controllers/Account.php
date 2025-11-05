<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AccountModel;
use App\Models\ClientModel;

class Account extends Controller
{
    /**
     * Affiche les comptes du client connecté
     *
     * @return string
     */
    public function index()
    {
        $user = auth()->user();
        $client = (new ClientModel())->where('user_id', $user->id)->first();
        $accounts = (new AccountModel())->where('client_id', $client['id'])->findAll();
        
        // Passer les données à la vue
        $data = [
            'accounts' => $accounts,
            'title' => 'Mes comptes'
        ];
        
        // Rendre la vue
        return view('accounts', $data);
    }
}
