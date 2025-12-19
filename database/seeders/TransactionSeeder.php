<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Transaction;
use App\Models\Cart;
use App\Models\CarRental;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'WAITING FOR PAYMENT',
            'FAILED',
            'SUCCEED',
        ];

        $paymentMethods = [
            'TRANSFER BANK',
            'E-WALLET',
            'QRIS',
        ];

        $carts = Cart::all();

        foreach (range(1, 40) as $i) {
            $cart = $carts->random();
            $carRentalId = $cart->car->car_rental_id;

            Transaction::create([
                'car_rental_id' => $carRentalId,
                'user_id' => $cart->user_id,
                'cart_id' => $cart->id,
                'title' => 'Pembayaran Sewa Mobil',
                'description' => 'Transaksi sewa mobil melalui aplikasi.',
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
