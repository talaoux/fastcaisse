<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display the customers page.
     */
    public function index()
    {
        return view('admin.customers');
    }
}