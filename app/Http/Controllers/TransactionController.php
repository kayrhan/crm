<?php

namespace App\Http\Controllers;

use App\TransactionCategory;
use App\Transactions;
use App\TransactionCompany;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helpers\Helper;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $data["categories"] = TransactionCategory::all();
        $data["companies"]  = TransactionCompany::all();

        $data["months"] = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        $currentMonth = Carbon::now()->month - 1;
        $data["currentMonth"] = $currentMonth;

        return view("transaction.transaction")->with($data);
    }

    public function add(Request $request)
    {
        try {
            $category = TransactionCategory::where('id',$request->category_id)->first();
            if($category){
                $transaction = new Transactions();
                $transaction->transaction_type = $request->transaction_type;
                $transaction->company_id        = $request->company_id;
                $transaction->category_id       = $request->category_id;
                $transaction->amount            = Helper::price_format_to_db($request->amount);
                $transaction->name              = $request->name;
                $transaction->description       = $request->description;
                $transaction->date              = $request->date;
                $transaction->currency          = $request->currency;
                $transaction->add_by            = auth()->id();
                $transaction->updated_by        = auth()->id();
                $transaction->save();
                return response()->json(["success" => 1]);

            }else{
                return response()->json(["success" => 0 , "message"=>'Seçtiğiniz kategori bulunamadı.']);
            }

        } catch (\Exception $e) {
            return response()->json(["success" => 0 , "message"=>'Bir hata meydana geldi!']);
        }
    }

    public function get_data(Request $request)
    {
        $data = Transactions::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {

        try {
            $category = TransactionCategory::where('id',$request->category_id)->first();
            if($category){
                $transaction                      = Transactions::find($id);
                $transaction->category_id         = $request->category_id;
                $transaction->transaction_type    = $request->transaction_type;
                $transaction->amount              = Helper::price_format_to_db($request->amount);
                $transaction->currency            = $request->currency;
                $transaction->name                = $request->name;
                $transaction->description         = $request->description;
                $transaction->date                = $request->date;
                $transaction->updated_by          = auth()->id();
                $transaction->save();
                return response()->json(["success" => 1]);

            }else{
                return response()->json(["success" => 0 , "message"=>'Seçtiğiniz kategori bulunamadı.']);
            }

        } catch (\Exception $e) {
            return response()->json(["success" => 0 , "message"=>'Bir hata meydana geldi!']);
        }
    }


    public function list(Request $request)
    {

        $transactions = Transactions::where("company_id", $request->company_id)->orderBy("created_at", "desc");

        return DataTables::of($transactions)
            ->editColumn("category_id", function ($row) {
                if ($row->transaction_type == 2) {
                    $category = TransactionCategory::where("id", $row->category_id)->where("transaction_type", 2)->first()->name ?? "";
                } else if ($row->transaction_type == 1) {
                    $category = TransactionCategory::where("id", $row->category_id)->where("transaction_type", 1)->first()->name ?? "";
                } else {
                    $category = "";
                }

                return $category;
            })

            ->editColumn("expense_amount", function ($row) {
                if ($row->transaction_type == 2) {
                    return $row->amount;
                } else {
                    return null;
                }
            })
            ->editColumn("income_amount", function ($row) {
                if ($row->transaction_type == 1) {
                    return $row->amount;
                } else {
                    return null;
                }
            })
            ->editColumn("date", function ($row) {

                return $row->date;
            })
            ->editColumn("add_by", function ($row) {
                $user = User::find($row->add_by);
                if ($user)
                    return $user->first_name . " " . $user->surname;
                else
                    return "-";
            })
            ->filterColumn("add_by", function ($q, $k) {

                $user = User::where('first_name', 'like', '%' . $k . '%')->select('id')->pluck('id');
                return $q->whereIn('add_by', $user);
            })
            ->make(true);
    }

    public function get_categories($company_id)
    {

        $categories = TransactionCategory::where("company_id", $company_id)->get();

        return response()->json($categories);
    }
    public function get_categories_raw()
    {
        $categories = TransactionCategory::all();
        return \response()->json($categories);
    }

    public function get_totals($company_id)
    {

        return Helper::calculate_transactions_timeline($company_id);
    }

    public function add_category(Request $request)
    {

        try {
            $category = TransactionCategory::where('name', $request->category_name)->where('transaction_type',$request->transaction_type)->first()?1:0;
            if ($category == 1) {
                return response()->json(["success" => 0,'message' => 'Bu kategori zaten kayıtlı.']);
            }else{
                $transaction_category = new TransactionCategory();
                $transaction_category->name = $request->category_name;
                $transaction_category->transaction_type = $request->transaction_type;
                $transaction_category->company_id = $request->company_id;
                $transaction_category->save();

                return response()->json(["success" => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => 0, "message"=>'Bir hata meydana geldi!']);
        }
    }

    public function update_category(Request $request)
    {
        try {
            $transaction_category = TransactionCategory::find($request->category_id);
            $category = TransactionCategory::where('name', $request->category_name)->where('transaction_type',$transaction_category->transaction_type)->first()?1:0;
            if ($category == 1) {
                return response()->json(["success" => 0,'message' => 'Bu kategori zaten kayıtlı.']);
            }else{
                $transaction_category->name = $request->category_name;
                $transaction_category->save();

                return response()->json(["success" => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => 0, "message"=>'Bir hata meydana geldi!']);
        }
    }

    public function get_transaction_count($category_id)
    {
        $transaction_count = Transactions::where("category_id", $category_id)->get()->count();
        return \response()->json(["transaction_count" => $transaction_count]);
    }
    //"category_id=" + category_id + "&company_id=" + company_id + "&move_to=" + move_to_category + "&_token=" + "{{csrf_token()}}",
    public function move_category(Request $request)
    {

        try {
            $transactions = Transactions::where("category_id", $request->category_id)->where("company_id", $request->company_id)->get();

            foreach ($transactions as $transaction) {
                $transaction->category_id = $request->move_to_category;
                $transaction->save();
            }

            return response()->json(["success" => 1]);
        } catch (\Exception $e) {
            return response()->json(["success" => 0]);
        }
    }

    public function delete_category(Request $request)
    {
        try {
            $category = TransactionCategory::find($request->category_id);
            $category->delete();
            return response()->json(["success" => 1]);
        } catch (\Exception $e) {
            return response()->json(["success" => 0]);
        }
    }
}
