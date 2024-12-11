<?php

namespace App\Repositories;

use App\Models\ProductTransaction;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Session;

class OrderRepository implements OrderRepositoryInterface {

    public function createTransaction(array $data)
    {
        return ProductTransaction::create($data);
        //ini merupakan salah satu penerapan elequent ORM
    }

    public function findByTrxIdAndPhoneNumber($bookingTrx, $phoneNumber)
    {
        return ProductTransaction::where('booking_trx_id', $bookingTrx)
            ->where('phone', $phoneNumber)
            ->first(); //karna hanya ada 1 record data
    }

    public function saveToSession(array $data)  //ini hanya menerima data berupa array
    {
        Session::put('orderData', $data);
    }

    public function getOrderDataFromSession()
    //method ini digunakan untuk mengambil data dari session
    //jadi data yang tidak langsung dikirim ke database, jadi disimpan dulu di session
    {
        return Session('orderData', []);
    }

    public function updateSessionData(array $data)
    {
        $orderData = session('orderData', []);
        $orderData = array_merge($orderData, $data);
        session(['orderData' => $orderData ]);
    }

    public function clearSession()
    {
        Session::forget('orderData');
    }

}