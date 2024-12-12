<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ShoeRepositoryInterface;

/*
    Frontservice class ini berfungsi untuk menampilkan data ke bagian frontend
    1. CategoryRepositoryInterface : getAllCategories
    2. ShoeRepositoryInterface : getPopularShoes, searchByName, getAllNewShoes,find, getPrice

    berarti dia ingin ambil semua kategori, sepatu popular, semua sepatu, harga

    */

class FrontService
{
    protected $categoryRepository;
    protected $shoeRepository;

    public function __construct(ShoeRepositoryInterface $shoeRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->shoeRepository = $shoeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function searchShoes(string $keyword)
    {
        return $this->shoeRepository->searchByName($keyword);
    }

    public function searchCategories(string $keyword)
    {
        return $this->categoryRepository->searchByName($keyword);
    }

    public function getFrontPageData()
    {
        $categories = $this->categoryRepository->getAllCategories();
        $popularShoes = $this->shoeRepository->getPopularShoes(4);
        $newShoes = $this->shoeRepository->getAllNewShoes();

        return compact('categories', 'popularShoes', 'newShoes');
    }

}