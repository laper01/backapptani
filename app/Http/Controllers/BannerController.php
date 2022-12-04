<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
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
            $banner = Banner::select('url')->latest()->get();
            return ResponseFormatter::response(
                true,
                $banner,
                Response::HTTP_OK,
                "Success"
            );
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [], 500, "Ada yang salah");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {



            return ResponseFormatter::response(true, [
                'message' => 'Login successful',
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, [
                    // 'message' => 'Something went wrong',
                    // 'error' =>
                ], $error->status, $error->validator->getMessageBag());
            }
            return ResponseFormatter::response(false, [
                // 'message' => 'Something went wrong',
                // 'error' => $error,
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
        //
        try {
            $request->validate([
                "image" => "required|mimes:jpeg,jpg,bmp,png|max:8192"
            ]);

            $path = $request->file('image')->store('public/banner');
            $url = Storage::url($path);

            $banner = new Banner();
            $banner->url = $url;
            $banner->path = $path;
            $banner->save();

            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Banner  berhasil disimpan");
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, null, $error->status, $error->validator->getMessageBag());
            }
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if (Storage::exists($request->path)) {
            $file = Banner::where('path', $request->path)->first();
            $file->delete();
            Storage::delete($request->path);
            return ResponseFormatter::response(true, null, Response::HTTP_OK, "Banner behasil dihapus");
        }
        return ResponseFormatter::response(false, null, 500, "Ada yang salah");
    }
}
