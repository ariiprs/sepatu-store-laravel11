<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckBookingRequest;
use App\Http\Requests\StoreCustomerDataRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\ProductTransaction;
use App\Models\Shoe;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function saveOrder(StoreOrderRequest $request, Shoe $shoe)
    {
        $validated = $request->validated();

        $validated['shoe_id'] = $shoe->id;

        $this->orderService->beginOrder($validated);
        // setelah sudah disimpan ke session, langkah selanjutnya adalah redirect ke route di bawah ini

        return redirect()->route('front.booking', $shoe->slug);
    }

    /* ini menampilkan form input datanya */
    public function booking()
    {
        $data = $this->orderService->getOrderDetails();
        // dd($data);
        return view('order.order', $data);
    }

    public function customerData()
    {
        $data = $this->orderService->getOrderDetails();
        // dd($data);
        return view('order.customer_data', $data);
    }
    /* ini menampilkan form input datanya */


    /* ini untuk menyimpan data  */
    public function saveCustomerData(StoreCustomerDataRequest $request)
    {
        $validated = $request->validated();
        $this->orderService->updateCustomerData($validated);

        return redirect()->route('front.payment');
    }
    /* ini untuk menyimpan data  */


    /* ini untuk menampilkan halaman payment */
    public function payment()
    {
        $data = $this->orderService->getOrderDetails();
        // dd($data);
        return view('order.payment', $data);
    }
    /* ini untuk menampilkan halaman payment */


    /* ini untuk melakukan validasi payment dan juga menyimpan data payment */
    public function paymentConfirm(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);

        $productTransactionId = $this->orderService->paymentConfirm($validated);

        if($productTransactionId){
            return redirect()->route('front.order_finished', $productTransactionId);
        }

        return redirect()->route('front.index')->withErrors(['Error' => 'Payment failed, Please try again later']);
    }

    public function orderFinished(ProductTransaction $productTransaction)
    {
        return view('order.order_finished', compact('productTransaction'));
    }

    public function checkBooking()
    {
        return view ('order.my_order');
    }

    public function checkBookingDetails(StoreCheckBookingRequest $request)
    {
        $validated = $request->validated();

        $orderDetails = $this->orderService->getMyOrderDetails($validated);

        if($orderDetails){
            return view('order.my_order_details', compact('orderDetails'));
        }

        return redirect()->route('front.check_booking')->withErrors(['error' => 'Transaction not found']);
    }

}
