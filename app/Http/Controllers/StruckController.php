<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerTransaction;

class StruckController extends Controller
{
    //

    public function show($id){
        $customerTransaction =  CustomerTransaction::with(["farmer_transaction.fruit_commodity.farmer", "farmer_transaction.fruit_commodity.fruit", "customer"])->find($id);
        return view('struck', [
            "struck"=>$customerTransaction
        ]);
    }
}
