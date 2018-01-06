<?php

namespace MarketPlex\Http\Controllers;

use Log;
use Auth;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

use MarketPlex\SaleTransaction as Sale;
use MarketPlex\ProductBill as Product;
use MarketPlex\ShippingBill as Shipping;
use MarketPlex\BillPayment as Payment;
use MarketPlex\Deposit;
use MarketPlex\Expense;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('sales-search-view')->withErrors([]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales-book-1')->withStores(Auth::user()->stores->pluck('id', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // to see custom validation messages: resources/lang/en/validation.php
        $this->validate($request,[
            'bill_id' => 'required|max:100',
            'client' => 'required|max:300',
            'product_bills.*.product_id' => 'present|required',
            'product_bills.*.product_quantity' => 'present|required|numeric|min:1',
            'shipping_bills.*.shipping_purpose' => 'sometimes|required|max:50',
            'shipping_bills.*.bill_amount' => 'sometimes|required|numeric|min:1.00',
            'shipping_bills.*.bill_quantity' => 'sometimes|required|numeric|min:1',
            'payments.*.trans_option' => [
                'sometimes',
                'present',
                Rule::in([ 'by_cash_hand2hand', 'by_cash_cheque_deposit', 'by_cash_electronic_trans', 'by_cheque_hand2hand' ]),
            ],
            'payments.*.paid_amount' => 'sometimes|required|numeric|min:1.00',
            'deposits.*.deposit_method' => [
                'sometimes',
                'present',
                Rule::in([ 'bank', 'vault' ]),
            ],
            'deposits.*.bank_ac_no' => 'sometimes|nullable',
            'deposits.*.deposit_amount' => 'sometimes|required|numeric|min:1.00',
            'expenses.*.expense_amount' => 'sometimes|required|numeric|min:1.00',
        ]);

        $sale = new Sale();
        $sale->client_id = 1;
        $sale->bill_id = $request->input('bill_id');
        $sale->client_name = $request->input('client');

        if (!$sale->save())
        {
            return response()->json([
                'message' => 'Something went wrong while processing your sale information. Please try again or contact your service provider.',
                'code' => 400
            ]);
        }

        $products = [];
        $productBills = $request->input('product_bills');
        if (! empty($productBills))
        {
            $productQuantities = $request->input('product_quantity');
            foreach ($productBills as $key => $value) {
                $products[] = [ 'sale_transaction_id' => $sale->id, 'product_id' => $value['product_id'], 'quantity' => $value['product_quantity'] ];
            }
        }
        // dd($products);
        Product::create($products);
        $shippings = [];
        $shippingBills = $request->input('shipping_purpose');
        if (! empty($shippingBills))
        {
            foreach ($shippingBills as $key => $value) {
                $shippings[] = [
                    'sale_transaction_id' => $sale->id, 'purpose' => $value['shipping_purpose'], 'quantity' => $value['bill_quantity'], 'amount' => $value['bill_amount']
                ];
            }
        }
        // dd($shippings);
        Shipping::create($shippings);
        $payments = [];
        $paidAmounts = $request->input('payments');
        if (! empty($paidAmounts))
        {
            foreach ($paidAmounts as $key => $value) {
                $payments[] = [ 'sale_transaction_id' => $sale->id, 'method' => $value['trans_option'], 'amount' => $value['paid_amount'] ];
            }
        }
        Payment::create($payments);
        $deposits = [];
        $depositAmounts = $request->input('deposits');
        if (! empty($depositAmounts))
        {
            foreach ($depositAmounts as $key => $value) {
                $deposits[] = [
                    'sale_transaction_id' => $sale->id,
                    'method' => $value['deposit_method'],
                    'bank_title' => '',
                    'bank_account_no' => $value['bank_ac_no'],
                    'bank_branch' => '',
                    'amount' => $value['deposit_amount']
                ];
            }
        }
        Deposit::create($deposits);
        Log::info(collect($deposits)->toJson());
        $expenses = [];
        $expenseAmounts = $request->input('expenses');
        if (! empty($expenseAmounts))
        {
            foreach ($expenseAmounts as $key => $value) {
                $expenses[] = [
                    'sale_transaction_id' => $sale->id, 'purpose' => $value['expense_purpose'], 'amount' => $value['expense_amount']
                ];
            }
        }
        Expense::create($expenses);
        Log::info(collect($expenses)->toJson());
        if ($request->ajax())
        {
            return response()->json([ 'message' => 'Your sale report is saved. Redirecting to all sales tracking page ...', 'code' => 200 ]);
        }
        return redirect()->route('user::sales.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sale)
    {
        //
        return view('sales-income')->withSale($sale)->withErrors([]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($sale)
    {
        //
        return view('sales-book-1')->withSale($sale)
                                   ->withRow(0)         // row index
                                   ->withErrors([]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTemplate(Request $request, $view)
    {
        if ($request->ajax())
        {
            return response()->view('includes.tables.' . $view, $request->all())->header('Content-Type', 'html');
        }
        return '<p>Invalid Request</p>';
    }
}
