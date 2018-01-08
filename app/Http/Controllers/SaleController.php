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
        dd(Sale::all()->pluck('id', 'bill_id', 'client_name', 'created_at'));
        return view('sales-search-view')->withSales(Sale::all()->pluck('id', 'bill_id', 'client_name', 'created_at'))->withErrors([]);
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

        $generalResponseData = [
            // If you want to consider \n as newline you must keep your message inside doluble quote ("")
            'message' => "Something went wrong while processing your sale information.\nPlease try again or contact your service provider.",
            'code' => 400
        ];

        if (!$sale->save())
        {
            return response()->json($generalResponseData, 400);
        }

        $generalResponseData[ 'message' ] = "Your sale entries are saved successfully! But...\n" . $generalResponseData[ 'message' ];

        $productBills = $request->input('product_bills');
        if (! empty($productBills))
        {
            foreach ($productBills as $key => $value) {
                $p = new Product();
                $p->sale_transaction_id = $sale->id;
                $p->product_id = $value['product_id'];
                $p->quantity = $value['product_quantity'];

                if (! $p->save())
                {
                    return response()->json($generalResponseData, 400);
                }
            }
        }
        $shippingBills = $request->input('shipping_purpose');
        if (! empty($shippingBills))
        {
            foreach ($shippingBills as $key => $value) {

                $s = new Shipping();
                $s->sale_transaction_id = $sale->id;
                $s->purpose = $value[ 'shipping_purpose' ];
                $s->quantity = $value[ 'bill_quantity' ];
                $s->amount = $value[ 'bill_amount' ];

                if (! $s->save())
                {
                    return response()->json($generalResponseData, 400);
                }
            }
        }
        $payments = [];
        $paidAmounts = $request->input('payments');
        if (! empty($paidAmounts))
        {
            foreach ($paidAmounts as $key => $value) {
                $p = new Payment();
                $p->sale_transaction_id = $sale->id;
                $p->method = $value[ 'trans_option' ];
                $p->amount = $value[ 'paid_amount' ];

                if (! $p->save())
                    return response()->json($generalResponseData, 400);
            }
        }
        $depositAmounts = $request->input('deposits');
        if (! empty($depositAmounts))
        {
            foreach ($depositAmounts as $key => $value) {

                $d = new Deposit();
                $d->sale_transaction_id = $sale->id;
                $d->method = $value['deposit_method'];
                $d->bank_title = '';
                $d->bank_account_no = $value[ 'bank_ac_no' ];
                $d->bank_branch = '';
                $d->amount = $value[ 'deposit_amount' ];

                if (! $d->save())
                    return response()->json($generalResponseData, 400);
            }
        }
        $expenseAmounts = $request->input('expenses');
        if (! empty($expenseAmounts))
        {
            foreach ($expenseAmounts as $key => $value) {
                $e = new Expense();
                $e->sale_transaction_id = $sale->id;
                $e->purpose = $value['expense_purpose'];
                $e->amount = $value['expense_amount'];

                if (! $e->save())
                    return response()->json($generalResponseData, 400);
            }
        }
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
