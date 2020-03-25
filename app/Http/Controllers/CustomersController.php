<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user)
    {
        //
        $sortBy_text = ['創建日期','縣市','地區'];
        $status_text = ['---','尚未開發','成交','培養','淺在','陌生'];
        $status_filter = 0;
        $sortBy = 'create_date';
        $query = Customer::query();
//        0 means show all customers
//        1 means show my customers
        $user_filter = 0;

        if($request->has('sortBy')){
            $sortBy = $request->query('sortBy');
            $query->orderBy($sortBy, 'DESC');
        }
//        $customers = Customer::orderBy($sortBy, 'DESC')->paginate(15);

        if($request->has('user_filter')){
            $user_filter = $request->query('user_filter');
        }
        if ($user_filter>0){
            $query->where('user_id','=',Auth::user()->id);
        }


        if($request->has('status_filter')){
            $status_filter = $request->query('status_filter');
            if($status_filter>0){
//                $customers = Customer::where('status','=',$status_filter)->orderBy($sortBy, 'DESC')->paginate(15);
                $query->where('status','=',$status_filter);
            }
        }


        $customers = $query->paginate(15);



        $customer_filter_value = 0;
        $data = [
            'customers' => $customers,
            'sortBy'=>$sortBy,
            'user_filter' => $user_filter,
            'sortBy_text'=>$sortBy_text,
            'status_filter'=>$status_filter,
            'status_text'=>$status_text,
        ];
        return view('customers.index', $data);
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $status_text = ['','尚未開發','成交','培養','淺在','陌生'];
        $users = User::all();
        $data = [
            'status_text' => $status_text,
            'users' =>  $users,
        ];

        return view('customers.create', $data);
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

        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required',
            'tax_id' => 'required',
            'capital' => 'required',
            'scales' => 'required',
            'phone_number' => 'required',
            'fax_number' => 'required',
            'address' => 'required',
            'status' => 'required',
            'city' => 'required',
            'area' => 'required',
            'active_status' => 'required',
        ]);
        $customer = Customer::create($request->all());
        $customer->create_date = now();
        $customer->update();
        // $path = $request->file->storeAs('路路徑', '');
        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
        $users = User::all();
        $status_text = ['','尚未開發','成交','培養','淺在','陌生'];

        $data = [
            'users' => $users,
            'customer' => $customer,
            'status_text'=>$status_text,
        ];
        return view('customers.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
        $status_text = ['','尚未開發','成交','培養','淺在','陌生'];
        $users = User::all();
        $data = [
            'customer' => $customer,
            'status_text' => $status_text,
            'users' => $users,
        ];

        return view('customers.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $user_id = $customer->user->id;

        $this->validate($request, [
            'name' => 'required',
            'tax_id' => 'required',
            'capital' => 'required',
            'scales' => 'required',
            'phone_number' => 'required',
            'fax_number' => 'required',
            'address' => 'required',
            'status' => 'required',
            'city' => 'required',
            'area' => 'required',
        ]);

        if($request->has('user_id')){
            $user_id = $request->input('user_id');
        }

        $to_be_update_data = $request->all();
//        user choose the user
        if($user_id>0){
            $to_be_update_data['user_id'] = $user_id;
            $to_be_update_data['already_set_sales'] = 1;
        }

        $to_be_update_data['update_date'] = now();
//        dd($to_be_update_data);


//
//
//        // $path = $request->file->storeAs('路路徑', '');
        $customer->update($to_be_update_data);
        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();

        return redirect()->route('customers.index');
    }


    public function delete(Customer $customer)
    {
        $customer->is_deleted = 1;
        $customer->delete_time = now();
        $customer->update();

        return redirect()->route('customers.index');
    }


    public function record(Customer $customer)
    {
        $business_concat_persons = $customer->business_concat_persons;
        $welfarestatus = $customer->welfarestatus;

        $data = [
            'customer' => $customer,
            'business_concat_persons' => $business_concat_persons,
            'welfarestatus' => $welfarestatus,
        ];

        return view('customers.record',$data);
    }
}
