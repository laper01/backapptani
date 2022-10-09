<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Response;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        try {

            $farmer = Farmer::select('id', 'name')->with("fruit_commoditys:id.fruit:name")->latest()->get();
            return ResponseFormatter::response(true, [
                'message' => 'Success',
                "farmer" => $farmer
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string",
                "land_location" => "required|string",
                "number_tree" => "required|numeric",
                "estimation_production" => "required|numeric",
                "land_size" => "required|numeric",
            ]);

            $farmer = new Farmer();
            $farmer->name = $request->name;
            $farmer->land_location = $request->land_location;
            $farmer->estimation_production = $request->estimation_production;
            $farmer->land_size = $request->land_size;
            $farmer->save();

            return ResponseFormatter::response(true, [
                'message' => 'Farmer saved successful',
                "farmer" => $farmer,
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, [
                    'message' => 'Something went wrong',
                    'error' => $error->validator->getMessageBag(),
                ], $error->status);
            }
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return \Illuminate\Http\Response
     */
    public function show(Farmer $farmer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return \Illuminate\Http\Response
     */
    public function edit(Farmer $farmer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Farmer  $farmer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Farmer $farmer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Farmer $farmer)
    {
        //
    }
}
