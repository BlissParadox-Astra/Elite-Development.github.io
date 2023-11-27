<?php

namespace App\Managers;

use App\Models\CanceledOrder;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class CanceledOrderManager
{
    public function cancelOrder(array $canceledOrderData)
    {
        $canceledOrderData['user_id'] = Auth::id();
        $canceledOrderData['canceled_date'] = $canceledOrderData['canceled_date'] ?? now();

        $transaction = Transaction::findOrFail($canceledOrderData['transaction_id']);
        $product = $transaction->transactedProduct;
        if ($transaction['quantity'] < $canceledOrderData['quantity']) {
            throw new \Exception('Cannot cancel more products than purchased.');
        } elseif ($transaction['quantity'] == $canceledOrderData['quantity']) {
            $canceledOrderData['total'] = $transaction['total'];

            if ($canceledOrderData['action_taken'] === 'Yes') {
                $product->incrementStockOnHand($canceledOrderData['quantity']);
            }

            CanceledOrder::create($canceledOrderData);
            $transaction->delete();
        } elseif ($transaction['quantity'] > $canceledOrderData['quantity']) {
            $productPrice = $transaction->transactedProduct->price;
            $canceledTotal = $productPrice * $canceledOrderData['quantity'];

            $canceledOrderData['total'] = $canceledTotal;
            $transaction['quantity'] -= $canceledOrderData['quantity'];

            if ($canceledOrderData['action_taken'] === 'Yes') {
                $product->incrementStockOnHand($canceledOrderData['quantity']);
            }

            $transaction['total'] -= $canceledTotal;
            CanceledOrder::create($canceledOrderData);
            $transaction->save();
        } else {
            throw new \Exception('Something went wrong.');
        }
    }

    public function getAllCanceledOrders($page, $itemsPerPage, $fromDate = null, $toDate = null, $filterType = null)
    {
        $query = CanceledOrder::with(['canceledTransaction.transactedProduct', 'user']);

        if ($filterType) {
            switch ($filterType) {
                case 'Day':
                    $query->whereDate('canceled_orders.canceled_date', now()->toDateString());
                    break;
                case 'Week':
                    $query->whereBetween('canceled_orders.canceled_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'Month':
                    $query->whereYear('canceled_orders.canceled_date', now()->year)
                        ->whereMonth('canceled_orders.canceled_date', now()->month);
                    break;
                case 'Year':
                    $query->whereYear('canceled_orders.canceled_date', now()->year);
                    break;
                case 'Customize':
                    $query->whereBetween('canceled_orders.canceled_date', ["{$fromDate} 00:00:00", "{$toDate} 23:59:59"]);
                    break;
                default:
                    $query->whereNull('deleted_at');
                    break;
            }
        } else {
            $query->whereNull('deleted_at');
        }

        return $query->paginate($itemsPerPage, ['*'], 'page', $page);
    }
}
