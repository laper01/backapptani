<?php

namespace App\Http\Controllers;

use App\Models\fruit;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Response;
use Exception;

class FruitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $fruits = fruit::latest()->get();
            return ResponseFormatter::response(true, [
                'message' => 'Success',
                "fruits" => $fruits
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
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
     * @param  \App\Models\fruit  $fruit
     * @return \Illuminate\Http\Response
     */
    public function show(fruit $fruit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\fruit  $fruit
     * @return \Illuminate\Http\Response
     */
    public function edit(fruit $fruit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\fruit  $fruit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, fruit $fruit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\fruit  $fruit
     * @return \Illuminate\Http\Response
     */
    public function destroy(fruit $fruit)
    {
        //
    }
}
