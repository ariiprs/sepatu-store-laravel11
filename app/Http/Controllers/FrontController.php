<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Category;
use App\Services\FrontService;
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

    public function allCategory()
    {
        $data = $this->frontService->getFrontPageData(); // ini menggunakan dependency injection
        return view('front.all_category', $data);
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $shoes = $this->frontService->searchShoes($keyword);

        return view('front.search', [
            'shoes' => $shoes,
            'keyword' => $keyword,
        ]);
    }

    public function searchCategory(Request $request)
    {
        $keyword = $request->input('keyword');

        $categories = $this->frontService->searchCategories($keyword);

        return view('front.search_category', [
            'categories' => $categories,
            'keyword' => $keyword,
        ]);
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
