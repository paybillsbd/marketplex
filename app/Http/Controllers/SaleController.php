<?php

namespace MarketPlex\Http\Controllers;

use Illuminate\Http\Request;
use MarketPlex\SaleTransaction as Sale;

use Auth;

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
        //
        // dd(Auth::user()->stores);
        return view('sales-book-1')->withErrors([])
                                   ->withStores(Auth::user()->stores->pluck('id', 'name'));
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
            $view_data = [
                'row_id' => $request->input('row_id'),
                'datetime' => $request->input('datetime'),
                'product_title' => $request->input('product_title'),
                'store_name' => $request->input('store_name'),
                'bank_accounts' => $request->input('bank_accounts'),
                'product_available_quantity' => $request->input('product_available_quantity')
            ];
            return response()->view('includes.tables.' . $view, $view_data)->header('Content-Type', 'html');
        }
        return '<p>Invalid Request</p>';
    }
}
