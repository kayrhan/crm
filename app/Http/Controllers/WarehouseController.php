<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Office;
use App\Stock;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class WarehouseController extends Controller
{
    public function index()
    {
        return view("warehouse.stocks.stocks");
    }

    public function addPage()
    {
        $offices = Office::all();
        $stock_id = Stock::count() > 0 ? Stock::latest()->first()->id + 1 : 1;
        $count = 6 - strlen(strval($stock_id));
        for ($i = 0; $i < $count; $i++) {
            $stock_id = "0" . $stock_id;
        }

        return view("warehouse.stocks.add-stock", compact("offices", "stock_id"));
    }

    public function addStock(Request $request)
    {
        $stock = new Stock();
        $stock->office_id = $request->office;
        $stock->brand = $request->brand;
        $stock->model = $request->model;
        $stock->desc_offer = $request->desc_offer;
        $stock->desc_original = $request->desc_original;
        $stock->stock = $request->stock;
        $stock->link = $request->stock_link;
        $stock->save();

        $this->addAttachments($request->attachments, $stock->id);

        return redirect("/stocks");
    }

    public function updatePage($id)
    {
        $stock = Stock::find($id);
        $offices = Office::all();
        $stock_id = $stock->id;
        $count = 6 - strlen(strval($stock_id));
        for ($i = 0; $i < $count; $i++) {
            $stock_id = "0" . $stock_id;
        }

        return view("warehouse.stocks.update-stock", compact("stock", "offices", "stock_id"));
    }

    public function updateStock(Request $request)
    {
        $stock = Stock::find($request->stock_id);
        $stock->office_id = $request->office;
        $stock->brand = $request->brand;
        $stock->model = $request->model;
        $stock->desc_offer = $request->desc_offer;
        $stock->desc_original = $request->desc_original;
        $stock->stock = $request->stock;
        $stock->link = $request->stock_link;
        $stock->update();

        $this->addAttachments($request->attachments, $stock->id);

        return redirect()->back();
    }

    public function deleteStock(Request $request)
    {
        $stock = Stock::find($request->stock_id);
        $stock->delete();
        return response()->json(["success" => 1]);
    }


    public function list()
    {
        try {
            $stocks = Stock::orderBy("id", "desc")->get();
            return DataTables::of($stocks)
                ->editColumn("id", function ($row) {
                    $count = 6 - strlen(strval($row->id));
                    $newID = $row->id;
                    for ($i = 0; $i < $count; $i++) {
                        $newID = "0" . $newID;
                    }
                    return $newID;
                })
                ->editColumn("office_id", function ($row) {
                    $company = Office::find($row->office_id);
                    if ($company) {
                        return ["name" => $company->name, "id" => $company->id];
                    }
                })
                ->editColumn("created_at", function ($row) {
                    $add_date = Carbon::parse($row->created_at)->format("d.m.Y");
                    return $add_date;
                })
                ->addColumn("office_search", function ($row) {
                    $company = Office::find($row->office_id);
                    if ($company) {
                        return $company->name;
                    }
                })
                ->addColumn("action", function ($row) {
                    return $row->id;
                })
                ->rawColumns(["action"])
                ->make(true);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
}
