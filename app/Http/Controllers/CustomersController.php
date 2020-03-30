<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\ConcatRecord;
use App\Customer;
use App\Status;
use App\User;
use App\Welfare;
use App\WelfareCompany;
use App\WelfareDetail;
use App\WelfareStatus;
use App\WelfareType;
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
    public function index(Request $request, User $user)
    {
        //
//        dd($request);
        $sortBy_text = ['創建日期', '縣市', '地區', '業務名稱', '狀態'];
        $status_text = ['---', '尚未開發', '成交', '培養', '淺在'];
        $status_filter = 0;
        $sortBy = 'create_date';
        $query = Customer::query();
//        0 means show all customers
//        1 means show my customers
        $user_filter = 0;
        $search_type = 0;
        $search_info = '';


        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    $query->orWhere('name','like',"%{$search_info}%");
                    break;
                case 2:
                    $query->orWhere('city','like',"%{$search_info}%");
                    $query->orWhere('area','like',"%{$search_info}%");
                    break;
//                case 3:
//                    $query->orWhere('city','like',"%{$search_info}%");

                default:
                    break;
            }
//            where('name', 'like', "%{$name}%")

        }


        if ($request->has('sortBy')) {
            $sortBy = $request->query('sortBy');
            foreach ($sortBy as $q) {
                $query->orderBy($q, 'DESC');

            }
        }
