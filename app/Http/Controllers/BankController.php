<?php

namespace MarketPlex\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use MarketPlex\Bank as Account;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json([ 'accounts' => Account::all() ]);
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
        $this->validate($request,[
            'new_bank_name' => 'required|max:150',
            'bank_branch_name' => 'required|max:100',
            'bank_acc_no' => 'required|max:30|unique:banks',
            // 'bank_detail' => 'max:400'
        ]);

        $account = new Account();
        $account->title = $request->input('new_bank_name');
        $account->account_no = $request->input('bank_acc_no');
        $account->branch = $request->input('bank_branch_name');

        if (! $account->save())
        {

            return response()->json([
                'message' => 'Something went wrong during adding your bank account.',
                'code' => 400
            ], 400);
        }
        return response()->json([
            'message' => 'Your account ' . $account->account_no . ' is added successfully!',
            'code' => 200
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Account $bank)
    {
        //
        if (!$bank)
            response()->json([ 'summary_html' => Account::htmlInvalidDataWarning() ]);
        return response()->json([
            'summary' => $bank->summary(),
            'summary_html' => $bank->htmlSummary(),
            'account_no' => $bank->account_no
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
}
