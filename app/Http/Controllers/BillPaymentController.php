<?php

namespace MarketPlex\Http\Controllers;

use Validator;
use MarketPlex\BillPayment;
use Illuminate\Http\Request;
use MarketPlex\SaleTransaction as Sale;
use MarketPlex\Expense;
use MarketPlex\Deposit;

class BillPaymentController extends Controller
{

    private $emptyRecordTableMessages = [
        'incomes' => 'No incomes to show from your date queries.',
        'expenses' => 'No expenses to show from your date queries.',
        'deposits' => 'No bank deposits to show from your date queries.',
    ];

    private $helpMessages = [
        'incomes' => 'All sales bill record made by the duration you provided in date queries',
        'search_sales_income' => 'Search sales incomes between a duration'
    ];

    private function viewWithFiltered($dateFrom, $dateTo)
    {
        $onDate = $dateFrom === $dateTo;
        $dateQuery = $onDate ? $dateFrom : [ $dateFrom, $dateTo ];
        $dateScope = $onDate ? 'On' : 'Between';
        $incomes = Sale::{ 'Incomes' . $dateScope}($dateQuery)->get();
        return view('sales-income')
                    ->withIncomes($incomes)
                    ->withFromDate($dateFrom)
                    ->withToDate($dateTo)
                    ->withTotalBill($incomes->sum(function($income) { return $income->getBillAmount(); }))
                    ->withTotalDue($incomes->sum(function($income) { return $income->getCurrentDueAmount(); }))
                    ->withTotalPaid($incomes->sum(function($income) { return $income->getTotalPaidAmount(); }))
                    ->withExpenses(Expense::{'Sales' . $dateScope}($dateQuery)->get())
                    ->withVaultDeposits(Deposit::{'Sales' . $dateScope}($dateQuery)->ToVault()->get())
                    ->withBankDeposits(Deposit::{'Sales' . $dateScope}($dateQuery)->ToBank()->get())
                    ->withPayments(BillPayment::{'Sales' . $dateScope}($dateQuery)->CashReceived()->get())
                    ->withEmptyRecordMessages($this->emptyRecordTableMessages)
                    ->withHelpMessages($this->helpMessages);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateToday = \Carbon\Carbon::today()->toDateString();
        // $dateToday = \Carbon\Carbon::createFromFormat('m/d/Y', '01/07/2018')->toDateString();
        return $this->viewWithFiltered($dateToday, $dateToday);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'queries.*' => 'required|date',
        ], [
            'queries.from_date' => 'Please provide a valid date input for start date',
            'queries.to_date' => 'Please provide a valid date input for end date'
        ]);

        $dateRange = $request->input('queries');
        return $this->viewWithFiltered($dateRange['from_date'], $dateRange['to_date']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \MarketPlex\BillPayment  $billPayment
     * @return \Illuminate\Http\Response
     */
    public function show(BillPayment $billPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MarketPlex\BillPayment  $billPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(BillPayment $billPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MarketPlex\BillPayment  $billPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillPayment $billPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MarketPlex\BillPayment  $billPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillPayment $billPayment)
    {
        //
    }
}
