<?php

namespace App\Http\Controllers;

use App\Models\FruitCommodity;
use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class FruitCommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $farmer = Farmer::select('id', 'name')->with("fruit_commoditys:id", "fruit_commoditys.fruit:name")->latest()->get();
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
        $user = Auth::user()->load(["collector"]);
        // dd($user);
        try {
            $request->validate([
                "farmer_id" => 'required',
                "fruit_id" => 'required',
            ]);

            $fruitCommodity = new FruitCommodity();
            $fruitCommodity->farmer_id = $request->farmer_id;
            $fruitCommodity->fruit_id = $request->fruit_id;
            $fruitCommodity->collector_id = $user->collector->collector_id;
            $fruitCommodity->save();

            return ResponseFormatter::response(true, [
                'message' => 'Comodity saved successful',
                // "user" => $user,
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
     * @param  \App\Models\FruitCommodity  $fruitCommodity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {

            $fruitCommodity =  FruitCommodity::find($id);
            return ResponseFormatter::response(true, [
                'message' => 'Comodity updated successful',
                "comodity" => $fruitCommodity,
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FruitCommodity  $fruitCommodity
     * @return \Illuminate\Http\Response
     */
    public function edit(FruitCommodity $fruitCommodity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FruitCommodity  $fruitCommodity
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request,)
    {
        try {
            $request->validate([
                "blossoms_tree_date" => 'required',
                "harvesting_date" => 'required',
                "fruit_grade" => 'required',
                "weight" => 'required',
            ]);

            $fruitCommodity =  FruitCommodity::find($id);
            $fruitCommodity->blossoms_tree_date = $request->blossoms_tree_date;
            $fruitCommodity->harvesting_date = $request->harvesting_date;
            $fruitCommodity->fruit_grade = $request->fruit_grade;
            $fruitCommodity->weight = $request->weight;
            $fruitCommodity->save();

            return ResponseFormatter::response(true, [
                'message' => 'Comodity updated successful',
                // "user" => $user,
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

    public function valid($id){
        try {
            $dt = Carbon::now();

            $fruitCommodity = FruitCommodity::find($id);
            $fruitCommodity->verified = true;
            $fruitCommodity->verfied_date = $dt->toDateString();
            $fruitCommodity->save();
            return ResponseFormatter::response(true, [
                'message' => 'Comodity validated successful',
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FruitCommodity  $fruitCommodity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $fruitCommodity = FruitCommodity::find($id);
            $fruitCommodity->delete();
            return ResponseFormatter::response(true, [
                'message' => 'Comodity deleted successful',
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }
}
