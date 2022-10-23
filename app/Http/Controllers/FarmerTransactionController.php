<?php

namespace App\Http\Controllers;

use App\Models\FarmerTransaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Farmer;
use App\Models\FruitCommodity;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FarmerTransactionController extends Controller
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
            $fruitComodity = FruitCommodity::query();

            $fruitComodity->where('verified', true);

            $fruitComodity->with(['farmer'])->get();

            return ResponseFormatter::response(true, [
                // 'message' => 'Success',
                "fruit_comodity" => $fruitComodity
            ], Response::HTTP_OK, "Success");
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                // 'message' => 'Something went wrong',
                // 'error' => $error,
            ], 500, "Ada yang salah");
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
        $request->validate([
            "fruit_comodity_id" => "required",
            "wieght" => 'required',
            "price" => 'required',
            "price_total" => 'required',
        ]);
        DB::beginTransaction();
        try {
            $fruitComodity = FruitCommodity::find($request->fruit_comodity_id);

            if((floatval($request->weight)+$fruitComodity->weight_selled) > $fruitComodity->weight ){
                return ResponseFormatter::response(false, null, 400, "Berat melebihi berat buah pada komoditas");
            }

            $fruitComodity->weight_selled = $request->wieght;
            $fruitComodity->save();

            $farmerTransaction = new FarmerTransaction();
            $farmerTransaction->fruit_comodity_id = $request->fruit_comodity_id;
            $farmerTransaction->wieght = $request->wieght;
            $farmerTransaction->price = $request->price;
            $farmerTransaction->save();

            DB::commit();
            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Behasil menambah Comoditas");
        } catch (Exception $error) {
            DB::rollBack();
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, null, $error->status, $error->validator->getMessageBag(),);
            }
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FarmerTransaction  $farmerTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            try {
                $farmerTransaction = FarmerTransaction::find($id);
                return ResponseFormatter::response(true, [
                    "farmer_transaction" => $farmerTransaction
                ], Response::HTTP_OK, "Transaksi petani berhasil dirubah");
            } catch (Exception $error) {
                return ResponseFormatter::response(false, null, 500, "Ada yang salah");
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FarmerTransaction  $farmerTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(FarmerTransaction $farmerTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FarmerTransaction  $farmerTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FarmerTransaction $farmerTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FarmerTransaction  $farmerTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(FarmerTransaction $farmerTransaction)
    {
        //
    }
}
