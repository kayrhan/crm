<?php

namespace App\Http\Controllers;

use App\Exports\HospitalityReceiptPDF;
use App\Helpers\Helper;
use App\Hospitality;
use App\HospitalityVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class HospitalityController extends Controller {

    public function index() {
        return view('hospitality.index');
    }

    public function create() {
        return view('hospitality.create');
    }

    public function store(Request $request) {
        try {
            $hospitality = New Hospitality();

            DB::transaction(function() use($request, $hospitality) {
                $hospitality->host = $request->host;
                $hospitality->place_of_stay = $request->place_of_stay;
                $hospitality->address = $request->address;
                $hospitality->reason = $request->reason;
                $hospitality->date = $request->date;
                $hospitality->day = $request->day;
                $hospitality->receipt_amount = floatval(Helper::price_format_to_db($request->receipt_amount));
                $hospitality->tip = floatval(Helper::price_format_to_db($request->tip));
                $hospitality->total_amount = $hospitality->receipt_amount + $hospitality->tip;
                $hospitality->currency = $request->currency;
                $hospitality->created_by = auth()->id();
                $hospitality->updated_by = auth()->id();
                $hospitality->save();

                foreach($request->visitor as $visitor) {
                    $guest = new HospitalityVisitor();
                    $guest->visitor = $visitor;
                    $guest->hospitality_id = $hospitality->id;
                    $guest->created_by = auth()->id();
                    $guest->updated_by = auth()->id();
                    $guest->save();
                }
            });

            $visitors = HospitalityVisitor::where('hospitality_id', $hospitality->id)->pluck('visitor')->toArray('visitor');
            $this->exportPDF($hospitality, $visitors); // PDF'i yazdırıyoruz.
            return redirect('/hospitality-receipt');
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to create a hospitality!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function edit($id) {
        $hospitality = Hospitality::with('hospitalityVisitors')->where('id', $id)->first();
        return view('hospitality.edit')->with('hospitality', $hospitality);
    }

    public function update(Request $request) {
        $hospitality = Hospitality::where('id', $request->id)->first();

        if($hospitality) {
            DB::transaction(function() use($request, $hospitality) {
                $hospitality->host = $request->host;
                $hospitality->place_of_stay = $request->place_of_stay;
                $hospitality->address = $request->address;
                $hospitality->reason = $request->reason;
                $hospitality->date = $request->date;
                $hospitality->day = $request->day;
                $hospitality->receipt_amount = floatval(Helper::price_format_to_db($request->receipt_amount));
                $hospitality->tip = floatval(Helper::price_format_to_db($request->tip));
                $hospitality->total_amount = $hospitality->receipt_amount + $hospitality->tip;
                $hospitality->currency = $request->currency;
                $hospitality->updated_by = auth()->id();
                $hospitality->save();

                foreach($request->visitor as $key => $value) {
                    $guest = HospitalityVisitor::where('id', $key)->first();
                    $guest->visitor = $value;
                    $guest->hospitality_id = $hospitality->id;
                    $guest->updated_by = auth()->id();
                    $guest->save();
                }

                if($request->new_visitor) {
                    foreach($request->new_visitor as $visitor) {
                        $guest = new HospitalityVisitor();
                        $guest->visitor = $visitor;
                        $guest->hospitality_id = $hospitality->id;
                        $guest->created_by = auth()->id();
                        $guest->updated_by = auth()->id();
                        $guest->save();
                    }
                }

                $visitors = HospitalityVisitor::where('hospitality_id', $hospitality->id)->pluck('visitor')->toArray('visitor');
                $this->exportPDF($hospitality, $visitors); // Tekrar PDF oluşturuyoruz.
            });
        }

        return redirect()->back();
    }

    public function destroy(Request $request) {
        try {
            $hospitality = Hospitality::where('id', $request->id)->first();

            if($hospitality) {
                $hospitality->delete();
                return true;
            }
            else {
                return false;
            }
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete a hospitality!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function exportPDF($hospitality, $visitors) {
        try {
            $document = new HospitalityReceiptPDF();
            $result = $document->createPDF($hospitality, $visitors);
            $name = $hospitality->date . "_" . time() . ".pdf";
            file_put_contents(storage_path('app/uploads/') . $name, $result);

            DB::transaction(function() use($hospitality, $name) {
                $hospitality = Hospitality::where('id', $hospitality->id)->first();
                $hospitality->file_name = $name;
                $hospitality->save();
            });
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to export Hospitality PDF!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function deleteVisitor(Request $request) {
        try {
            $visitor = HospitalityVisitor::where('id', $request->id)->first();

            if($visitor) {
                $hospitality = Hospitality::where('id', $visitor->hospitality_id)->first();
                $visitor->delete();
                $visitors = HospitalityVisitor::where('hospitality_id', $visitor->hospitality_id)->pluck('visitor')->toArray('visitor');
                $this->exportPDF($hospitality, $visitors); // Silme işlemi yapıldıktan sonra tekrar PDF oluşturulması gerekiyor.

                return true;
            }
            else {
                return false;
            }
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete visitor in Hospitality Section!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function getTableData() {
        $hospitality = Hospitality::orderBy('created_at', 'DESC');
        return DataTables::of($hospitality)
            ->addColumn('actions', function($row) {
                return $row->id;
            })
            ->editColumn('visitors', function($row) {
                $visitors = HospitalityVisitor::where('hospitality_id', $row->id)->get();
                $text = null;

                if($visitors) {
                    foreach($visitors as $visitor) {
                        $text .= '<a href="/hospitality-receipt/edit/' . $row->id . '" target="blank">' . $visitor->visitor . '</a><br>';
                    }

                    return $text;
                }
                else {
                    return null;
                }
            })
            ->filterColumn('visitors', function($query, $input) {
                $visitor = HospitalityVisitor::where('visitor', 'LIKE', '%' . $input . '%')->pluck('hospitality_id');
                $query->whereIn('id', $visitor);
            })
            ->filterColumn('total_amount', function($query, $input) {
                $input = Helper::price_format_to_db($input);
                $query->where('total_amount', 'LIKE', '%' . $input . '%');
            })
            ->filterColumn('day', function($query, $input) {
                $query->whereRaw("CONCAT(day, ' Days') LIKE '%". $input . "%'");
            })
            ->filterColumn('host', function($query, $input) {
                $query->where('host', 'LIKE', '%' . $input . '%');
            })
            ->rawColumns(['visitors'])
            ->make(true);
    }
}