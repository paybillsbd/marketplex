<?php

namespace MarketPlex\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $options = [
            [ 'id' => 1, 'text' => 'Client 1' ],
            [ 'id' => 1, 'text' => 'Client 2' ],
            [ 'id' => 1, 'text' => 'Client 3' ],
            [ 'id' => 1, 'text' => 'Client 4' ],
        ];
        $data = [
            'items' => $options,
            'total_count' => count($options)
        ];
        $clients = [
            [ 'value' => 1, 'label' => 'ColdFusion' ],
            [ 'value' => 2, 'label' => 'Scala' ],
            [ 'value' => 3, 'label' => 'Haskell' ],
            [ 'value' => 4, 'label' => 'Python' ],
        ];
        return response()->json($clients);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
