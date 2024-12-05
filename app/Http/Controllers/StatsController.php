<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Transaction;
use App\Models\TransactionTransfer;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function total(){
        return view(
            'stats.total'
        );
    }

}

