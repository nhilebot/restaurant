<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuchitietController extends Controller
{
    public function index(Request $request)
{
    $query = Menu::query();

    // nếu có nhập tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;

        $query->where('name', 'LIKE', '%' . $search . '%');
    }

    $menus = $query->get();

    return view('menu', compact('menus'));
}

    public function special()
    {
         $menus = Menu::where('category', 'special')->get();
        return view('special', compact('menus'));
    }

    public function salad()
    {
        $menus = Menu::where('category', 'salad')->get();
        return view('salad', compact('menus'));
    }

    public function desserts()
{
    // Nếu trong DB category là 'dessert'
    // Thay get() bằng paginate(số_món_1_trang)
    $menus = Menu::where('category', 'dessert')->get();
    return view('desserts', compact('menus'));
}

    public function drinks()
    {
        $menus = Menu::where('category', 'drink')->get();
        return view('drinks', compact('menus'));
    }

    public function seafood()
    {
        $menus = Menu::where('category', 'seafood')->get();
        return view('seafood', compact('menus'));
    }

    public function vietnamese()
    {
        $menus = Menu::where('category', 'vietnamese')->get();
        return view('vietnamese', compact('menus'));
    }

    public function showDetail($id)
    {
        $menu = Menu::findOrFail($id);
        return view('detail', compact('menu')); // đổi nếu file view khác
        
    }
}