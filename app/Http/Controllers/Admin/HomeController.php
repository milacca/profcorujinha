<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $usuarios = User::count();
        $totalEbooks = Product::count();
        $categories = ProductCategory::withCount('products')->get();

        $user = Auth::user();

        return view('home', compact('usuarios', 'totalEbooks', 'categories', 'user'));
    }
}
