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
            $fruitCommodity = FruitCommodity::with(["farmer", "fruit"])->latest()->get();
            return ResponseFormatter::response(true, [
                // 'message' => 'Success',
                "fruit_comodity" => $fruitCommodity
            ], Response::HTTP_OK, "berhasil");
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                // 'message' => 'Something went wrong',
                // 'error' => $error,
            ], 500, "Ada yang salah");
        }
    }


    public function listVerifiedComodity()
    {
        try {
            $fruitComodityQuery = FruitCommodity::query();

            $fruitComodityQuery->where('verified', true);

            $fruitComodity = $fruitComodityQuery->with(['farmer', "fruit"])->get();


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
        $user = Auth::user()->load(["collector"]);
        // return response( $user->collector->id);
        try {
            $request->validate([
                "farmer_id" => 'required',
                "fruit_id" => 'required',
            ]);

            $fruitCommodity = new FruitCommodity();
            $fruitCommodity->farmer_id = $request->farmer_id;
            $fruitCommodity->fruit_id = $request->fruit_id;
            $fruitCommodity->collector_id = $user->collector->id;
            $fruitCommodity->save();

            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Komoditas berhasil disimpan");
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, null, $error->status, $error->validator->getMessageBag(),);
            }
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
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
            return ResponseFormatter::response(
                true,
                $fruitCommodity,
                Response::HTTP_OK,
                "berhasil"
            );
        } catch (Exception $error) {
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
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
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                "blossoms_tree_date" => 'required',
                "harvesting_date" => 'required',
                "fruit_grade" => 'required',
                "weight" => 'required',
            ]);


            $fruitCommodity =  FruitCommodity::find($id);

            if ($fruitCommodity->verified) {
                return ResponseFormatter::response(false, null, 401, "Comoditas sudah diverifikasi");
            }

            $fruitCommodity->blossoms_tree_date = $request->blossoms_tree_date;
            $fruitCommodity->harvesting_date = $request->harvesting_date;
            $fruitCommodity->fruit_grade = $request->fruit_grade;
            $fruitCommodity->weight = $request->weight;
            $fruitCommodity->save();

            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Komoditas berhasil diupdate");
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, null, $error->status, $error->validator->getMessageBag(),);
            }
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }

    public function valid($id)
    {
        try {
            $dt = Carbon::now();

            $fruitCommodity = FruitCommodity::find($id);
            $fruitCommodity->verified = true;
            $fruitCommodity->verfied_date = $dt->toDateString();
            $fruitCommodity->save();
            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Komoditas berhasil divalidasi");
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
        try {
            $fruitCommodity = FruitCommodity::find($id);
            if ($fruitCommodity->verified) {
                return ResponseFormatter::response(false, null, 401, "Comoditas sudah diverifikasi");
            }
            $fruitCommodity->delete();
            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Komoditas berhasil dihapus");
        } catch (Exception $error) {
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }
}
