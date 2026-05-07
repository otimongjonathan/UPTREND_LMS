<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    public function index()
    {
        return view('repayments.index');
    }
}
