<?php

namespace App\Controllers;
use App\Models\ClientModel;

class Home extends BaseController
{
    public function index(): string
    {
        $user = auth()->user();
        $client = (new ClientModel())->where('user_id', $user->id)->first();
        $client["created_at"] = gmdate("d-m-Y", $client["created_at"]);
        return view('welcome_message', ["client" => $client]);
    }
}
