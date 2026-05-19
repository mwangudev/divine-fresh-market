<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard(){

        //Eager load
        $sales = Sale::with('items.product')->latest()->paginate(20);

        return view('dashboard', compact('sales'));

        }





 }

