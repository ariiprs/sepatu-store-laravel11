<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService) // ini adalah dependency injection
    {
        $this->frontService = $frontService;
    }

    public function index()
    {
        $data = $this->frontService->getFrontPageData(); // ini menggunakan dependency injection
        return view('front.index', $data);
    }

    public function details(Shoe $shoe) //ini menggunakan konsep model binding
    {
        return view('front.details', compact('shoe'));
        //compact digunakan supaya data dari model Shoe dikirim ke shoe
    }

    public function category(Category $category)
    {
        return view('front.category', compact('category'));
    }

}
