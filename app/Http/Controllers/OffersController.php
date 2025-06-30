<?php

namespace App\Http\Controllers;

use Exception;
use App\Offer;
use App\Company;
use Carbon\Carbon;
use App\OfferData;
use App\OfferStatus;
use App\Organization;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OffersController extends Controller
{
    public function index()
    {

        return view("offers.offers");
    }

    public function create_offer()
    {
        $data['companies'] = Company::all();
        $data['statusses'] = OfferStatus::all();
        return view("offers.add-offer")->with($data);
    }

    public function create_offer_post(Request $request)
    {
        try {
            //code...

            $request->offer_amount = Helper::price_format_to_db($request->offer_amount);
            $offer = new Offer();
            $offer->offer_no = $request->offer_no;
            $offer->company_id = $request->company_id;

            $offer->org_id = $request->org_id;
            $offer->offer_amount = $request->offer_amount;
            $offer->offer_date = $request->offer_date;
            $offer->status = $request->status;
            $offer->additional_info = $request->description;
            $offer->add_by = auth()->id();
            $offer->updated_by = auth()->id();
            $offer->save();

            if ($request->links[0]) {
                $total_index = count($request->links);
                for ($i = 0; $i < $total_index; $i++) {
                    if ($request->links[$i] != "") {
                        $offer_data = new OfferData();
                        $offer_data->offer_id = $offer->id;
                        $offer_data->buying_price = Helper::price_format_to_db($request->buyingPrice[$i]);
                        $offer_data->selling_price = Helper::price_format_to_db($request->sellingPrice[$i]);
                        $offer_data->amount = $request->amounts[$i];
                        $url = $request->links[$i];
                        if (!(str_starts_with($request->links[$i], "http://") or str_starts_with($request->links[$i], "https://"))) {
                            $url = "https://" . $request->links[$i];
                        }

                        $offer_data->link = $url;

                        $offer_data->save();
                    }
                }
            }

            $attachments = $request->attachments;
            $this->addAttachments($attachments, $offer->id);

            return redirect("/offers");
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function update_offer($offer_id)
    {
        $data['companies'] = Company::all();
        $data['statusses'] = OfferStatus::all();
        $offer_datas = OfferData::where("offer_id", $offer_id)->get();
        $total_profit = 0;
        $total_buy = 0;
        $total_sell = 0;

        foreach ($offer_datas as $offer_data) {
            $amount = $offer_data->amount;
            $profit = $offer_data->selling_price - $offer_data->buying_price;

            $total_profit += $profit * $amount;
            $total_buy += $offer_data->buying_price * $amount;
            $total_sell += $offer_data->selling_price * $amount;
        }

        $data["offer_datas"] = $offer_datas;
        $data["total_profit"] = $total_profit;
        $data["total_buy"] = $total_buy;
        $data["total_sell"] = $total_sell;
        $data["offer"] = Offer::find($offer_id);



        return view("offers.update-offer")->with($data);
    }

    public function update_offer_post(Request $request)
    {
        try {
            $request->offer_amount = Helper::price_format_to_db($request->offer_amount);
            $offer = Offer::find($request->offer_id);
            $offer->status = $request->status;
            $offer->additional_info = $request->description;
            $offer->updated_by = auth()->id();
            $offer->save();

            if ($request->links[0]) {
                $total_index = count($request->links);
                for ($i = 0; $i < $total_index; $i++) {
                    if ($request->links[$i] != "") {

                        $offer_data = new OfferData();
                        $offer_data->offer_id = $offer->id;
                        $offer_data->link = $request->links[$i];
                        $offer_data->amount = $request->amounts[$i];
                        $offer_data->buying_price = Helper::price_format_to_db($request->buyingPrice[$i]);
                        $offer_data->selling_price = Helper::price_format_to_db($request->sellingPrice[$i]);

                        $url = $request->links[$i];
                        if (!(str_starts_with($request->links[$i], "http://") or str_starts_with($request->links[$i], "https://"))) {
                            $url = "https://" . $request->links[$i];
                        }
                        $offer_data->link = $url;

                        $offer_data->save();
                    }
                }
            }

            $attachments = $request->attachments;
            $this->addAttachments($attachments, $offer->id);

            return redirect("/update-offer/" . $request->offer_id)->with("success", 1);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect("/update-offer/" . $request->offer_id)->with("success", -1);
        }
    }

    public function list()
    {
        try {
            $offers = Offer::orderBy("created_at", "desc")->get();
            return DataTables::of($offers)
                ->editColumn("customer", function ($row) {
                    $organization = Organization::where("id", $row->org_id)->first()->org_name;
                    return $organization;
                })
                ->editColumn("company", function ($row) {
                    $company = Company::find($row->company_id);

                    return $company->name;
                })
                ->editColumn("offer_date", function ($row) {
                    $offer_date = Carbon::parse($row->offer_date)->format("d.m.Y");
                    return $offer_date;
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

    public function delete_offer($offer_id)
    {

        $offer = Offer::find($offer_id);
        $offer->delete();
        return response()->json(["success" => 1]);
    }


    public function is_offer_number_unique($offer_no)
    {
        $offer = Offer::where("offer_no", $offer_no)->withTrashed()->first();

        if ($offer) {
            return response()->json(["isUnique" => false]);
        } else {
            return response()->json(["isUnique" => true]);
        }
    }



    public function get_offer_data($id)
    {
        $offer_data = OfferData::find($id);

        return response()->json($offer_data);
    }

    public function offer_data_delete(Request $request)
    {
        $offer_id = $request->offer_id;
        $offer_data_id = $request->offer_data_id;
        $offer_data = OfferData::find($offer_data_id);
        $offer_data->delete();

        return $this->regenerate($offer_id);
    }

    public function update_offer_data(Request $request)
    {
        $offer_data = OfferData::find($request->data_id);
        $offer_data->buying_price  = Helper::price_format_to_db($request->buying_price);
        $offer_data->selling_price = Helper::price_format_to_db($request->selling_price);
        $offer_data->amount = $request->amount;
        $url = $request->link;
        if (!(str_starts_with($url, "http://") or str_starts_with($url, "https://"))) {
            $url = "https://" . $request->link;
        }

        $offer_data->link = $url;
        $offer_data->save();

        return $this->regenerate($request->offer_id);
    }

    public function regenerate($offer_id)
    {
        $offer_datas = OfferData::where("offer_id", $offer_id)->get();
        return response()->json($offer_datas);
    }
}
