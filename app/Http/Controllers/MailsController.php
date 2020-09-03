<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
class MailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $sortBy_text = ['創建日期', '姓名', '公司(客戶)', '縣市地區', '在職狀態', '收信意願'];
        $status_text = ['---', '在職', '離職'];
        $is_left = -1;
        $want_receive_mail = -1;
        $sortBy = 'create_date';
        $query = BusinessConcatPerson::query();
        $query->join('customers', 'customers.id', '=', 'business_concat_persons.customer_id');

        $query->select('customers.name as customer_name', 'business_concat_persons.name as name',
            'email', 'is_left', 'business_concat_persons.update_date as update_date',
            'business_concat_persons.create_date as create_date', 'city', 'area', 'want_receive_mail',
            'user_id');

        $search_type = 0;
        $search_info = '';


//        user is not root
        if (Auth::user()->level != 2) {
            $query->where('user_id', '=', Auth::user()->id);
        }

        if ($request->has('is_left')) {
            $is_left = $request->input('is_left');
            if ($is_left >= 0) {
                $query->where('is_left', '=', $is_left);
//                $query->orderBy('is_left', 'DESC');
            }
        }


        if ($request->has('want_receive_mail')) {
            $want_receive_mail = $request->input('want_receive_mail');
            if ($want_receive_mail >= 0) {
                $query->where('want_receive_mail', '=', $want_receive_mail);
            }
        }
        if ($request->has('search_type')) {
            $search_type = $request->input('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->input('search_info');
            switch ($search_type) {
                case 1:
                    $query->where('business_concat_persons.name', 'like', "%{$search_info}%");
//                    $query->orderBy('business_concat_persons.name', 'DESC');
                    break;
                case 2:
                    $query->where('customers.name', 'like', "%{$search_info}%");
//                    $query->orderBy('customers.name', 'DESC');
                    break;
                case 3:
                    $query->where(function($query) use($search_info){
                        $query->where('city', 'like', "%{$search_info}%")
                            ->orWhere('area', 'like', "%{$search_info}%");
                        return $query;
                    });
//                    $query->orderBy('area', 'DESC');
                    break;
                case 4:
                    $query->where('business_concat_persons.email', 'like', "%{$search_info}%");
                    break;
                default:
                    break;
            }
        }


        if ($request->has('sortBy')) {
            $sortBy = $request->input('sortBy');
            foreach ($sortBy as $q) {
                $query->orderBy($q, 'DESC');
            }
        } else {
            $query->orderBy($sortBy, 'DESC');
        }


        $concat_persons = $query->paginate(15);

        $data = [
            'concat_persons' => $concat_persons,
            'sortBy' => $sortBy,
            'sortBy_text' => $sortBy_text,
            'is_left' => $is_left,
            'want_receive_mail' => $want_receive_mail,
            'status_text' => $status_text,
        ];
        return view('mails.index', $data);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function export(Request $request)
    {
        $sortBy_text = ['創建日期', '姓名', '公司(客戶)', '縣市地區', '在職狀態', '收信意願'];
        $status_text = ['---', '在職', '離職'];
        $is_left = -1;
        $want_receive_mail = -1;
        $sortBy = 'create_date';
        $query = BusinessConcatPerson::query();
        $query->join('customers', 'customers.id', '=', 'business_concat_persons.customer_id');

        $query->select('customers.name as customer_name', 'business_concat_persons.name as name',
            'email', 'is_left', 'business_concat_persons.update_date as update_date',
            'business_concat_persons.create_date as create_date', 'city', 'area', 'want_receive_mail',
            'user_id');

        $search_type = 0;
        $search_info = '';


//        user is not root
        if (Auth::user()->level != 2) {
            $query->where('user_id', '=', Auth::user()->id);
        }


        if ($request->has('is_left')) {
            $is_left = $request->input('is_left');
            if ($is_left >= 0) {
                $query->where('is_left', '=', $is_left);
                $query->orderBy('is_left', 'DESC');
            }
        }


        if ($request->has('want_receive_mail')) {
            $want_receive_mail = $request->input('want_receive_mail');
            if ($want_receive_mail >= 0) {
                $query->where('want_receive_mail', '=', $want_receive_mail);
                $query->orderBy('want_receive_mail', 'DESC');
            }
        }


        if ($request->has('sortBy')) {
            $sortBy = $request->input('sortBy');
            foreach ($sortBy as $q) {
                $query->orderBy($q, 'DESC');
            }
        } else {
            $query->orderBy($sortBy, 'DESC');
        }

        if ($request->has('search_type')) {
            $search_type = $request->input('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->input('search_info');
            switch ($search_type) {
                case 1:
                    $query->where('business_concat_persons.name', 'like', "%{$search_info}%");
                    $query->orderBy('business_concat_persons.name', 'DESC');
                    break;
                case 2:
                    $query->where('customers.name', 'like', "%{$search_info}%");
                    $query->orderBy('customers.name', 'DESC');
                    break;
                case 3:
                    $query->where(function($query) use($search_info){
                        $query->where('city', 'like', "%{$search_info}%")
                            ->orWhere('area', 'like', "%{$search_info}%");
                        return $query;
                    });
                    $query->orderBy('area', 'DESC');
                    break;
                default:
                    break;
            }
        }




        $concat_persons = $query->get(['customers.name']);
//        dd($concat_persons);
        return Excel::download(new InvoicesExport($concat_persons), 'data.xlsx');

    }
}
