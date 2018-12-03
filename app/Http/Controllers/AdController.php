<?php

namespace App\Http\Controllers;

use App\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ads = Ad::orderBy('created_at')->paginate(15);

        $data = [
            'ads' => $ads,
        ];
        return view('ads.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('ads.create');
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
    public function edit(Ad $ad)
    {
        //
        $data=[
            'ad' => $ad,
        ];
        return view('ads.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ad $ad)
    {
        $this->validate($request, [
            'name' => 'required',
            'text_1' => 'required|string|max:20',
            'text_2' => 'required|string|max:20',
            'text_3' => 'required|string|max:20',
            'image' => 'image',
        ]);

        if($request->image!=null){
            $file = $request->file('image');
            $unique_name = 'ad_'.$ad->id.'.'.$file->extension();
            $ad->imagename = $unique_name;
            $ad->update();
            $request->file('image')->move(public_path().'/storage',$unique_name);
        }

        // $path = $request->file->storeAs('路路徑', '');
        $ad->update($request->all());
        return redirect()->route('ads.index');
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
