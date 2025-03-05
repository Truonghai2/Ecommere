<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistorySearchController extends Controller{

    protected $product;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function searchProduct(Request $request){

    }

}
