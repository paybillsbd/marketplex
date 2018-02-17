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

use PDF;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewSalesFiltered(Sale::all());
    }

    private function viewSalesFiltered($sales)
    {
        return view('sales-search-view')->withSales($sales)
                                        ->withRouteQueryToday(route('user::payments.index', [ 'api_token' => Auth::user()->api_token ]));       
    }

    private function viewSalesEntryForm()
    {
        return view('sales-book-1')->withStores(Auth::user()->stores->pluck('id', 'name'))
                                   ->withMessages(Sale::messages())
                                   ->withBillId(Sale::generateBillId());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->viewSalesEntryForm();
    }

    private function saveWithRedirect(Request $request, $sale)
    {
        $generalResponseData = [
            // If you want to consider \n as newline you must keep your message inside doluble quote ("")
            'message' => "Something went wrong while processing your sale information.\nPlease try again or contact your service provider.",
            'code' => 400
        ];

        if (!$sale->saveTransaction($sale->productbills->IsEmpty() ? $request->only('bill_id', 'client') : $request->only('client')))
        {
            return response()->json($generalResponseData, 400);
        }

        $generalResponseData[ 'message' ] = "Your sale entries are saved successfully! But...\n" . $generalResponseData[ 'message' ];

        $productBills = $request->input('product_bills');
        if (empty($productBills) || ! Product::saveManyBills($productBills, $sale))
        {
            return response()->json($generalResponseData, 400);
        }
        $generalResponseData[ 'sold_products' ] = $productBills;

        $shippingBills = $request->input('shipping_bills')?:[];
        if (! Shipping::saveManyBills($shippingBills, $sale))
        {
            return response()->json($generalResponseData, 400);
        }
        $paidAmounts = $request->input('payments')?:[];
        if (! Payment::saveManyPayments($paidAmounts, $sale))
        {
            return response()->json($generalResponseData, 400);
        }
        $depositAmounts = $request->input('deposits')?:[];
        if (! Deposit::saveManyDeposits($depositAmounts, $sale))
        {
            return response()->json($generalResponseData, 400);
        }
        $expenseAmounts = $request->input('expenses')?:[];
        if (! Expense::saveManyExpenses($expenseAmounts, $sale))
        {
            return response()->json($generalResponseData, 400);
        }

        if ($request->ajax())
        {
            return response()->json([
                'message' => 'Your sale report is saved. Redirecting to all sales tracking page ...',
                'code' => 200
            ]);
        }
        return redirect()->route('user::sales.index');
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
        $this->validate($request, [
            'bill_id' => 'required|unique:sale_transactions,bill_id|max:100',
            'client' => 'required|max:300',
            'product_bills.*.product_id' => 'present|required',
            'product_bills.*.product_quantity' => 'present|required|numeric|min:1',
            'shipping_bills.*.shipping_purpose' => 'sometimes|required|max:50',
            'shipping_bills.*.bill_amount' => 'sometimes|required|min:1.00',
            'shipping_bills.*.bill_quantity' => 'sometimes|required|numeric|min:1',
            'payments.*.trans_option' => [
                'sometimes',
                'present',
                Rule::in([ 'by_cash_hand2hand', 'by_cash_cheque_deposit', 'by_cash_electronic_trans', 'by_cheque_hand2hand' ]),
            ],
            'payments.*.paid_amount' => 'sometimes|required|min:1.00',
            'deposits.*.deposit_method' => [
                'sometimes',
                'present',
                Rule::in([ 'bank', 'vault' ]),
            ],
            'deposits.*.bank_ac_no' => 'sometimes|nullable',
            'deposits.*.deposit_amount' => 'sometimes|required|min:1.00',
            'expenses.*.expense_amount' => 'sometimes|required|min:1.00',
        ]);
        return $this->saveWithRedirect($request, new Sale());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
        return view('sales-income')->withSale($sale);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'queries' => 'present|array',
            'queries.client_name' => 'present',
            'queries.billing_id' => 'present',
            'queries.from_date' => 'required_with:queries.to_date',
            'queries.to_date' => 'required_with:queries.from_date',
        ]);

        $queries = $request->input('queries');
        if (Sale::expectedQueries($queries))
        {
            if (Sale::nothingSearched($queries))
            {
                return $request->ajax() ? response()->json([ 'message' => 'No queries found!', 'code' => 400 ], 400)
                    : $this->viewSalesFiltered(Sale::all())->withErrors([ 'queries' => 'No queries found!' ]);
            }
            $sales = Sale::search($queries)->get();
            // dd($sales);
            return $request->ajax() ? response()->json([ 'sales' => $sales, 'code' => 200 ])
                : $this->viewSalesFiltered($sales);
        }
        return $request->ajax() ? response()->json([ 'message' => 'No queries found!', 'code' => 400 ], 400)
            : $this->viewSalesFiltered(Sale::all())->withErrors([ 'queries' => 'No queries found!' ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        return $this->viewSalesEntryForm()->withSale($sale)
                                          ->withDues(Sale::where('client_name', 'like', '%' . $sale->client_name . '%')->get())
                                          ->withRow($sale->nextRowIndex());        // row index
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        // to see custom validation messages: resources/lang/en/validation.php
        $this->validate($request,[
            'client' => 'required|max:300',
            'product_bills.*.product_id' => 'present|required',
            'product_bills.*.product_quantity' => 'present|required|numeric|min:1',
            'shipping_bills.*.shipping_purpose' => 'sometimes|required|max:50',
            'shipping_bills.*.bill_amount' => 'sometimes|required|min:1.00',
            'shipping_bills.*.bill_quantity' => 'sometimes|required|numeric|min:1',
            'payments.*.trans_option' => [
                'sometimes',
                'present',
                Rule::in([ 'by_cash_hand2hand', 'by_cash_cheque_deposit', 'by_cash_electronic_trans', 'by_cheque_hand2hand' ]),
            ],
            'payments.*.paid_amount' => 'sometimes|required|min:1.00',
            'deposits.*.deposit_method' => [
                'sometimes',
                'present',
                Rule::in([ 'bank', 'vault' ]),
            ],
            'deposits.*.bank_ac_no' => 'sometimes|nullable',
            'deposits.*.deposit_amount' => 'sometimes|required|min:1.00',
            'expenses.*.expense_amount' => 'sometimes|required|min:1.00',
        ]);
        return $this->saveWithRedirect($request, $sale);
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

    public function downloadInvoice(Request $request, Sale $sale)
    {   
        $data = [ 'sale' => $sale, 'messages' => Sale::messages() ];
        $pdf = PDF::loadView('invoices.invoice-sales-general', $data)->setPaper('a4', 'portrait')->setWarnings(false);
        return $request->query('download') ? $pdf->download('invoice-sales-general.pdf') : $pdf->stream();
    }
}
