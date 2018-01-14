<?php

namespace MarketPlex\Http\Controllers;

use MarketPlex\BillPayment;
use Illuminate\Http\Request;
use MarketPlex\SaleTransaction as Sale;
use MarketPlex\Expense;
use MarketPlex\Deposit;

class BillPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $todayDate = \Carbon\Carbon::today()->toDateString();
        // $todayDate = \Carbon\Carbon::createFromFormat('m/d/Y', '01/07/2018')->toDateString();
        $incomes = Sale::getIncomesToday();
        // $incomes = Sale::whereDate('created_at', '=', $todayDate)->get();

        $totalBill = 0;
        $totalDue = 0;
        $totalPaid = 0;
        $expenses = collect([]);
        $deposits = collect([]);
        $payments = collect([]);
        foreach ($incomes as $income)
        {
            $totalBill += $income->getBillAmount();
            $totalDue += $income->getCurrentDueAmount();
            $totalPaid += $income->getTotalPaidAmount();

            foreach (Expense::with('sale')->get() as $value) {
                $expenses->push($value);
            }
            foreach (Deposit::with('sale')->get() as $value) {
                $deposits->push($value);
            }
            foreach (BillPayment::with('sale')->get() as $value) {
                $payments->push($value);
            }
        }
        // dd($deposits);
        return view('sales-income')->withIncomes($incomes)
                                   ->withFromDate($todayDate)
                                   ->withToDate($todayDate)
                                   ->withTotalBill($totalPaid)
                                   ->withTotalDue($totalDue)
                                   ->withTotalPaid($totalPaid)
                                   ->withExpenses($expenses)
                                   ->withDeposits($deposits)
                                   ->withPayments($payments->whereIn('method', [
                                                            'by_cash_hand2hand',
                                                            'by_cash_cheque_deposit',
                                                            'by_cash_electronic_trans']));
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
