<?php

namespace App\Http\Controllers;

use App\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $suggestions = Suggestion::orderBy('created_at', 'DESC')->paginate(15);

        $data = [
            'suggestions' => $suggestions,
        ];

        return view('suggestions.index', $data);
    }

    public function reply(Request $request,Suggestion $suggestion)
    {

        $email = $suggestion->email;
        $name = $suggestion->name;
        Mail::raw($request->reply, function ($message) use ($email,$name) {
            $message->to($email)->subject($name.'你好');
        });
        $suggestion->is_replied = 1;
        $suggestion->update();
        return redirect()->route('suggestions.index');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Suggestion $suggestion)
    {
        //
        $data = [
            'suggestion' => $suggestion,


        ];
        return view('suggestions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