//        dd($query);
//        $customers = Customer::orderBy($sortBy, 'DESC')->paginate(15);

        if ($request->has('user_filter')) {
            $user_filter = $request->query('user_filter');
        }
        if ($user_filter > 0) {
            $query->where('user_id', '=', Auth::user()->id);
        }


        if ($request->has('status_filter')) {
            $status_filter = $request->query('status_filter');
            foreach ($status_filter as $s) {
                if ($s > 0) {
//                $customers = Customer::where('status','=',$status_filter)->orderBy($sortBy, 'DESC')->paginate(15);
                    $query->orWhere('status', '=', $s);
                }
            }

        }


        $customers = $query->paginate(15);


        $customer_filter_value = 0;
        $data = [
            'customers' => $customers,
            'sortBy' => $sortBy,
            'user_filter' => $user_filter,
            'sortBy_text' => $sortBy_text,
            'status_filter' => $status_filter,
            'status_text' => $status_text,
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
        $status_text = ['', '尚未開發', '成交', '培養', '淺在'];
        $users = User::all();
        $data = [
            'status_text' => $status_text,
            'users' => $users,
        ];

        return view('customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
        $users = User::all();
        $status_text = ['', '尚未開發', '成交', '培養', '淺在'];

        $data = [
            'users' => $users,
            'customer' => $customer,
            'status_text' => $status_text,
        ];
        return view('customers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
        $status_text = ['', '尚未開發', '成交', '培養', '淺在'];
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
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

        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
        }

        $to_be_update_data = $request->all();
//        user choose the user
        if ($user_id > 1) {
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
     * @param int $id
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


    public static $welfare_types = ['提貨卷', '禮卷', '泡湯卷', '電影票', '點卷', '點數', '其他'];

    public function record(Customer $customer)
    {

        $business_concat_persons = $customer->business_concat_persons()->orderBy('create_date', 'DESC')->get();
        $welfarestatus = $customer->welfarestatus->sortBy('welfare_id');
//        dd($welfarestatus);

        $concat_records = $customer->concat_records()->orderBy('update_date', 'DESC')->paginate(5);
        $welfare_companies = WelfareCompany::all();
        $welfare_details = WelfareDetail::all();


        $welfare_type_names = self::$welfare_types;
        $welfare_type_codes = range(0, count($welfare_type_names) - 1);

        $status_text = ['---', '尚未開發', '成交', '培養', '淺在'];


        $data = [
            'customer' => $customer,
            'business_concat_persons' => $business_concat_persons,
            'welfarestatus' => $welfarestatus,
            'concat_records' => $concat_records,
            'welfare_companies' => $welfare_companies,
            'welfare_details' => $welfare_details,
            'welfare_type_names' => $welfare_type_names,
            'welfare_type_codes' => $welfare_type_codes,
            'status_text' => $status_text,
//            'welfares'=>$welfares,
        ];

        return view('customers.record', $data);
    }

    public function delete_welfare_type(Request $request)
    {
        $welfare_type = WelfareType::find($request['type_id']);
        $welfare_type->delete();
        return response()->json([
            'success' => 'delete success!',
        ]);

    }


    public function update_status(Request $request)
    {
        $customer = Customer::find($request['customer_id']);
        $customer->status = $request['customer_status'];
        $customer->active_status = $request['active_status'];
        $customer->update();

        return response()->json([
            'success' => 'update status success!',
        ]);
    }


    public function add_concat_person(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:10',
            'phone_number' => 'required|max:20',
            'extension_number' => 'required|max:20',
            'email' => 'required|email|max:50',
        ]);

        $is_left = false;
        $data = array(
            'customer_id' => $request['customer_id'],
            'name' => $request['name'],
            'phone_number' => $request['phone_number'],
            'extension_number' => $request['extension_number'],
            'email' => $request['email'],
            'create_date' => now(),
            'update_date' => now(),
            'is_left' => $is_left,
        );

        BusinessConcatPerson::insert($data);
        return response()->json([
            'success' => 'success',
        ]);

    }

    public function add_concat_record(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:2|min:0',
            'track_date' => 'date|nullable',
            'development_content' => 'required',
        ]);
        Log::info($request['track_date']);

        $track_date = $request['track_date'];

        $user_id = Auth::user()->id;
        $data = array(
            'customer_id' => $request['customer_id'],
            'user_id' => $user_id,
            'status' => $request['status'],
            'development_content' => $request['development_content'],
            'track_content' => $request['track_content'],
            'track_date' => $track_date,
            'create_date' => now(),
            'update_date' => now(),
        );


        ConcatRecord::insert($data);
        return response()->json([
            'success' => 'success',
        ]);
    }

    public function update_concat_person(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:10',
            'phone_number' => 'required|max:20',
            'extension_number' => 'required|max:20',
            'email' => 'required|email|max:50',
            'is_left' => 'required|min:0|max:1',
        ]);
        $concat_person_id = $request['concat_person_id'];
        $concat_person = BusinessConcatPerson::find($concat_person_id);
        $concat_person->want_receive_mail = $request['want_receive_mail'];
        $concat_person->name = $request['name'];
        $concat_person->phone_number = $request['phone_number'];
        $concat_person->extension_number = $request['extension_number'];
        $concat_person->email = $request['email'];
        $concat_person->is_left = $request['is_left'];
        $concat_person->update_date = now();
        $concat_person->update();
        return response()->json([
            'success' => 'update concat person info success',
        ]);

    }

    public function update_concat_record(Request $request)
    {
        $concat_record = ConcatRecord::find($request['concat_record_id']);
        $concat_record->status = $request['record_status'];
        $concat_record->development_content = $request['development_content'];
        $concat_record->track_content = $request['track_content'];
        $concat_record->track_date = $request['track_date'];
        $concat_record->user_id = Auth::user()->id;
        $concat_record->update();
        return response()->json([
            'success' => 'update concat record success',
        ]);

    }

    public function add_welfare_types(Request $request)
    {
        $this->validate($request, [
            'welfare_status_id' => 'required',
            'welfare_code' => 'required',
            'budget' => 'numeric|nullable'

        ]);
        if ($request['welfare_code'] > 0) {
//        這邊再新增一種新的福利資訊～
            $data = array(
                'code' => $request['welfare_code'],
                'welfare_status_id' => $request['welfare_status_id'],
                'name' => self::$welfare_types[$request['welfare_code']],
                'welfare_type_company_relation_id' => 1,

            );
            WelfareType::insert($data);

        }
//        新增note
        $welfare_status = WelfareStatus::find($request['welfare_status_id']);
        $welfare_status->note = $request['note'];
        $welfare_status->budget = $request['budget'];
        $welfare_status->update();
        return response()->json([
            'success' => 'success',
        ]);


    }


}
