<?php

namespace App\Http\Controllers;

use App\Company;
use App\ContractAttachments;
use App\contractPayments;
use App\Contracts;
use App\ContractStatusLog;
use App\Helpers\Helper;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContractsController extends Controller
{
    //
    public function index(Request $request, $owner_company, $contract_type = "")
    {
        $this->control_owner_company($owner_company);
        $companies = $this->get_filtered_companies($owner_company);
        return view(
            'contracts.contracts',
            [
                "owner_company" => $owner_company,
                "companies" => $companies,
                "contract_type" => $contract_type
            ]
        );
    }

    public function addContract($owner_company)
    {
        $this->control_owner_company($owner_company);
        $companies = $this->get_filtered_companies($owner_company);
        return view('contracts.contract-edit',["page_type"=>"add","companies"=>$companies,"owner_company"=>$owner_company]);
    }

    public function addContractData(Request $request,$owner_company)
    {

        $this->control_owner_company($owner_company);
        $request->price = Helper::price_format_to_db($request->price);
        $request->hourprice = Helper::price_format_to_db($request->hourprice??0);
        $request->transportprice_1 = Helper::price_format_to_db($request->transportprice_1??0);
        $request->transportprice_2 = Helper::price_format_to_db($request->transportprice_2??0);
        $request->transportprice_3 = Helper::price_format_to_db($request->transportprice_3??0);

        $request->priceDiscount = Helper::price_format_to_db($request->priceDiscount??0);

        $request->hourPriceDiscount = Helper::price_format_to_db($request->hourPriceDiscount??0);
        $request->transportpriceDiscount_1 = Helper::price_format_to_db($request->transportpriceDiscount_1??0);
        $request->transportpriceDiscount_2 = Helper::price_format_to_db($request->transportpriceDiscount_2??0);
        $request->transportpriceDiscount_3 = Helper::price_format_to_db($request->transportpriceDiscount_3??0);

        if($owner_company=='getucon-de'){
            if($request->type==1){
                $prefix = 'DC-';
                $start = 155001;
            } else if($request->type==2){
                $prefix = 'SYS-';
                $start = 155001;
            } else if($request->type==5){
                $prefix = 'FW-';
                $start = 35501;
            } else if($request->type==3){
                $prefix = 'NS-';
                $start = 45501;
            } else if($request->type==4){
                $prefix = 'WS-';
                $start = 55501;
            }

            $contract = Contracts::where('owner_company',$owner_company)->where('type',$request->type)->orderBy('contractId','DESC')->first();

            if($contract){
                $contractId = explode($prefix,$contract->contractId);
                $contractId = $prefix.($contractId[1]+1);
            } else {
                $contractId = $prefix.$start;
            }
        } else {
            $contractId = $request->contractId;
        }

        $insertData = [
            'oid' => $request->organization,
            'contractId' => $contractId,
            'start' => $request->contract_start,
            'end' => $request->contract_end,
            'price' => $request->price,
            'priceDiscount'=>$request->priceDiscount,
            'type' => $request->type,
            'paymentCycle' => $request->paymentCycle,
            'cid' => $request->cid,
            'description' => $request->description,
            'hourprice' => $request->hourprice,
            'hourPriceDiscount' => $request->hourPriceDiscount ?? 0,
            'transportPrice1' => $request->transportprice_1,
            'transportPriceDiscount1' => $request->transportpriceDiscount_1 ?? 0,
            'transportPrice2' => $request->transportprice_2,
            'transportPriceDiscount2' => $request->transportpriceDiscount_2 ?? 0,
            'transportPrice3' => $request->transportprice_3,
            'transportPriceDiscount3' => $request->transportpriceDiscount_3 ?? 0,
            'personel' => $request->personel,
            'status' => $request->contract_payment_status,
            'reaction_time'=>$request->reaction_time??null,
            'inclusive_hours'=>$request->inclusive_hours??null,
            'owner_company'=>$owner_company
        ];

        $contract = Contracts::create($insertData);

        if ($request->ticketAttachments) {
            foreach ($request->ticketAttachments as $key => $attachment) {
                $ticketAttachment = new ContractAttachments();
                $ticketAttachment->contract_id = $contract->id;
                $ticketAttachment->attachment = $attachment;
                $ticketAttachment->size = $key;
                $ticketAttachment->add_by = auth()->id();
                $ticketAttachment->add_ip = request()->ip();
                $ticketAttachment->save();
            }
        }


        if ($request->paymentprice) {
            if ($request->paymentprice[0]) {
                foreach ($request->paymentprice as $paymentItem => $paymentValue) {
                    $dataRow = [
                        'cid' => $contract->id,
                        'price' => str_replace(",", ".", str_replace(".", "", $request->paymentprice[$paymentItem])),
                        'type' => $request->paymentmethod[$paymentItem],
                        'date' => $request->paymentdate[$paymentItem],
                        'note' => $request->paymentNotes[$paymentItem],
                    ];
                    contractPayments::create($dataRow);
                }
            }
        }


        return redirect('/contracts/'.$owner_company);
    }


    public function list(Request $request,$owner_company)
    {
        $this->control_owner_company($owner_company);
        try {

            $contracts = Contracts::selectRaw('contracts.*,organizations.org_name')
                ->leftJoin('organizations', function ($join) {
                    $join->on('contracts.oid', '=', 'organizations.id');
                })
                ->where("owner_company",$owner_company)
                ->orderBy('id', 'DESC');

            return DataTables::of($contracts)
                ->editColumn('type', function ($row) {
                    if ($row->type === 1) return 'DataCenter';
                    if ($row->type === 2) return 'Support-Service-Maintance';
                    if ($row->type === 3) return 'Non-Service';
                    if ($row->type === 4) return 'Web Contract';
                    if($row->type === 5) return 'Leasing-Firewall';
                    return '';
                })
                ->editColumn('cid', function ($row) {
                    $company = Company::where("id",$row->cid)->first();

                    return $company->name??"-";
                })
                ->addColumn("contract_status",function ($row){
                    if($row->terminated_date!=null){//send data for terminaded date and controll front side
                        return $row->terminated_date;
                    }elseif ($row->upgraded_date != null){//send data for upgraded date and controll front side
                        return  $row->upgraded_date;
                    }
                    else{
                        return null;// Continious
                    }

                })
                ->filterColumn('contract_status', function ($query) use ($request) {
                    if ($request->columns[3]["search"]["value"] == "1") {
                        $query->whereRaw("contracts.terminated_date is null and upgraded_date is null");
                    }
                    if ($request->columns[3]["search"]["value"] == "2") {
                        $query->whereRaw("contracts.terminated_date is not null");
                    }
                    if($request->columns[3]["search"]["value"] == "3"){
                        $query->whereRaw("contracts.upgraded_date is not null");
                    }
                })

                ->editColumn('start', function ($row) {
                    if ($row->start) {
                        return Carbon::parse($row->start)->format('d.m.Y');
                    } else {
                        return '';
                    }
                })
                ->editColumn('end', function ($row) {
                    if ($row->end) {
                        return Carbon::parse($row->end)->format('d.m.Y');
                    } else {
                        return '';
                    }
                })
                ->editColumn('status', function ($row) {
                    if($row->status) {
                        if ($row->status === 1) return ucfirst(trans('words.contact_payment_status_1'));
                        if ($row->status === 2) return ucfirst(trans('words.contact_payment_status_2'));
                    }
                    else{
                        return null;
                    }
                })
                ->editColumn("price",function ($row){
                    return $row->price-($row->price*($row->priceDiscount/100));
                })
                ->addColumn('actions', function ($contracts) {
                    return '<a href="#" data-id="' . $contracts->id . '" class="deleteContract"><i class="fa fa-trash btn btn-danger"></i></a>';
                })->rawColumns(['actions','contract_status'])
                ->filterColumn('org_name', function ($query, $keyword) {
                    $query->whereRaw("organizations.org_name LIKE ?", ["%{$keyword}%"]);
                })
                ->make(true);
        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }

    public function show($owner_company,$id)
    {
        $this->control_owner_company($owner_company);

        $contract = Contracts::selectRaw('contracts.*,organizations.org_name,users.first_name,users.surname')
            ->leftJoin('organizations', function ($join) {
                $join->on('contracts.oid', '=', 'organizations.id');
            })->leftJoin('users', function ($join) {
                $join->on('contracts.personel', '=', 'users.id');
            })->where('contracts.id', $id)->first();

        $payments = contractPayments::where('cid', $id)->get();
        $attachments = ContractAttachments::selectRaw('contract_attachments.*,users.first_name,users.surname')->join('users', 'users.id', 'contract_attachments.add_by')->where('contract_id', $id)->get();

        $companies = $this->get_filtered_companies($owner_company);
        return view('contracts.contract-edit', ["page_type"=>"update",'contract' => $contract, 'payments' => $payments, 'attachments' => $attachments,"companies"=>$companies,"owner_company"=>$owner_company]);
    }

    public function update(Request $request,$owner_company)
    {

        $this->control_owner_company($owner_company);

        $request->price = Helper::price_format_to_db($request->price);

        $request->hourprice = Helper::price_format_to_db($request->hourprice??0);

        $request->transportprice_1 = Helper::price_format_to_db($request->transportprice_1??0);
        $request->transportprice_2 = Helper::price_format_to_db($request->transportprice_2??0);
        $request->transportprice_3 = Helper::price_format_to_db($request->transportprice_3??0);

        $request->priceDiscount = Helper::price_format_to_db($request->priceDiscount??0);

        $request->hourPriceDiscount = Helper::price_format_to_db($request->hourPriceDiscount??0);
        $request->transportpriceDiscount_1 = Helper::price_format_to_db($request->transportpriceDiscount_1??0);
        $request->transportpriceDiscount_2 = Helper::price_format_to_db($request->transportpriceDiscount_2??0);
        $request->transportpriceDiscount_3 = Helper::price_format_to_db($request->transportpriceDiscount_3??0);

        //Eğer contract terminated veya upgraded yapılmak isteniyorsa sadece biri yapılabilir veya ikisi aynı anda deaktif edilebilir
        if($request->terminated!="on"){
            $request->terminated_date = null;
        }
        if($request->upgraded != "on"){
            $request->upgraded_date = null;
        }

        $insertData = [
            'oid' => $request->organization,
            'contractId' => $request->contractId,
            'start' => $request->contract_start,
            'end' => $request->contract_end,
            'price' => $request->price,
            'priceDiscount' => $request->priceDiscount,
            'type' => $request->type,
            'paymentCycle' => $request->paymentCycle,
            'cid' => $request->cid,
            'description' => $request->description,
            'hourprice' => $request->hourprice,
            'hourPriceDiscount' => $request->hourPriceDiscount,
            'transportPrice1' => $request->transportprice_1,
            'transportPriceDiscount1' => $request->transportpriceDiscount_1,
            'transportPrice2' => $request->transportprice_2,
            'transportPriceDiscount2' => $request->transportpriceDiscount_2,
            'transportPrice3' => $request->transportprice_3,
            'transportPriceDiscount3' => $request->transportpriceDiscount_3,
            'personel' => $request->personel,
            'status' => $request->contract_payment_status,
            'reaction_time'=>$request->reaction_time??null,
            'inclusive_hours'=>$request->inclusive_hours??null,
            'terminated_date' => $request->terminated_date !== null ? $request->terminated_date : null,
            'upgraded_date'=>$request->upgraded_date !== null?$request->upgraded_date:null,
            'notwh_weekday'=>$request->notwh_weekday,// wh->working hour , notwh-> not working hour
            'wh_saturday'=>$request->wh_saturday,
            'notwh_saturday'=>$request->notwh_saturday,
            'wh_sunday_holiday'=>$request->wh_sunday_holiday,
            'notwh_sunday_holiday'=>$request->notwh_sunday_holiday,
        ];
//commit
        if ($request->ticketAttachments) {
            foreach ($request->ticketAttachments as $key => $attachment) {
                $ticketAttachment = new ContractAttachments();
                $ticketAttachment->contract_id = $request->id;
                $ticketAttachment->attachment = $attachment;
                $ticketAttachment->size = $key;
                $ticketAttachment->add_by = auth()->id();
                $ticketAttachment->add_ip = request()->ip();
                $ticketAttachment->save();
            }
        }
        $contract = Contracts::where("id",$request->id)->first();
        //contract status u terminated veya upgraded yapıldıysa eski tarihleri loglama yapıyor
        //Örnek : contract terminated ise ve upgraded yapılmak isteniyorsa upgraded tarihi contracts tablosuna kayıt edilir.
        //eski terminated tarihi ise contract status tablosuna kayıt edilir.
        if(($contract->terminated_date != null && $request->terminated != "on") || ($contract->upgraded_date != null && $request->upgraded!="on")){

            $contract_status_log = new ContractStatusLog();
            $contract_status_log->cid = $contract->id;
            $contract_status_log->terminated_date = $contract->terminated_date;
            $contract_status_log->upgraded_date   = $contract->upgraded_date;
            $contract_status_log->add_by          = auth()->id();
            $contract_status_log->save();
        }

        Contracts::where('id', $request->id)->update($insertData);
        contractPayments::where('cid', $request->id)->forceDelete();
        if ($request->paymentprice) {
            foreach ($request->paymentprice as $paymentItem => $paymentValue) {
                $dataRow = [
                    'cid' => $request->id,
                    'price' => str_replace(",", ".", str_replace(".", "", $request->paymentprice[$paymentItem])),
                    'type' => $request->paymentmethod[$paymentItem],
                    'date' => $request->paymentdate[$paymentItem],
                    'note' => $request->paymentNotes[$paymentItem],

                ];
                contractPayments::create($dataRow);
            }
        }

        if($request->save_and_close==0)
            return redirect('/update-contract/'.$owner_company."/".$request->id);
        else
            return redirect("/contracts/".$owner_company);
    }

    public function delete($id)
    {
        try {
            Contracts::where('id', $id)->delete();
            $message = 'Contract deleted successfully';
            return response()->json([
                'status' => 200,
                'message' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'error',
            ]);
        }
    }

    public function questContractId(Request $request, $contractId)
    {

        $contracts = Contracts::where('contractId', $contractId)->get();

        if ($contracts->count()>0) {

            $terminated = $contracts->whereNotNull("terminated_date");
            $upgraded   = $contracts->whereNotNull("upgraded_date");
            $continous = $contracts->whereNull("terminated_date")->whereNull("upgraded_date");

            if($terminated->isNotEmpty() && $continous->isEmpty()){ // eğer contract terminaded edildiyse aynı contract id ile yeni kayıta izin ver

                return response()->json([
                    'status' => 0
                ]);
            }
            elseif($upgraded->isNotEmpty() && $continous->isEmpty()){//eğer contract upgraded edildiyse aynı contract id ile yeni kayıta izin ver
                return response()->json([
                    'status' => 0
                ]);
            }

            else{ // contract devam ediyorsa
                return response()->json([
                    'status' => 1 // status 1 ise izin verme
                ]);

            }

        } else { // gelen isteğe göre contract bulunamadıysa zaten izin ver

            return response()->json([
                'status' => 0
            ]);
        }
    }

    public function removeAttachment($id)
    {

        $success = ContractAttachments::where("id", $id)->delete();

        return response()->json(['status' => $success]);

    }

    public function control_owner_company($owner_company){
        if($owner_company!="getucon-de") {
            abort(404,"Not Found");
        }
    }
    public function get_filtered_companies($owner_company){
        $companies = Company::get();
        if($owner_company == "getucon-de"){
            $companies = $companies->reject(function ($element){
                return $element->id == 1 || $element->main_comp=="getucon-tr";
            });
        }
        return $companies;
    }

    public function getFile($company, $file, $type)
    {
        $filename = "";
        if($company == "getucon" && $file == "backup" && $type == "pdf"){
            $filename = "getucon_Backup.pdf";
        }else if($company == "getucon" && $file == "backup" && $type == "doc"){
            $filename = "getucon_Backup.docx";
        }else if($company == "getucon" && $file == "dsgvo" && $type == "pdf"){
            $filename = "getucon_DSGVO.pdf";
        }else if($company == "getucon" && $file == "dsgvo" && $type == "doc"){
            $filename = "getucon_DSGVO.docx";
        }
        $file_path = public_path("/assets/contracts_files/" . $filename);

        if($type == "doc"){
            return response()->download($file_path);
        }else if($type == "pdf"){
            return response()->file($file_path);
        }
    }

}
