<?php

namespace App\Repositories;

use App\Models\PromoCode;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;

class PromoCodeRepository implements PromoCodeRepositoryInterface
{
    public function getAllPromoCode()
    {
        //fungsinya adalah untuk mengambil semua data promo code yang ada di database
        return PromoCode::latest()->get();
    }

    public function findByCode(string $code)
    {
        return PromoCode::where('code', $code)->first();
    }
}