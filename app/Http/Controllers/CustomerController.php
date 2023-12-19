<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class CustomerController extends Controller
{
    public function overviewCustomer(){
        $users = UserModel::get();
        return view('/customer/overview', compact('users'));
    }
}
