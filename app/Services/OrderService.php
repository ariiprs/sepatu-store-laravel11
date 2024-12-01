<?php

namespace App\Services;

use App\Models\ProductTransaction;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;
use App\Repositories\Contracts\ShoeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



/*
    Orderservice class ini berfungsi untuk melakukan order

    1. CategoryRepositoryInterface : getAllCategories
    2. ShoeRepositoryInterface : getPopularShoes, searchByName, getAllNewShoes,find, getPrice
    berarti dia ingin ambil semua kategori, sepatu popular, semua sepatu, harga
    3. OrderRepositoryInterface : saveToSession, getOrderDataFromSession, createTransaction,
    updateSessionData , findByTrxIdAndPhoneNumber
    4. PromoCodeRepositoryInterface : findByCode, getAllPromoCode
    */


class OrderService
{
    protected $categoryRepository;
    protected $shoeRepository;
    protected $orderRepository;
    protected $promoCodeRepository;

    //construct berguna untuk dependency injection (akan berjalan secara otomatis saat aplikasi berjalan)
    public function __construct(
        ShoeRepositoryInterface $shoeRepository,
        CategoryRepositoryInterface $categoryRepository,
        OrderRepositoryInterface $orderRepository,
        PromoCodeRepositoryInterface $promoCodeRepository
    )
    {
        $this->shoeRepository = $shoeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
        $this->promoCodeRepository = $promoCodeRepository;
    }


    public function beginOrder(array $data) //memulai order / tahapan pertama order
    {
        /* ini dipilih karna pada user interface saat pertama kali order
        itu harus memilih size pada salah satu sepatu yang diinginkan.
        */
        $orderData = [
            'shoe_size' => $data['shoe_size'],
            'size_id' => $data['size_id'],
            'shoe_id' => $data['shoe_id'],
        ];

        //menyimpan data ke session
        $this->orderRepository->saveToSession($orderData);
    }

    public function getOderDetails()
    {
        //mengambil data dari session yang sudah dibuat dari function beginOrder
        $orderData = $this->orderRepository->getOrderDataFromSession();

        //mengambil data sepatu menggunakan function shoeRepository yaiut find .
        //find berdasarkan variabel orderData['shoe_id']
        $shoe = $this->shoeRepository->find($orderData['shoe_id']);

        $quantity = isset($orderData['quantity']) ? $orderData['quantity'] : 1;
        $subTotalAmount = $shoe->price * $quantity;

        $taxRate = 0.11;
        $totalTax = $subTotalAmount * $taxRate;

        $grandTotalAmount = $subTotalAmount + $totalTax;

        $orderData['sub_total_amount'] = $subTotalAmount;
        $orderData['total_tax'] = $totalTax;
        $orderData['grand_total_amount'] = $grandTotalAmount;

        return compact('orderData', 'shoe' );
    }

    public function applyPromoCode(string $code, int $subTotalAmount)
    {
        $promo = $this->promoCodeRepository->findByCode($code);
        // $promo = GGGAMING
        //GGGAMING discount_amount = 100000

        if($promo){
            $discount = $promo->discount_amount;
            $grandTotalAmount = $subTotalAmount - $discount;
            $promoCodeId = $promo->id;
            return ['discount' => $discount, 'grandTotalAmount' => $grandTotalAmount, 'promoCodeId' => $promoCodeId];
        }

        return ['error' => 'Kode Promo Tidak Tersedia'];

    }

    public function saveBookingTransaction(array $data)
    {
        $this->orderRepository->saveToSession($data);
    }

    public function updateCustomerData(array $data)
    {
        $this->orderRepository->updateSessionData($data);
    }


    //fungsi ini berfungsi untuk menyimpan semua data pda kolom product_transaction ke database
    public function paymentConfirm (array $validated)
    {
        $orderData = $this->orderRepository->getOrderDataFromSession();

        $productTransactionId = null;

        /* menggunakan try and catch supaya ketika gagal akan langsung menampilkan pesan.
        DB::transaction digunakan supaya tidak terjadi kecacatan pada database.

        method ini membuat semua data yang harus dimasukan adalah required.
        jadi ketika salah satu data tidak diisi, maka akan muncul pesan error dan tidak dimasukan ke DB.

        */

        try { //ini merupakan closure based transaction
            DB::transaction(function () use ($validated, &$productTransactionId, $orderData) {
                if(isset($validated['proof'])){
                    $proofPath = $validated['proof']->store('proofs', 'public');
                    //yang disimpan hanya nama file nya saja, kalo fotonya di simpan di database
                    $validated['proof'] = $proofPath;
                }

                $validated['name'] = $orderData['name'];
                $validated['email'] = $orderData['email'];
                $validated['phone'] = $orderData['phone'];
                $validated['address'] = $orderData['address'];
                $validated['post_code'] = $orderData['post_code'];
                $validated['city'] = $orderData['city'];
                $validated['quantity'] = $orderData['quantity'];
                $validated['sub_total_amount'] = $orderData['sub_total_amount'];
                $validated['grand_total_amount'] = $orderData['grand_total_amount'];
                $validated['discount_amount'] = $orderData['discount_amount'];
                $validated['promo_code_id'] = $orderData['promo_code_id'];
                $validated['shoe_id'] = $orderData['shoe_id'];
                $validated['shoe_size'] = $orderData['shoe_size'];
                $validated['is_paid'] = false;
                //ini disetting false karna nanti kita cek dulu beneran udah bayar atau belum
                $validated['booking_trx_id'] = ProductTransaction::generateUniqueTrxId();

                $newTransaction = $this->orderRepository->createTransaction($validated);

                $productTransactionId = $newTransaction->id;
            });
        } catch (\Exception $e) {
            Log::error( 'Error in payment confirmation: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return null;
        }

        return $productTransactionId;
    }

}