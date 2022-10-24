<?php

namespace App\Http\Controllers;

use App\Models\CustomerTransaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Customer;
use App\Models\FarmerTransaction;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $customerTransaction = CustomerTransaction::with(["farmer_transaction.fruit_commodity.farmer", "farmer_transaction.fruit_commodity.fruit", "customer"])->latest()->get();
            return ResponseFormatter::response(true, [
                // 'message' => 'Success',
                "customer_transaction" => $customerTransaction
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
        DB::beginTransaction();
        try {
            $request->validate([
                "farmer_transaction_id" => "required",
                "weight" => 'required',
                "price" => 'required',
                "shiping_payment" => "required",
                "total_payment" => 'required',
                "shiping_date" => 'required',
                "address"=> 'required',
                "receiver_name" => "required",
                "phone_number" => "required|string",
            ]);
            $customer = Customer::where("phone_number", $request->phone_number)->first();

            if($customer == null){
                $customer = new Customer();
                $customer->phone_number = $request->phone_number;
                $customer->save();
            }

            $farmerTransaction = FarmerTransaction::find($request->farmer_transaction_id);

            if((floatval($request->weight)+$farmerTransaction->weight_selled) > $farmerTransaction->weight ){
                return ResponseFormatter::response(false, null, 400, "Berat melebihi berat buah yang disediakan");
            }

            $farmerTransaction->weight_selled = $request->weight;
            $farmerTransaction->save();

            $customerTransaction = new CustomerTransaction();
            $customerTransaction->farmer_transaction_id = $request->farmer_transaction_id;
            $customerTransaction->customer_id = $customer->id;
            $customerTransaction->shiping_date = $request->shiping_date;
            $customerTransaction->weight = $request->weight;
            $customerTransaction->price = $request->price;
            $customerTransaction->total_payment = $request->total_payment;
            $customerTransaction->address = $request->address;
            $customerTransaction->receiver_name = $request->receiver_name;
            $customerTransaction->shiping_payment = $request->shiping_payment;
            $customerTransaction->struck_link = "struck/{$customerTransaction->id}";
            $customerTransaction->save();
            DB::commit();
            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Behasil menambah transaksi customer");
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
     * @param  \App\Models\CustomerTransaction  $customerTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $farmerTransaction = CustomerTransaction::with(["farmer_transaction.fruit_commodity.farmer", "farmer_transaction.fruit_commodity.fruit", "customer"])->find($id);
            return ResponseFormatter::response(true, [
                "farmer_transaction" => $farmerTransaction
            ], Response::HTTP_OK, "Berhasil");
        } catch (Exception $error) {
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerTransaction  $customerTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerTransaction $customerTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerTransaction  $customerTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerTransaction $customerTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerTransaction  $customerTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerTransaction $customerTransaction)
    {
        //
    }
}
