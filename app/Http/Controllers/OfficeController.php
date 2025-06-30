<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Office;
use App\State;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OfficeController extends Controller
{
    public function index()
    {
        return view("warehouse.offices.offices");
    }
    public function list()
    {
        try {
            $offices = Office::orderBy("created_at", "desc")->get();
            return DataTables::of($offices)
                ->editColumn("country", function ($row) {
                    return Country::find($row->country)->name;
                })->editColumn("city", function ($row) {
                    $country = Country::find($row->country)->name;
                    if ($country == "Turkey") {
                        return State::find($row->city)->name;
                    } else {
                        return City::find($row->city)->name;
                    }
                })
                ->rawColumns(["action"])
                ->addColumn("action", function ($row) {
                    return $row->id;
                })
                ->make(true);
        } catch (Exception $e) {

            return ['error' => 'Something went wrong'];
        }
    }

    public function addPage()
    {
        $countries = $this->getCountries();

        return view("warehouse.offices.add-office", compact("countries"));
    }

    public function updatePage($id)
    {
        $office = Office::find($id);
        $countries = $this->getCountries();

        return view("warehouse.offices.update-office", compact("office", "countries"));
    }

    public function updateOffice(Request $request)
    {
        // dd($request->all());
        $office = Office::find($request->office_id);
        $office->name = $request->name;
        $office->country = $request->country;
        $office->city = $request->city;
        $office->description = $request->description;
        $office->update();

        $this->addAttachments($request->attachments, $office->id);

        return redirect()->back();
    }
    public function addOffice(Request $request)
    {
        $office = new Office();
        $office->name = $request->name;
        $office->country = $request->country;
        $office->city = $request->city;
        $office->save();

        $this->addAttachments($request->attachments, $office->id);

        return redirect("/offices");
    }

/*    public function deleteOffice(Request $request)
    {
        $office_id = $request->office_id;
        $office = Office::find($office_id);
        $office->delete();
        return response()->json(["success" => 1]);
    }*/

    // Country and Cities
    public function getCountries()
    {
        if (Auth::check()) {
            $countries = Country::all();

            return $countries;
        } else {
            return "";
        }
    }

    public function getCities($country_id)
    {
        if (Auth::check()) {
            $country = Country::find($country_id);
            if ($country->name == "Turkey") {
                $cities = State::where("country_id", $country_id);
            } else {
                $cities = City::where("country_id", $country_id);
            }
            $cities = $cities->selectRaw("id, name as text")->get()->toArray();

            return response()->json($cities);
        } else {
            return "";
        }
    }
}
