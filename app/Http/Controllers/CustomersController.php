<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\ConcatRecord;
use App\Customer;
use App\Status;
use App\User;
use App\Welfare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $sortBy_text = ['創建日期','縣市','地區','業務名稱'];
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
//        twzipcode 會包含zipcode 要移除掉
        $request_data = $request->all();
        unset($request_data['zipcode']);


        $customer = Customer::create($request_data);
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
        if($user_id>1){
            $to_be_update_data['user_id'] = $user_id;
            $to_be_update_data['already_set_sales'] = 1;
        }

        $to_be_update_data['update_date'] = now();
        unset($to_be_update_data['zipcode']);
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
        $welfarestatus = $customer->welfarestatus->sortBy('welfare_id');
//        dd($welfarestatus);

        $concat_records = $customer->concat_records()->orderBy('update_date','DESC')->paginate(5);
        $welfares = Welfare::all();





        $data = [
            'customer' => $customer,
            'business_concat_persons' => $business_concat_persons,
            'welfarestatus' => $welfarestatus,
            'concat_records' => $concat_records,
            'welfares'=>$welfares,
        ];

        return view('customers.record',$data);
    }


    public function insert_concat_person(Request $request)
    {
        return view('customers.index');
    }


    public function add_concat_preson(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:10',
            'phone_number' => 'required|max:20',
            'extension_number' => 'required|max:20',
            'email' => 'required|email|max:50',
        ]);

        $is_left = false;
        $data = array(
            'customer_id'=>$request['customer_id'],
            'name'=>$request['name'],
            'phone_number'=>$request['phone_number'],
            'extension_number'=>$request['extension_number'],
            'email'=>$request['email'],
            'create_date'=>now(),
            'update_date'=>now(),
            'is_left'=>$is_left,
        );

        BusinessConcatPerson::insert($data);
        return response()->json([
            'success'=>'success',
        ]);

    }

    public function add_concat_record(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:2|min:0',
            'track_date'=> 'date|nullable',
            'development_content'=>'required',
        ]);
        Log::info($request['track_date']);

        $track_date = $request['track_date'];

        $user_id = Auth::user()->id;
        $data = array(
            'customer_id'=>$request['customer_id'],
            'user_id'=>$user_id,
            'status'=>$request['status'],
            'development_content'=>$request['development_content'],
            'track_content'=>$request['track_content'],
            'track_date'=>$track_date,
            'create_date'=>now(),
            'update_date'=>now(),
        );


        ConcatRecord::insert($data);
        return response()->json([
            'success'=>'success',
        ]);
    }

    public function update_concat_person(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:10',
            'phone_number' => 'required|max:20',
            'extension_number' => 'required|max:20',
            'email' => 'required|email|max:50',
            'is_left'=>'required|min:0|max:1',
        ]);
        $concat_person_id = $request['concat_person_id'];
        $concat_person = BusinessConcatPerson::find($concat_person_id);

        $concat_person->name = $request['name'];
        $concat_person->phone_number = $request['phone_number'];
        $concat_person->extension_number = $request['extension_number'];
        $concat_person->email  = $request['email'];
        $concat_person->is_left = $request['is_left'];
        $concat_person->update();
        return response()->json([
            'success'=>'success',
        ]);


    }

}
