<?php

namespace App\Http\Controllers;

use App\AccountingDataTr;
use App\AccountingMailLogTr;
use App\AccountingStornoTr;
use App\AccountingTr;
use App\AccountingTrTicketId;
use App\Company;
use App\Discussion;
use App\Exports\AccountingGulerPDF;
use App\Exports\AccountingMediaKitPDF;
use App\Exports\AccountingTrPDF;
use App\Helpers\Helper;
use App\InvoicePaymentTr;
use App\InvoiceReminderAttachmentsTr;
use App\InvoiceReminderMailDateLog;
use App\InvoiceReminderTr;
use App\Mail\AccountingMailTr;
use App\Mail\InvoiceMailTurkey;
use App\Organization;
use App\Status;
use App\Ticket;
use App\TicketAttachment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AccountingTrController extends Controller {

    public function index($ref_company, $type) {
        $data["type"] = $type;
        $data["ref_company"] = $ref_company;
        $company = Company::query()->where("route_name", $ref_company)->firstOrFail();
        $data["company"] = $company;

        if($type == "offer") {
            return view("getucon-accounting-tr.index",$data);
        }
        else if($type == "invoice") {
            $calculated_payments = [];
            $query = AccountingTr::query()->where("type", "invoice")->where("owner_company", $ref_company)->where("is_cancel", "No");
            $first_invoice = ($query->min('date')) ?? date("Y"); // Tarihsel olarak en erken kesilmiş faturanın tarihini alıyoruz.
            $minimum_year = substr($first_invoice, 0, 4); // İlk faturanın yılını ayırıyoruz.

            for($year = $minimum_year; $year <= (int)date("Y"); $year++) {
                $calculated_payments[] = $year;
            }

            $calculated_payments[] = "All";

            $data["calculated_payments"] = $calculated_payments;
            $data["contracted_customers"] = Organization::query()->where("is_contracted", 1)->get();

            return view("getucon-accounting-tr.index", $data);
        }
        else {
            return redirect("/");
        }
    }

    public function paymentMonitoring($ref_company){

        $calculated_payments = [];
        $query = AccountingTr::query()->where("type", "invoice")->where("owner_company", $ref_company)->where("is_cancel", "No");
        $first_invoice = ($query->min('date')) ?? date("Y"); // Tarihsel olarak en erken kesilmiş faturanın tarihini alıyoruz.
        $minimum_year = substr($first_invoice, 0, 4); // İlk faturanın yılını ayırıyoruz.

        for($year = $minimum_year; $year <= (int)date("Y"); $year++) {
            $calculated_payments[] = $this->getTotal($year, $ref_company); // Faturaların ödenmiş, ödenmemiş ve toplam tutarlarını yıl yıl hesaplıyoruz.
        }

        $invoices = $query->pluck('no')->toArray();
        $total_invoice_amount = AccountingTr::query()->whereIn("no", $invoices)->sum("total_amount");
        $total_unpaid_amount = AccountingTr::query()->whereIn("no", $invoices)->sum("unpaid_payment");
        $total_payments = InvoicePaymentTr::query()->whereIn("invoice_no", $invoices)->sum("payment_amount");

        $calculated_payments[] = [
            "year" => "All",
            "total" => $total_invoice_amount,
            "payments" => $total_payments,
            "unpaid" => $total_unpaid_amount
        ];
        return response($calculated_payments);

    }

    public function add(Request $request, $ref_company, $type) {
        $company = Company::query()->where("route_name", $ref_company)->firstOrFail();

        if($type == "invoice") {
            $data["type_text"] = "Invoice";
        }
        else if($type == "offer") {
            $data["type_text"] = "Offer";
        }
        else {
            return redirect("/");
        }
        $data["ticket_id_count"] = 0;
        $data["company"] = $company;
        $data["page_type"] = "add";
        $data["type"] = $type;
        $data["ref_company"] = $ref_company;
        $data["companies"] = Company::query()->where("main_comp", $ref_company)->get();

        if($request->ref_no != null) { // Eğer "Offer" üzerinden "Fatura" kesilmek isteniyorsa mantığı burada kuruyoruz.
            $accounting = AccountingTr::query()->where("no", $request->ref_no)->where("owner_company", $ref_company)->with("ticketIds")->first();

            if($accounting) { // Referans numarasına karşılık gelen kayıt var mı?
                if($request->copy != 1) { // Fatura kopyalanmak isteniyor mu?
                    if($type == "invoice") { // Eğer referans ile "Invoice" fatura oluşturulmak isteniyorsa mantığını burada kuruyoruz.
                        if($accounting->proforma_no) { // Eğer "Offer" hali hazırda bir "Invoice" referansı içeriyorsa, "Offer" ile tekrar bir "Invoice" oluşturulmasına izin verme.
                            return redirect("/accounting-tr/update/" . $ref_company . "/offer/" . $accounting->id)->withErrors(["msg" => "This offer (" . $accounting->no . ") already reference an invoice (" . $accounting->proforma_no . ")!"]);
                        }
                    }

                    $data["isCopy"] = false;
                }
                else { // Fatura sadece kopya olarak eklenmek isteniyorsa burayı kullanıyoruz. (Tamamen girdi alanlarını otomatik olarak doldursun, zamandan tasarruf olsun diye.)
                    $data["isCopy"] = true;
                }

                $data["ref_no"] = $request->ref_no;
                $data["accounting"] = $accounting;
                $data["accounting_datas"] = AccountingDataTr::query()->where("accounting_id", $accounting->id)->orderBy("pos")->get();
                $data["org_name"] = $accounting->getCustomerName->org_name;
                $data["company_name"] = $accounting->getCompanyName->name;
                $data["ticket_name"] = $accounting->ticket_id!=null?($accounting->getTicket->name??"-"):null;
                $ticketIds[]=$accounting->ticket_id;

                if($accounting->ticketIds->count() > 0) {
                    $data["ticket_id_count"] = $accounting->ticketIds->count()+1;
                    foreach ($accounting->ticketIds as $ticketId){
                        $data["tickets"][] =[
                            "id"=>$ticketId->ticket_id,
                            "ticket_name"=> Ticket::where("id",$ticketId->ticket_id)->first()->name??"-",
                        ];
                        $ticketIds[]=$ticketId->ticket_id;
                    }

                }else{
                    $data["ticket_id_count"] = 0;
                }

                $ticket = Ticket::query()->whereIn("id",$ticketIds)->where(function ($query) {
                    $query->where("status_id","!=",6)->orWhere("proofed",0);
                })->first();
                if($ticket) {
                    $data["tickets_status_ok"]=0;
                }else{
                    $data["tickets_status_ok"]=1;
                }

            }
        }

        if($request->ticket_id){
            $ticket = Ticket::query()->findOrFail($request->ticket_id);
            $data["org_name"] = $ticket->organization->org_name;
            $data["customer_id"] = $ticket->org_id;
            $data["ticket_id"] = $request->ticket_id;
        }


        return view("getucon-accounting-tr.invoice-export-edit", $data);
    }

    public function update($ref_company,$type,$id) {
        $accounting = AccountingTr::query()->where("owner_company", $ref_company)->with("ticketIds")->find($id);
        $company = $this->route_control($ref_company);

        if($type == "invoice") {
            foreach(explode(";", $accounting->gta_amounts) as $amount) {
                $data["gta"]["amounts"][] = $amount;
            }

            foreach(explode(";", $accounting->gta_create_date) as $date) {
                $data["gta"]["dates"][] = $date;
            }

            foreach(explode(";", $accounting->official_invoice_number) as $number) {
                $data["gta"]["numbers"][] = $number;
            }

            $data["current_reminder"] = InvoiceReminderTr::query()->where("invoice_no", $accounting->no)->first();
            $data["customer"] = Organization::query()->find($accounting->customer_id);
            $data["type_text"] = "Invoice";
            $data["total_payments"] = InvoicePaymentTr::query()->where("invoice_no", $accounting->no)->sum("payment_amount");
            $data["payment_logs"] = InvoicePaymentTr::query()->where("invoice_no", $accounting->no)->orderBy("payment_date", "DESC")->orderBy("created_at", "DESC")->get();

            if($data["current_reminder"]) {
                $data["deadline_logs"] = InvoiceReminderMailDateLog::query()->where("reminder_id", $data["current_reminder"]->id)->where("de_or_tr", 2)->get();
            }
        }
        else if($type == "offer") {
            $data["type_text"] = "Offer";
        }

        $accounting->org_name = Organization::query()->find($accounting->customer_id)->org_name;
        $accounting_datas = AccountingDataTr::query()->where("accounting_id", $accounting->id)->orderBy("pos")->get();

        if($accounting->type == "invoice") {
            $data["mail_status"] = $accounting->is_cancel === "Yes" ? 0 : 2; // Mail bileşenin gösterimini bu değişkenle kontrol edeceğiz. Eğer aynı tip mail gönderildiyse kapat. 0: Disabled, 1: Enabled, 2: Reminder.
            $reminder = $this->isReminderSet($accounting->no,$ref_company);

            if($reminder) {
                $data["attachments"] = InvoiceReminderAttachmentsTr::query()->where("reminder_id", $reminder->id)->get();
                $accounting->reminder_setted = Carbon::parse($reminder->created_at)->format("d.m.Y [H:i:s]");
                $accounting->reminder_id = $reminder->id;
            }
            else {
                $data["attachments"] = [];
            }

            if($accounting->offer_no) {
                $offer = AccountingTr::query()->where("no",$accounting->offer_no)->first();
                $accounting->reference_offer = $offer;
            }

            if($accounting->is_cancel === "Yes") {
                $data["mail_status"] = AccountingMailLogTr::query()->where("type", "storno")->where("no", $accounting->storno_no)->first() ? 0 : 1; // Eğer fatura iptal edilmişse ve iptal mail'i gönderilmediyse buradan kontrol sağlıyoruz.
                $storno = AccountingStornoTr::query()->where("no", $accounting->storno_no)->first();
                $accounting->storno = $storno;
            }
        }
        else if($accounting->type == "offer") {
            if($accounting->proforma_no) {
                $data["mail_status"] = 0; // Eğer fatura kesildiyse teklif faturası gönderememeli.
                $proforma = AccountingTr::query()->where("no", $accounting->proforma_no)->first();
                $accounting->reference_proforma = $proforma;
            }
            else {
                $data["mail_status"] = AccountingMailLogTr::query()->where("type", "offer")->where("no", $accounting->no)->first() ? 0 : 1;
            }
        }

        $numbers = []; // Empty Array
        array_push($numbers, $accounting->no, $accounting->invoice_no, $accounting->proforma_no, $accounting->offer_no, $accounting->storno_no); // Muhasebe numaralarını Mail Log'larını kontrol etmek için alıyoruz.

        $data["mail_logs"] = AccountingMailLogTr::query()->whereIn("no", $numbers)->get();
        $data["page_type"] = "update";
        $data["type"] = $type;
        $data["accounting"] = $accounting;
        $data["accounting_datas"] = $accounting_datas;
        $data["company"] = $company;
        $data["companies"] = Company::query()->where("main_comp", $ref_company)->get();
        $data["ticket_name"] = $accounting->ticket_id != null ? ($accounting->getTicket->name ?? "-") : null;

        $data["tickets"]=[];
        $data["tickets_status_ok"]=0;

        if($accounting->ticket_id){
            $ticketIds[]=$accounting->ticket_id;

            if($accounting->ticketIds->count() > 0) {
                foreach ($accounting->ticketIds as $ticketId){
                    $data["tickets"][] =[
                        "id"=>$ticketId->ticket_id,
                        "ticket_name"=> Ticket::where("id",$ticketId->ticket_id)->first()->name??"-",
                    ];
                    $ticketIds[]=$ticketId->ticket_id;
                }

            }

            $ticket = Ticket::query()->whereIn("id",$ticketIds)->where(function ($query) {
                $query->where("status_id","!=",6)->orWhere("proofed",0);
            })->first();
            if($ticket) {
                $data["tickets_status_ok"]=0;
            }else{
                $data["tickets_status_ok"]=1;
            }
        }


        return view("getucon-accounting-tr.invoice-export-edit",$data);
    }

    public function add_post(Request $request, $ref_company, $type) {
        try {
            $organization = Organization::query()->find($request->organization);
            $accounting = new AccountingTr();
            $prefix = "";

            if(!in_array($type, ["offer", "invoice"])) {
                abort(404);
            }

            if($type == "invoice") {
                $prefix = match($ref_company) {
                    "guler-consulting" => "GC-",
                    "media-kit" => "MK-",
                    default => "GT-",
                };
            }
            elseif($type == "offer") {
                $prefix = "OF-";
            }

            $max = AccountingTr::withTrashed()->selectRaw("MAX(CAST(SUBSTRING_INDEX(no,'-',-1) AS UNSIGNED )) as max")->where("type", $type)->where("owner_company", $ref_company)->first();
            $max = $max->max;

            if($max == null) {
                if($type == "offer") {
                    $number = match($ref_company) {
                        "guler-consulting" => $prefix . 384910,
                        "media-kit" => $prefix . 148787,
                        default => $prefix . 453411,
                    };
                }
                if($type == "invoice") {
                    $number = match($ref_company) {
                        "guler-consulting" => $prefix . 583410,
                        "media-kit" => $prefix . 789423,
                        default => $prefix . 985856,
                    };
                }
            }
            else {
                $number = $prefix . ($max + 1); // değilse max number ı al
            }

            DB::transaction(function() use($request, $type, $organization, $accounting, $number, $ref_company) {
                $accounting->customer_id = $organization->id;
                $accounting->customer_no = $organization->customer_no;
                $accounting->type = $type;
                $accounting->no = $number;
                $accounting->editor = $request->editor;
                $accounting->amount = 0;
                $accounting->title = $request->title;
                $accounting->footnote = $request->footnote;
                $accounting->company_id  = $request->company_id;
                $accounting->delivery_date = $request->delivery_date;
                $accounting->deadline = $request->deadline;
                $accounting->deadline_day = $request->deadline_day;
                $accounting->date = $request->date;
                $accounting->add_by = auth()->id();
                $accounting->updated_by = auth()->id();
                $accounting->ticket_id = $request->ticket_id;
                $accounting->internal_info = $request->internal_info;
                $accounting->kdv = intval($request->kdv);
                $accounting->repeat_date = $request->repeat_date;
                $accounting->repeat_reminder = $request->repeat_reminder;
                $accounting->owner_company = $ref_company;

                if($type === "invoice") {
                    $accounting->is_cancel = "No";
                }

                $accounting->save();
                $total_amount = 0;

                foreach($request->items as $item) {
                    $accounting_item = new AccountingDataTr();
                    $accounting_item->quantity = $item["quantity"];
                    $accounting_item->accounting_id = $accounting->id;
                    $accounting_item->add_by = auth()->id();
                    $accounting_item->description = $item["description"];
                    $accounting_item->pos = $item["position"];
                    $accounting_item->quantity_type = $item["type"];
                    $accounting_item->discount = floatval(Helper::price_format_to_db($item["discount"]));
                    $accounting_item->total_price = floatval(Helper::price_format_to_db($item["total_price"]));
                    $accounting_item->unit_price = floatval(Helper::price_format_to_db($item["unit_price"]));
                    $accounting_item->updated_by = auth()->id();
                    $accounting_item->save();
                    $total_amount += $accounting_item->total_price;
                }

                $accounting->amount = $total_amount;
                $accounting->unpaid_payment = round(($total_amount + ($total_amount * ($accounting->kdv / 100))), 2);
                $accounting->total_amount = round(($total_amount + ($total_amount * ($accounting->kdv / 100))), 2);

                if($request->reference_type == "offer") { // Referans ile ekleme yapıyorsak burayı kullanıyoruz.
                    $accounting->offer_no = $request->reference_no;
                    $offer = AccountingTr::query()->where("no", $accounting->offer_no)->where("owner_company", $ref_company)->first();

                    if($type == "invoice") {
                        $offer->proforma_no = $accounting->no;
                    }

                    $offer->save();
                }

                $accounting->save();
            });

            $this->export_pdf($accounting); // PDF oluşturuyoruz.

            if($request->ticket_count && $request->ticket_count > 0){
                for ($i=2;$i<=$request->ticket_count;$i++){
                    $ticketId = "ticket_id".$i;
                    if($request->$ticketId){
                        AccountingTrTicketId::updateOrCreate([
                            "accounting_id" => $accounting->id,
                            "ticket_id" => $request->$ticketId
                        ]);
                    }
                }
            }

            return redirect("/accounting-tr/update/" . $ref_company . "/" . $type . "/" . $accounting->id);
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to create an Accounting in Turkey's companies!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine());

            return response(["error" => "error"]);
        }
    }

    public function update_post(Request $request, $ref_company, $type, $id) {
        try {
            $accounting = AccountingTr::query()->where("owner_company",$ref_company)->find($id);
            $official = $this->assignOfficial($request);

            if($type == "invoice") { // Eğer "Invoice" bölümündeysek "Official Invoice ve Repeat Date" bölümünü burada güncelliyoruz.
                $accounting->gta_amounts = $official["gta_amounts"];
                $accounting->gta_create_date = $official["gta_create_dates"];
                $accounting->official_invoice_number = $official["official_invoice_numbers"];
                $accounting->repeat_date = $request->repeat_date;
                $accounting->repeat_reminder = $request->repeat_reminder;
            }

            $accounting->internal_info = $request->internal_info;
            $accounting->updated_by = auth()->id();
            $accounting->save();

            if(!$request->isDisabledAll) {
                if($type == "offer") {
                    $offer = AccountingTr::query()->where("owner_company", $ref_company)->find($id);

                    if($offer->proforma_no) {
                        return redirect()->back()->withErrors(["msg" => "This offer reference to the proforma PF-" . $offer->proforma_no . " so,update process cannot be performed."]);
                    }
                }
                elseif($type == "invoice") {
                    $reminder = InvoiceReminderTr::query()->where("invoice_no", $accounting->no)->where("owner_company", $ref_company)->first();

                    if($reminder) { // Eğer hatırlatıcı kurulmuşsa müşteriye mail çıktığı için güncellemeye izin verme.
                        return redirect()->back()->withErrors(["msg" => "There is a robot reminder set on this proforma." . $accounting->no . " so,update process cannot be performed."]);
                    }
                }

                DB::transaction(function() use($request, $id, $accounting, $type) {
                    $official = $this->assignOfficial($request);

                    if($type == "invoice") { // Eğer "Invoice" bölümündeysek "Official Invoice" bölümünü burada güncelliyoruz.
                        $accounting->gta_amounts = $official["gta_amounts"];
                        $accounting->gta_create_date = $official["gta_create_dates"];
                        $accounting->official_invoice_number = $official["official_invoice_numbers"];
                    }

                    $accounting->editor = $request->editor;
                    $accounting->title = $request->title;
                    $accounting->footnote = $request->footnote;
                    $accounting->delivery_date = $request->delivery_date;
                    $accounting->deadline = $request->deadline;
                    $accounting->deadline_day = $request->deadline_day;
                    $accounting->kdv = intval($request->kdv);
                    $accounting->date = $request->date;
                    $accounting->updated_by = auth()->id();
                    $accounting->repeat_date = $request->repeat_date;
                    $accounting->repeat_reminder = $request->repeat_reminder;
                    $accounting->save();
                    $current_accounting_items = AccountingDataTr::query()->where("accounting_id", $accounting->id)->pluck("id")->toArray();
                    $accounting_items_final = [];
                    $total_amount = 0;

                    foreach($request->items as $item) {
                        if(isset($item["item_id"])) { // Eğer halihazırda bulunan bir "İtem" güncellenmeye çalışıyorsa bulmaya çalışacağız.
                            try {
                                $accounting_item = AccountingDataTr::query()->findOrFail($item["item_id"]);
                            }
                            catch(Exception $exception) {
                                $accounting_item = new AccountingDataTr();
                                $accounting_item->accounting_id = $accounting->id;
                                $accounting_item->add_by = auth()->id();
                            }
                        }
                        else {
                            $accounting_item = new AccountingDataTr();
                            $accounting_item->accounting_id = $accounting->id;
                            $accounting_item->add_by = auth()->id();
                        }

                        $accounting_item->pos = $item["position"];
                        $accounting_item->description = $item["description"];
                        $accounting_item->quantity = $item["quantity"];
                        $accounting_item->quantity_type = $item["type"];
                        $accounting_item->discount = floatval(Helper::price_format_to_db($item["discount"]));
                        $accounting_item->unit_price = floatval(Helper::price_format_to_db($item["unit_price"]));
                        $accounting_item->total_price = floatval(Helper::price_format_to_db($item["total_price"]));
                        $accounting_item->updated_by = auth()->id();
                        $accounting_item->save();
                        $accounting_items_final[] = $accounting_item->id;
                        $total_amount += $accounting_item->total_price;
                    }

                    $accounting->amount = $total_amount;
                    $accounting->total_amount = round(($total_amount + ($total_amount * ($accounting->kdv / 100))), 2);
                    $to_be_deleted_items = array_diff($current_accounting_items, $accounting_items_final);

                    foreach($to_be_deleted_items as $item) {
                        $accounting_item = AccountingDataTr::query()->find($item);
                        $accounting_item?->delete();
                    }

                    if($accounting->type == "invoice") {
                        $payments = InvoicePaymentTr::query()->where("invoice_no", $accounting->no)->sum("payment_amount");
                        $accounting->unpaid_payment = round(($accounting->total_amount - $payments), 2);
                    }
                    else {
                        $accounting->unpaid_payment = $accounting->total_amount;
                    }

                    $accounting->save();
                });

                if(!$accounting->ticket_id) {
                    $accounting->ticket_id = $request->ticket_id;
                    $accounting->save();
                }

                if($request->ticket_count && $request->ticket_count > 0){
                    for ($i=2;$i<=$request->ticket_count;$i++){
                        $ticketId = "ticket_id".$i;
                        if($request->$ticketId){
                            AccountingTrTicketId::updateOrCreate([
                                "accounting_id" => $accounting->id,
                                "ticket_id" => $request->$ticketId
                            ]);
                        }

                    }
                }


                $this->export_pdf($accounting);
            }

            if($request->save_and_close == 1) {
                return redirect("/accounting-tr/" . $ref_company . "/" . $type);
            }

            return redirect("/accounting-tr/update/" . $ref_company . "/" . $type . "/" . $accounting->id);
        }
        catch (\Exception $e){
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to update an accounting data in Turkey's Companies!",
                9,
                $e->getMessage() . " Line:" . $e->getLine()
            );

            return redirect()->back();
        }
    }

    public function list(Request $request,$ref_company,$type) {
        $accountings = AccountingTr::query()->where("type", $type)->where("owner_company", $ref_company)->orderByRaw("substring(no,4) desc");

        if($request->status == "blacklist") { // Hatırlatıcısı "Blacklist" statüsünde olan faturaları ayıklamak için mantığı burada kuruyoruz.
            $reminders = InvoiceReminderTr::query()->where('status', 3)->where('owner_company', $ref_company)->pluck('invoice_no')->toArray(); // "Blacklist" olan hatırlatıcıların fatura numaralarını aldık.
            $accountings->whereIn("no", $reminders);
        }

        if($request->status == "unpaid") { // Ödenmemiş tutarı bulunan faturaları burada ayıklıyoruz.
            $invoices = AccountingTr::query()->where("type", "invoice")->where("owner_company", $ref_company)->where("is_cancel", "No")->get(); // İptal faturalarını getirmemeli.
            $invoice_amounts = [];
            $unpaid_invoices = [];

            foreach($invoices as $invoice) {
                $invoice_amounts[$invoice->no] = $invoice->total_amount;
            }

            foreach($invoice_amounts as $key => $value) { // Her faturanın tutarını, kendi ödemeleriyle karşılaştıracağız.
                $paid_amount = InvoicePaymentTr::where('invoice_no', $key)->sum('payment_amount');

                if($paid_amount != $value) { // Fatura tutarı, ödenen tutar ile eşit değilse burada aksiyon alıyoruz.
                    $unpaid_invoices[] = $key;
                }
            }

            $accountings->whereIn("no", $unpaid_invoices);
        }

        if(!($request->organization === "all-customers")) {
            $accountings->where("customer_id", $request->organization);
        }

        $accountings->get();

        return DataTables::of($accountings)
            ->addColumn("customer",function($row) {
                $organization = Organization::where("id",$row->customer_id)->first();
                return $organization->org_name;
            })
            ->addColumn("company",function ($row){
                return Company::where("id",$row->company_id)->first()->name;
            })
            ->addColumn("actions",function ($row){
                return $row->id;
            })
            ->addColumn("add_by",function ($row){
                $user = User::where("id",$row->add_by)->first();
                return $user->first_name. " " .$user->surname;
            })
            ->addColumn('is_mail_send', function($row) use($type, $ref_company) {
                if($type == "offer") {
                    $mail_log = AccountingMailLogTr::query()->where("type", "offer")->where("no", $row->no)->first();

                    if($mail_log) {
                        return "Yes";
                    }
                    else {
                        return "No";
                    }
                }
                else {
                    $robot = InvoiceReminderTr::where('invoice_no', $row->no)->first();
                    if($robot) {
                        if($robot->status == 1) {
                            return "<span class='text-primary'>Running</span>";
                        }
                        else {
                            return "<span class='text-primary'>Not Running</span>";
                        }
                    }
                    else {
                        return "Not Set";
                    }
                }
            })
            ->addColumn('received_payments', function ($row) use($type){
                if($type == "invoice") {
                    return InvoicePaymentTr::where('invoice_no', $row->no)->sum('payment_amount'); // Modal'da göstermek için o faturaya yapılmış toplam tutar bilgisini dönüyoruz.
                }
            })
            ->addColumn('reminder', function($row) use($type) {
                if($type == "invoice") {
                    $reminder = InvoiceReminderTr::where('invoice_no', $row->no)->first();
                    if($reminder) {
                        return $reminder->id;
                    }
                    else {
                        return null;
                    }
                }
            })
            ->addColumn('is_cancel', function($row) use($type) {
                if($type === "offer") {
                    $proforma = AccountingTr::query()->where("no", $row->proforma_no)->first();

                    if($proforma) {
                        return $proforma->is_cancel;
                    }
                    else {
                        return null;
                    }
                }
                else {
                    return $row->is_cancel;
                }
            })
            ->editColumn("unpaid_payment", function($row) use($type) {
                if($type == "invoice") {
                    return $row->is_cancel === "Yes" ? null : number_format($row->unpaid_payment, 2, ",", ".");
                }
            })->editColumn("ticket_id", function($row) use($type) {
                if($row->ticket_id){
                    $ticketIds = [];
                    $ticketIds[] = $row->ticket_id;

                    $otherTicketIds = AccountingTrTicketId::where("accounting_id", $row->id)
                        ->pluck("ticket_id")
                        ->toArray();

                    $ticketIds = array_merge($ticketIds, $otherTicketIds);
                }else{
                    $ticketIds = null;
                }

                return $ticketIds;
            })
            ->editColumn('official_invoice', function($row) use($ref_company) {
                if(($row->type == "offer" && $row->proforma_no) || $row->type == "invoice") {
                    if($row->official_invoice_number) {
                        $numbers = explode(";", $row->official_invoice_number);
                        $text = "";
                        foreach($numbers as $value) {
                            $text .= "<a href='/accounting-tr/update/" . $ref_company . "/" . $row->type . "/" . $row->id . "' target='_blank'>" . $value . "</a><br>";
                        }

                        return $text;
                    }
                    else {
                        return null;
                    }
                }
                else {
                    return null;
                }
            })
            ->filterColumn('official_invoice', function($query, $input) {
                $query->where('official_invoice_number', 'LIKE', '%' . $input . '%')->select('id')->pluck('id');
            })
            ->filterColumn('customer', function($query, $input) {
                $organizations = Organization::where('org_name', 'LIKE', '%' . $input . '%')->select('id')->pluck('id');
                $query->whereIn('customer_id', $organizations);
            })
            ->filtercolumn('no',function ($query, $input) {
                $query->where('no', 'LIKE', '%' . $input . '%');
            })
            ->filterColumn('total_amount', function($query, $input) {
                $input = Helper::price_format_to_db($input);
                $query->where('total_amount', 'LIKE', '%' . $input . '%');
            })
            ->filterColumn("unpaid_payment", function($query, $input) {
                $input = Helper::price_format_to_db($input);
                $query->where("unpaid_payment", "LIKE", "%" . $input . "%");
            })
            ->filterColumn('company', function($query, $input) {
                $companies = Company::where('name', 'LIKE', '%' . $input . '%')->select('id')->pluck('id');
                $query->whereIn('company_id', $companies);
            })
            ->filterColumn('add_by', function($query, $input) {
                $users = User::whereRaw("CONCAT(first_name,' ',surname) LIKE '%" . $input . "%'")->select('id')->pluck('id');
                $query->whereIn('add_by', $users);
            })
            ->editColumn('title', function($row) {
                $data =[
                    "title"=>html_entity_decode(strip_tags($row->title)),
                    "htmlTitle"=>$row->title
                ];
                return $data;
            })
            ->filterColumn('is_mail_send', function($query, $input) use($type) {
                if($type == "offer") {
                    $mail_logs = AccountingMailLogTr::query()->where("type", "offer")->select("no")->pluck("no");
                    if($input == 1) {
                        $query->whereIn("no", $mail_logs);
                    }
                    else {
                        $query->whereNotIn("no", $mail_logs);
                    }
                }
                else {
                    $invoice_robots = InvoiceReminderTr::query();
                    if($input == 1) {
                        $invoice_robots = $invoice_robots->where('status', 1)->select('invoice_no')->pluck('invoice_no');
                        $query->whereIn('no', $invoice_robots);
                    }
                    elseif($input == 2) {
                        $invoice_robots = $invoice_robots->where('status', 2)->select('invoice_no')->pluck('invoice_no');
                        $query->whereIn('no', $invoice_robots);
                    }
                    elseif($input == 3) {
                        $invoice_robots = $invoice_robots->select('invoice_no')->pluck('invoice_no');
                        $query->whereNotIn('no', $invoice_robots);
                    }
                }
            })
            ->filterColumn("repeat_date", function($query, $input) {
                if($input === "true") {
                    $query->whereNotNull("repeat_date");
                }
                else {
                    $query;
                }
            })
            ->rawColumns(["customer","company","actions","add_by","official_invoice","is_mail_send"])
            ->make(true);
    }

    public function export_pdf($accounting, $is_storno = false) {
        try {
            $accounting_datas = AccountingDataTr::query()->where("accounting_id", $accounting->id)->orderBy("pos")->get();

            foreach($accounting_datas as $accounting_data) {
                if($is_storno) {
                    $accounting_data->total_price = $accounting_data->total_price * -1;
                    $accounting_data->unit_price = $accounting_data->unit_price * -1;
                }

                if($accounting_data->discount != 0) {
                    $accounting_data->discount = number_format($accounting_data->discount, 2, ",", ".");
                }
                else { // Discount "0" ise PDF'e "0" yazdırmaması gerekiyor.
                    $accounting_data->discount = "";
                }
            }

            $accounting->date = Carbon::parse($accounting->date)->format("d.m.Y");
            $accounting->delivery_date = Carbon::parse($accounting->delivery_date)->format("d.m.Y");
            $accounting->deadline = Carbon::parse($accounting->deadline)->format("d.m.Y");
            $accounting->kdv_amount = ($accounting->amount * ($accounting->kdv / 100));

            if($is_storno) {
                $accounting->amount = $accounting->amount * -1;
                $accounting->kdv_amount = $accounting->kdv_amount * -1;
                $accounting->total_amount = $accounting->total_amount * -1;
            }

            $accounting->amount = number_format($accounting->amount,2,",",".");
            $accounting->total_amount = number_format($accounting->total_amount, 2, ",", ".");
            $accounting->kdv_amount = number_format($accounting->kdv_amount,2,",",".");
            $accounting->accounting_datas = $accounting_datas;
            $data["accounting"] = $accounting;
            $organization = Organization::query()->select(["id", "org_name", "customer_no", "address", "zip_code", "city", "owner_firstname", "owner_lastname"])->where("id", $accounting->customer_id)->first();
            $organization->organization_admin = ($organization->owner_firstname ?? "") . " " . ($organization->owner_lastname ?? "");
            $data["organization"] = $organization ;
            $data["company"] = Company::query()->find($accounting->company_id);

            if($accounting->owner_company == "getucon-tr") {
                $pdf = new AccountingTrPDF();
            }
            if($accounting->owner_company == "guler-consulting") {
                $pdf = new AccountingGulerPDF();
            }
            if($accounting->owner_company == "media-kit") {
                $pdf = new AccountingMediaKitPDF();
            }

            $output = $pdf->create_pdf($data);

            if($is_storno) {
                $filename = $accounting->storno_no . "_" . time() . ".pdf";
            }
            else {
                $filename = $accounting->no . "_" . time() . ".pdf";
            }

            file_put_contents(storage_path("app/uploads/").$filename,$output);

            DB::transaction(function() use($accounting, $filename, $is_storno) {
                if($is_storno) {
                    $storno = AccountingStornoTr::query()->where("no", $accounting->storno_no)->firstOrFail();
                    $storno->filename = $filename;
                    $storno->save();
                }
                else {
                    Storage::delete("/uploads/" . $accounting->filename); // Dosyalar biriksin istemiyoruz.
                    $accounting = AccountingTr::query()->find($accounting->id);
                    $accounting->filename = $filename;
                    $accounting->save();
                }
            });
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to export an accounting's PDF in Turkey's companies! Accounting Number: " . $accounting->no,
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function isReminderSet($invoice_no,$ref_company){
        $reminder = InvoiceReminderTr::where("invoice_no",$invoice_no)->where("owner_company",$ref_company)->first();
        //reminder robotu kuruldu ise tekrar robot kurulması yapılamayacak
        if($reminder) {
            return $reminder;
        }
        else {
            return null;
        }
    }

    public function getRequestedAccounting(Request $request,$ref_company) {
        $files = [];//dropzone a gönderilecek dosyaları toplamak için
        if($request->accounting_type == "offer") {
            $accounting = AccountingTr::where("type", "offer")->where("no", $request->accounting_no)->where("owner_company",$ref_company)->first();

            if($accounting) {
                $organization = Organization::where("id",$accounting->customer_id)->first();
                $files[] = $accounting->filename;
            }
        }
        else if($request->accounting_type == "invoice") {
            $accounting = AccountingTr::query()->where("type", "invoice")->where("no", $request->accounting_no)->where("owner_company", $ref_company)->first();

            if($accounting) {
                $organization = Organization::where("id",$accounting->customer_id)->first();
                $storno = AccountingStornoTr::query()->where("no", $accounting->storno_no)->first();
                $files[] = $storno->filename;
            }
        }
        else {
            return \response()->json(["success",0]);
        }

        return \response()->json(["success"=>1,"accounting"=>$accounting,"organization"=>$organization,"files"=>$files]);

    }

    public function send_email(Request $request,$ref_company){
        try {
            $accounting = AccountingTr::where("type",$request->accounting_type)->where("no",$request->accounting_no)->where("owner_company",$ref_company)->first();

            if($request->ticket_id) {
                $accounting->ticket_id = $request->ticket_id;
                $accounting->save();
            }
            $data["accounting"] = $accounting;

            /*GET ATTACHMENTS */
            $files = json_decode($request->input("files"));
            $attachments = [];
            $log_file_names="";
            foreach ($files as $file) {
                $attachments[] = $file->value;
                $log_file_names.=$file->value.";";
            }
            $data["attachments"] = $attachments;
            /*GET ATTACHMENTS END */

            /*GET TO MAIL*/
            $customer_mail = env("TEST_MAIL",explode(";",$request->email_to));

            /*GET CC AND BCC*/
            if($request->email_cc) {
                $data["cc"] = explode(";",$request->email_cc);
            }
            else {
                $data["cc"] = null;
            }
            if($request->email_bcc) {
                $data["bcc"] = explode(";",$request->email_bcc);
                $data["bcc"][] = "cg@getucon.de";
                $data["bcc"][] = "si@getucon.de";
                $data["bcc"][] = "ta@getucon.de";
                $data["bcc"] = array_unique($data["bcc"]);
            }
            else {
                $data["bcc"] = ["cg@getucon.de","si@getucon.de", "ta@getucon.de"];
            }

            if(env("TEST_MAIL")) {
                $data["bcc"] = ["ay@getucon.de"];
            }
            /*GET ADDITIONAL TEXT*/
            if($request->additional_text=="<p><br></p>"){
                $data["additional_text"] = null;

                $request->additional_text=null;
            }
            else {
                $data["additional_text"] = $request->additional_text;
            }

            $data["subject"] = $request->subject;
            try {
                if($ref_company == "getucon-tr") {
                    $mailer = env("MAIL_GETUCON_MAILER");
                }
                if($ref_company == "guler-consulting") {
                    $mailer = env("MAIL_GULER_ACCOUNTING_MAILER");
                }
                if($ref_company == "media-kit") {
                    $mailer = env("MAIL_MEDIA_KIT_MAILER");
                }

                Mail::mailer($mailer)->to($customer_mail)->send(new AccountingMailTr($data));
            }
            catch (\Exception $e) {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Accounting mail not sent ! Accounting no ".$accounting->no??"-",
                    9,
                    $e->getMessage()
                );

                return ["Error"];
            }

            /*CREATE MAIL LOG*/
            $mail_log = new AccountingMailLogTr();
            $mail_log->no = $accounting->type === "invoice" ? $accounting->storno_no : $accounting->no;
            $mail_log->type = $accounting->type === "invoice" ? "storno" : $accounting->type;
            $mail_log->send_by = auth()->id();
            $mail_log->subject = $request->subject;
            $mail_log->email_to = $request->email_to;
            $mail_log->email_cc = $request->email_cc;
            $mail_log->email_bcc = $request->email_bcc;
            $mail_log->files     = $log_file_names;
            $mail_log->additional_text = $request->additional_text;
            $mail_log->save();
            if(($accounting->type == "offer" || $accounting->type == "invoice") && $accounting->ticket_id) {
                $this->create_discussion($accounting, $mail_log);
            }
            return response()->json(["success"=>1]);
        }
        catch (\Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Accounting mail log not saved! Accounting no ".$accounting->no??"-",
                9,
                $e->getMessage()
            );

            return response()->json(["success"=>0]);
        }
    }

    public function create_discussion($accounting,$mail_log){
        $text = "[Mail sent by ". $mail_log->getUser->first_name." ".$mail_log->getUser->surname."] on ". Carbon::parse($mail_log->created_at)->format("d.m.Y [H:i:s]")."<br>";
        $text .= "Subject: ".$mail_log->subject."<br>"."To: ".$mail_log->email_to."<br>";

        if($mail_log->email_cc){
            $text.="Cc: ".$mail_log->email_cc."<br>";
        }
        if($mail_log->email_bcc){
            $text.="Bcc: ".$mail_log->email_bcc."<br>";
        }
        if($mail_log->additional_text){
            $text.="Additional Text: ".$mail_log->additional_text."<br>";
        }

        $discussion = new Discussion();
        $discussion->message = $text;
        $discussion->user_id = auth()->id();
        $discussion->is_private = 0;
        $discussion->ticket_id = $accounting->ticket_id;
        $discussion->org_id = auth()->user()->org_id;
        $discussion->save();
        $ticket = Ticket::where("id",$discussion->ticket_id)->first();
        if(!in_array($ticket->status_id,[7,9,10])) { // invoiced closed correction after invoiced
            $ticket->due_date = Carbon::now()->addDays(7)->format("Y-m-d");
            $ticket->save();
            Helper::update_ticket_status($ticket->id, 5); // set question
        }

        $files = explode(";",substr($mail_log->files,0,-1));

        foreach ($files as $file) {
            $attachment = new TicketAttachment();
            $attachment->ticket_id = $accounting->ticket_id;
            $attachment->discussion_id = $discussion->id;
            $attachment->attachment = $file;
            $size = filesize(storage_path("app/uploads/").$file);
            $attachment->size = $size;
            $attachment->add_by = auth()->id();
            $attachment->private = 0;
            $attachment->save();
        }
    }

    public function get_ticket($ticket_id){

        $ticket = Ticket::where("id",$ticket_id)->first();

        if($ticket) {
            return response()->json(["ticket_name"=>$ticket->name,"ticket_id"=>$ticket->id]);
        }
    }

    public function quest_no($ref_company,$no){

        $accounting = AccountingTr::where("no",$no)->where("owner_company",$ref_company)->first();

        if($accounting){
            return response()->json(["status"=>1]);
        }
        else{
            return response()->json(["status"=>0]);
        }
    }

    private function route_control($ref_company){
        return Company::where("route_name",$ref_company)->firstOrFail();
    }

    public function getTotal($year, $ref_company) { // Her bir yıldaki toplam, ödenmiş ve ödenmemiş fatura tutarını bulmak için bu fonksiyonu kullanıyoruz.
        $query = AccountingTr::query()->where("type", "invoice")->where("owner_company", $ref_company)->where("date", "LIKE", date($year) . "%")->where("is_cancel", "No");
        $invoice_numbers = $query->pluck("no")->toArray(); // Sorgu ile uyumlu olarak fatura numaralarını alarak Array'e basıyoruz. Bunları altta fatura ödemelerini ayıklarken kullanacağız.
        $total_amount = $query->sum("total_amount");
        $unpaid_amount = $query->sum("unpaid_payment");
        $paid_amount = InvoicePaymentTr::query()->whereIn("invoice_no", $invoice_numbers)->sum("payment_amount");

        return [
            "year" => $year,
            "total" => $total_amount,
            "payments" => $paid_amount,
            "unpaid" => $unpaid_amount
        ];
    }

    public function receivePayment(Request $request) {
        try {

            DB::transaction(function() use($request) {
                if($request->received_payments) {

                    $paid_amount = 0;
                    $old_payments = InvoicePaymentTr::query()->where('invoice_no', $request->invoice_number)->whereNull('deleted_at')->get();
                    foreach ($old_payments as $old_payment) {
                        $paid_amount += $old_payment->payment_amount;
                    }

                    $invoice = AccountingTr::query()
                        ->where('type', 'invoice')
                        ->where('no', $request->invoice_number)
                        ->whereNull('storno_no')
                        ->whereNull('deleted_at')
                        ->first();

                    $unpaid_amount = $invoice->total_amount - ($paid_amount + Helper::price_format_to_db($request->received_payments));

                    $invoice->unpaid_payment = $unpaid_amount;
                    $invoice->save();



                    $request->received_payments = Helper::price_format_to_db($request->received_payments);

                    $payment = new InvoicePaymentTr();
                    $payment->invoice_no = $request->invoice_number;
                    $payment->payment_amount = $request->received_payments;
                    $payment->payment_date = $request->payment_date;
                    $payment->add_by = auth()->id();
                    $payment->save();

                }

                if($request->payment_status == 2) { // Eğer gelen istekteki "payment_status" iki ise fatura tutarının tamamı ödenmiş demektir. Bunu script'te hesaplıyoruz. Bu durumda da Reminder'ın kapanması gerekiyor.
                    $data["status"] = 2; // Disabled yapıyoruz.
                    InvoiceReminderTr::where('invoice_no', $request->invoice_number)->update($data); // Robotu kapatıyoruz.
                }
            });
        }
        catch(\Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to receive a payment in Turkey's Invoice Section!",
                9,
                $e->getMessage() . " Line:" . $e->getLine()
            );

            return redirect('/tickets');
        }
    }

    public function deletePayment(Request $request) {
        try {
            if(auth()->user()->role_id == 1) { // Sadece Super Admin ödeme silme işlemi yapabilir.

                $payment = InvoicePaymentTr::where('id', $request->payment_id)->first(); // İlgili ödemenin ID'sini alıyoruz.

                $invoice = AccountingTr::query()
                    ->where('type', 'invoice')
                    ->where('no', $payment->invoice_no)
                    ->whereNull('storno_no')
                    ->whereNull('deleted_at')
                    ->first();

                $invoice->unpaid_payment += $payment->payment_amount;
                $invoice->save();

                $payment->delete();

                $reminder = InvoiceReminderTr::where('invoice_no', $payment->invoice_no)->first(); // Eğer ödemenin Reminder'ı kapalıysa, ödeme silindiği için aktif hale getiriyoruz.
                if($reminder) { // Reminder'ın varlığını kontrol ediyoruz.
                    if($reminder->status == 2) { // 2: Disabled, 1: Enabled
                        $data["status"] = 1;
                        $reminder->update($data);
                    }
                }
                return true;
            }
            else {
                return false;
            }
        }
        catch(\Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to delete a payment in Turkey's Invoice Section!",
                9,
                $e->getMessage() . " Line:" . $e->getLine()
            );
        }
    }

    public function paymentHistory($invoice_number) { // Bu bölüm, Modal'da ödeme geçmişini göstermek için yazılmıştır.
        try {
            $payments = InvoicePaymentTr::where('invoice_no', $invoice_number)->orderBy('payment_date', 'DESC')->orderBy('created_at', 'DESC')->get();
            $data = [];
            foreach($payments as $index => $value) {
                $user = User::where('id', $value->add_by)->first();

                $data[$index]["payment_time"] = Carbon::parse($value->payment_date)->format("d.m.Y");
                $data[$index]["payment_id"] = $value->id;
                $data[$index]["created_by"] = $user->first_name . " " . $user->surname;
                $data[$index]["payment_amount"] = (number_format($value->payment_amount, 2, ',', '.')) . ' €';
                $data[$index]["creation_time"] = Carbon::parse($value->created_at)->format("d.m.Y H:i:s");
            }

            return $data;
        }
        catch(\Exception $e) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to retrieve a Turkey's invoice's payment history!",
                9,
                $e->getMessage() . " Line:" . $e->getLine()
            );
        }
    }

    public function retrieveInvoiceDetails(Request $request) {
        try {
            $accounting = AccountingTr::query()
                ->where("type", "invoice")
                ->where("no", $request->accounting_no)
                ->with("ticketIds")->first();
            $ticket_status_ok = 1;
            $ticket_file = null;

            if($accounting) {
                $files[] = $accounting->filename;

                if($accounting->ticket_id) {
                    $ticket_status_ok = 0;
                    $ticket = Ticket::query()->where("id", $accounting->ticket_id)->firstOrFail();
                    $subject = "Invoice: " . $accounting->no . " | #" . $ticket->id . " | " . Str::of($ticket->name)->limit(31, "...");

                    $ticketIds=[];
                    $ticketIds[]=$ticket->id;
                    foreach ($accounting->ticketIds as $ticketId) {
                        $ticketIds[]=$ticketId->ticket_id;
                    }

                    $ticket = Ticket::query()->whereIn("id",$ticketIds)->where(function ($query) {
                        $query->where("status_id","!=",6)->orWhere("proofed",0);
                    })->first();

                    if($ticket){
                        $ticket_status_ok = 0;
                    }else{
                        $ticket_status_ok = 1;
                        $ticket_report = $this->getReportSummary($ticketIds);
                        $ticket_file = $ticket_report["file"];
                    }

                }
                else {
                    $subject = "Invoice: " . $accounting->no . " | " . Str::of(strip_tags($accounting->title))->limit(31, "...");
                }

                return response()->json([
                    "success" => 1,
                    "subject" => $subject,
                    "files" => $files,
                    "ticket_status_ok" => $ticket_status_ok,
                    "ticket_file" => $ticket_file,
                ]);
            }
            else {
                return response()->json([
                    "success" => 0
                ]);
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to retrieve a Turkey's invoice details!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function deleteAttachment(Request $request) { // Bu fonksiyon, hatırlatıcı bölümündeki ek'leri silmek için yazılmıştır.
        try {
            $attachment = InvoiceReminderAttachmentsTr::where('id', $request->attachment_id)->first();
            if($attachment) {
                $attachment->delete();
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
                "Something went wrong while trying to remove an attachment in Invoices' Reminder Section!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function setReminder(Request $request) {
        try {
            $reminder_data = [
                'invoice_no' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'deadline' => $request->reminder_deadline,
                'cid' => $request->cid,
                'oid' => $request->oid,
                'status' => $request->reminder_status,
                'mail_text_0' => $request->invoice_mail_text,
                'mail_text_1' => $request->first_reminder_text,
                'mail_text_2' => $request->second_reminder_text,
                'mail_text_3' => $request->third_reminder_text,
                'day' => $request->reminder_day,
                'owner_company' => $request->ref_company,
                'subject' => $request->subject,
                'cc' => $request->cc,
                'bcc' => $request->bcc,
                'add_by' => auth()->id()
            ];

            if(in_array($request->reminder_status, [1,2])) {
                DB::transaction(function() use($reminder_data, $request) {
                    if($request->invoice_reminder_id) { // Eğer hali hazırda bir hatırlatıcı var ise onu güncelle.
                        $reminder = InvoiceReminderTr::find($request->invoice_reminder_id);

                        if($reminder->deadline != $request->reminder_deadline) { // Hatırlatıcı'nın Deadline'ı ve Request Deadline farklı ise mantığı burada kuruyoruz.
                            $this->resetDeadline($request, $reminder);

                            if($reminder->status == 3) { // Eğer hatırlatıcı "Blacklist" statüsünde ise ve tarih değiştiyse hatırlatıcı aktif olur.
                                $reminder_data["status"] = 1;
                            }

                            $reminder_data['post_mail_1'] = null;
                            $reminder_data['post_mail_2'] = null;
                            $reminder_data['post_mail_3'] = null;
                            $reminder_data['post_mail_4'] = null;
                        }

                        $reminder->update($reminder_data);
                        $this->saveReminderAttachment($request, 1);
                    }
                    else { // Hatırlatıcı bulamazsa yeni bir tane oluştur. Aynı zamanda fatura maili de buradan çıkacak.
                        $this->sendInvoiceMail($request);
                        $new_reminder = InvoiceReminderTr::create($reminder_data);
                        $this->saveReminderAttachment($request, 0, $new_reminder->id);
                        $accounting = AccountingTr::query()->where("no", $request->invoice_number)->with("ticketIds")->first();

                        if($accounting->ticket_id) {

                            self::commentInvoiceCreation($accounting->ticket_id, $request->reminder_attachments, $request->invoice_number); // Fatura'nın kesildiğine dair ticket'a yorum düşeceğiz. Kod tekrarından kaçınmak için başka controller'den metodu alıyoruz.
                            foreach ($accounting->ticketIds as $ticketId) {
                                if($ticketId){
                                    self::commentInvoiceCreation($ticketId->ticket_id, $request->reminder_attachments, $request->invoice_number); // Fatura'nın kesildiğine dair ticket'a yorum düşeceğiz. Kod tekrarından kaçınmak için başka controller'den metodu alıyoruz.
                                }
                            }

                        }
                    }

                    if($request->received_payments) {
                        $this->receivePayment($request);
                    }
                });
            }
            else {
                Helper::create_debug_log(
                    __CLASS__,
                    __FUNCTION__,
                    "Invalid 'Reminder Status' on Turkey's Reminder Section!",
                    9,
                    "Not an Exception!"
                );
            }

            return redirect('/accounting-tr/' . $request->ref_company . '/invoice');
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to set an Invoice Reminder in Turkey's Companies Section!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function assignOfficial($request) {
        $official_invoice_number = "";
        $gta_create_dates = "";
        $gta_amounts = "";

        if($request->official_invoice_number) {
            $len = count($request->official_invoice_number);

            for($i=0; $i < $len; $i++) {
                if($request->official_invoice_number[$i]) {
                    $official_invoice_number .= $request->official_invoice_number[$i] . ";";
                    $gta_create_dates .= $request->gta_create_date[$i] . ";";
                    $gta_amounts .= $request->gta_amounts[$i] . ";";
                }
            }
        }

        $data["official_invoice_numbers"] = rtrim($official_invoice_number,";");
        $data["gta_create_dates"] = rtrim($gta_create_dates,";");
        $data["gta_amounts"] = rtrim($gta_amounts,";");

        return $data;
    }

    public function checkOfficialInvoice(Request $request) { // Resmi fatura'nın daha önce kontrol edilip edilmediğini burada kontrol ediyoruz.
        $official = AccountingTr::whereRaw("official_invoice_number like '%" . $request->official_number . "%'")->withTrashed()->first(); // Silinmiş olanları da kontrol etmek önemli bir nokta.

        if($official) {
            return response()->json(["status" => 1]);
        }
        else {
            return response()->json(["status" => 0]);
        }

    }

    public function sendInvoiceMail($request) {
        $accounting = AccountingTr::query()->where("type", "invoice")->where("no", $request->invoice_number)->first();
        $customer_mail = env("TEST_MAIL", $request->customer_email);

        $attachments = [];
        $log_file_names="";

        foreach($request->reminder_attachments as $key => $value) {
            $attachments[] = $value;
            $log_file_names .= $value . ";";
        }

        $data["accounting"] = $accounting;
        $data["attachments"] = $attachments;

        if($request->cc) {
            $data["cc"] = explode(";",$request->cc);
        }
        else {
            $data["cc"] = null;
        }

        $defaults = ["cg@getucon.de","md@getucon.de","si@getucon.de", "ta@getucon.de"];

        if($request->bcc) {
            $data["bcc"] = explode(";",$request->bcc);
        }

        foreach($defaults as $default) {
            $data["bcc"][] = $default;
        }
        $data["bcc"] = array_unique($data["bcc"]);

        if(env("TEST_MAIL")) { // Test Ortamı İçin
            $data["bcc"] = ["ey@getucon.de"];
        }

        if($request->invoice_mail_text == "<p><br></p>") {
            $data["additional_text"] = null;
            $request->invoice_mail_text = null;
        }
        else {
            $data["additional_text"] = $request->invoice_mail_text;
        }

        $data["subject"] = $request->subject;
        $data["company"] = $request->ref_company;

        try {
            if($request->ref_company == "getucon-tr") {
                $mailer = env("MAIL_GETUCON_MAILER", "");
            }
            if($request->ref_company == "guler-consulting") {
                $mailer = env("MAIL_GULER_ACCOUNTING_MAILER", "");
            }
            if($request->ref_company == "media-kit") {
                $mailer = env("MAIL_MEDIA_KIT_MAILER", "");
            }

            Mail::mailer($mailer)->to($customer_mail)->send(new InvoiceMailTurkey($data));
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to send Turkey Invoice Mail. Invoice NO:" . $accounting->no ?? "-",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }

        $mail_log = new AccountingMailLogTr();
        $mail_log->no = $accounting->no;
        $mail_log->type = $accounting->type;
        $mail_log->send_by = auth()->id();
        $mail_log->subject = $request->subject;
        $mail_log->email_to = $request->customer_email;
        $mail_log->email_cc = $request->cc;
        $mail_log->email_bcc = $request->bcc;
        $mail_log->files     = $log_file_names;
        $mail_log->additional_text = $request->invoice_mail_text;
        $mail_log->save();
    }

    public function resetDeadline($request, $reminder) {
        $old_dates = [$reminder->post_mail_1, $reminder->post_mail_2, $reminder->post_mail_3, $reminder->post_mail_4];
        $first = 1;

        foreach($old_dates as $old_date) {
            if($old_date != null) {
                $log = new InvoiceReminderMailDateLog();
                $log->reminder_id = $reminder->id;
                $log->de_or_tr = 2; // Türkiye
                $log->old_mail_date = $old_date;

                if($first == 1) {
                    $log->changed_deadline = $request->reminder_deadline;
                    $log->changed_by = auth()->id();
                    $first = 0;
                }

                $log->save();
            }
        }
    }

    public function saveReminderAttachment($request, $reminder_status, $reminder_id = null) {
        if($reminder_status == 0) { // Yeni bir hatırlatıcı oluşturuluyorsa hatırlatıcının eklerini burada kaydediyoruz.
            if($request->reminder_attachments) {
                foreach($request->reminder_attachments as $key => $attachment) {
                    $reminder_attachment = new InvoiceReminderAttachmentsTr();
                    $reminder_attachment->reminder_id = $reminder_id;
                    $reminder_attachment->attachment = $attachment;
                    $reminder_attachment->size = $key;
                    $reminder_attachment->add_by = auth()->id();
                    $reminder_attachment->add_ip = $request->ip();
                    $reminder_attachment->save();
                }
                if($request->reminder_attachments_ticket){
                    foreach($request->reminder_attachments_ticket as $reminder_ticket_attachment) {
                        File::move(storage_path('app/tempfiles/' . $reminder_ticket_attachment), storage_path('app/uploads/' . $reminder_ticket_attachment));
                    }
                }
            }
        }
        elseif($reminder_status == 1) { // Halihazırda mevcut bir hatırlatıcı güncelleniyorsa ekleri burada ayarlıyoruz.
            if($request->reminder_attachments) {
                foreach($request->reminder_attachments as $key => $attachment) {
                    $reminder_attachment = new InvoiceReminderAttachmentsTr();
                    $reminder_attachment->reminder_id = $request->invoice_reminder_id;
                    $reminder_attachment->attachment = $attachment;
                    $reminder_attachment->size = $key;
                    $reminder_attachment->add_by = auth()->id();
                    $reminder_attachment->add_ip = $request->ip();
                    $reminder_attachment->save();
                }
                if($request->reminder_attachments_ticket){
                    foreach($request->reminder_attachments_ticket as $reminder_ticket_attachment) {
                        File::move(storage_path('app/tempfiles/' . $reminder_ticket_attachment), storage_path('app/uploads/' . $reminder_ticket_attachment));
                    }
                }
            }
        }
        else { // Geçersiz bir kullanım olursa hata kaydı oluşturuyoruz.
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Invalid 'Reminder Status' passed to the Attachments on Turkey's Companies!",
                9,
                "Not an exception!"
            );
        }
    }

    public function cancelInvoice(Request $request) {
        try {
            $maximum = AccountingStornoTr::query()->max("no");

            if(substr($maximum, 3) === "0" || $maximum === null) {
                if($request->reference_company === "getucon-tr") {
                    $number = "ST-" . 248795;
                }
                else {
                    $number = "ST-" . 345879;
                }
            }
            else {
                $number_without_prefix = intval(substr($maximum, 3)) + 1;
                $number = "ST-" . $number_without_prefix;
            }

            $invoice = AccountingTr::query()->where("no", $request->invoice_no)->firstOrFail();
            $offer = AccountingTr::query()->where('proforma_no', $request->invoice_no)->first();
            DB::transaction(function() use($request, $invoice, $offer, $number) {
                $storno = new AccountingStornoTr();
                $storno->no = $number;
                $storno->invoice_no = $invoice->no;
                $storno->reason = $request->reason;
                $storno->save();

                if($offer) {
                    $offer->is_cancel = "Yes";
                    $offer->storno_no = $number;
                    $offer->save();
                }

                $invoice->is_cancel = "Yes";
                $invoice->storno_no = $number;
                $invoice->save();
                $reminder = InvoiceReminderTr::query()->where("invoice_no", $invoice->no)->first();

                if($reminder) {
                    $reminder->status = 2;
                    $reminder->save();
                }

                $invoice->storno_reason = $storno->reason;
                return $invoice;
            });

            $invoice->date = Carbon::parse(Carbon::now())->format("d.m.Y");
            $this->export_pdf($invoice, true);
            $PDF = AccountingStornoTr::query()->where("invoice_no", $invoice->no)->value("filename");
            $this->sendCancellationInvoiceMail($PDF, $invoice->no, $request->to, $request->cc, $request->bcc);

            if($invoice->ticket_id) {
                $this->commentInvoiceCancellation($invoice->ticket_id, $PDF, $invoice->no);
            }

            return response(["success" => 1]);
        }
        catch(\Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to cancel an Invoice on Turkey!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function sendCancellationInvoiceMail($PDF, $invoice_number, $to, $cc, $bcc) {
        try {
            $storno_number = AccountingStornoTr::query()->where("invoice_no", $invoice_number)->value("no");
            $subject = "Storno: " . $storno_number;
            $accounting = AccountingTr::query()->where("type", "invoice")->where("no", $invoice_number)->first();
            $data["attachments"] = [];
            $data["accounting"] = $accounting;
            $data["attachments"][] = $PDF;
            $customer_mail = env("TEST_MAIL", explode(";", $to));

            if($cc) {
                $data["cc"] = explode(";", $cc);
            }
            else {
                $data["cc"] = null;
            }

            $default_bcc = ["cg@getucon.de", "si@getucon.de", "ta@getucon.de"];

            if($bcc) {
                $data["bcc"] = explode(";", $bcc);
            }

            foreach($default_bcc as $d_bcc) {
                $data["bcc"][] = $d_bcc;
            }

            $data["bcc"] = array_unique($data["bcc"]);

            if(env("TEST_MAIL")) {
                $data["bcc"] = ["ay@getucon.de"];
            }

            $data["additional_text"] = null;
            $data["subject"] = $subject;

            if($accounting->owner_company == "guler-consulting") {
                $mailer = env("MAIL_GULER_ACCOUNTING_MAILER");
            }
            elseif($accounting->owner_company == "getucon-tr") {
                $mailer = env("MAIL_GETUCON_MAILER");
            }
            elseif($accounting->owner_company == "media-kit") {
                $mailer = env("MAIL_MEDIA_KIT_MAILER");
            }

            Mail::mailer($mailer)->to($customer_mail)->send(new AccountingMailTr($data));

            $mail_log = new AccountingMailLogTr();
            $mail_log->type = "storno";
            $mail_log->no = $storno_number;
            $mail_log->send_by = auth()->id();
            $mail_log->subject = $subject;
            $mail_log->email_to = is_array($customer_mail) ? implode(";", $customer_mail) : $customer_mail;
            $mail_log->email_cc = $cc;
            $mail_log->email_bcc = implode(";", $data["bcc"]);
            $mail_log->files = $PDF;
            $mail_log->additional_text = null;
            $mail_log->save();

            return response()->json([
                "success" => 1
            ]);
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to send a cancellation invoice mail!",
                9,
                $exception->getMessage() . " Line" . $exception->getLine()
            );
        }
    }

    public function commentInvoiceCreation($ticket_id, $attachments, $invoice_number) {
        try {
            $comment = new Discussion();
            $comment->message = "An Invoice " . $invoice_number . " for this ticket has just been created and sent.<br><br>";
            $comment->user_id = 206; // Ticket Robot
            $comment->ticket_id = $ticket_id;
            $comment->org_id = 8; // Ticket Robot'un organizasyonu, getucon Management & Technology
            $comment->save();

            foreach($attachments as $key => $value) {
                $attachment = new TicketAttachment();
                $attachment->ticket_id = $ticket_id;
                $attachment->discussion_id = $comment->id;
                $attachment->attachment = $value;
                $attachment->size = $key;
                $attachment->add_by = 206;
                $attachment->private = 0;
                $attachment->save();
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to comment of an invoice creation!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }

    public function changeTicketStatus(Request $request) {
        try {
            $accounting = AccountingTr::query()->findOrFail($request->id);
            $ticket = Ticket::query()->findOrFail($accounting->ticket_id);

            if($ticket->status_id === 6 && $ticket->proofed === 1) {
                $ticket->status_id = 7;
                $ticket->save();

                return [
                    "status" => "Success",
                    "message" => "Ticket status has successfully changed!"
                ];
            }
            else {
                return [
                    "status" => "Error",
                    "message" => "Ticket's status is not Done & Proofed!"
                ];
            }
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to change ticket status from accounting module!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );

            return [
                "status" => "Error",
                "message" => "Something went wrong!"
            ];
        }
    }

    public function commentInvoiceCancellation($ticket_id, $storno_pdf, $invoice_number) {
        try {
            $comment = new Discussion();
            $comment->message = "An Invoice Cancellation " . $invoice_number . " for this ticket has just been cancelled! <br><br>";
            $comment->user_id = 206; // Ticket Robot
            $comment->ticket_id = $ticket_id;
            $comment->org_id = 8; // Ticket Robot'un organizasyonu, getucon Management & Technology
            $comment->save();

            $attachment = new TicketAttachment();
            $attachment->ticket_id = $ticket_id;
            $attachment->discussion_id = $comment->id;
            $attachment->attachment = $storno_pdf;
            $attachment->size = filesize(storage_path("app/uploads/") . $storno_pdf);
            $attachment->add_by = 206;
            $attachment->private = 0;
            $attachment->save();
        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to comment an invoice cancellation on Turkey's Accounting!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
    public function updateTicket(Request $request)
    {
        $ticket=Ticket::query()
            ->where("id",$request->ticket_id)
            ->first();
        $accounting= AccountingTr::query()->where("id",$request->accounting_id)->first();
        if($ticket && $accounting){
            $accounting->ticket_id =$request->ticket_id;
            $accounting->save();
            return response()->json(["message"=>"success"]);
        }else{
            return response()->json(["message"=>"error"]);
        }

    }

    public function deleteMainTicket(Request $request)
    {
        $accounting=AccountingTr::query()->where("id",$request->accounting_id)->first();
        if($accounting){
            $accounting->ticket_id =null;
            $accounting->save();
            return response()->json(["message"=>"success"]);
        }else{
            return response()->json(["message"=>"error"]);
        }

    }
    public function deleteTicket(Request $request)
    {
        $ticketId=AccountingTrTicketId::query()
            ->where("ticket_id",$request->ticket_id)
            ->where("accounting_id",$request->accounting_id)->first();

        if($ticketId){
            $ticketId->delete();
            return response()->json(["message"=>"success"]);
        }else{
            return response()->json(["message"=>"error"]);
        }

    }
    static function getReportSummary($id) {
        try {

            set_time_limit(600);

            $data["tickets"] = Ticket::query()->whereIn("id", $id)->get();
            $organization = Organization::query()->find($data["tickets"][0]->org_id);
            $status2 = $data["tickets"][0]->status_id;

            $all_tickets_total_spent_minutes = 0;
            $all_tickets_good_will_minutes = 0;

            foreach($data["tickets"] as $ticket) {
                $discussions = Discussion::query()->where("ticket_id", $ticket->id)->where("is_private", 0)->latest()->get();

                foreach($discussions as $discussion) {
                    $user = User::query()->find($discussion->user_id);
                    $discussion->user = $user ?? "";
                    $content = preg_replace('/(<)([img])(\w+)([^>]*>)/', '$1', $discussion->message);
                    $discussion->message = strip_tags($content, ["br", "p", "b", "i"]);
                }

                $ticket->discussions = $discussions;
                $attachments = TicketAttachment::query()->where("ticket_id", $ticket->id)->where("private", 0)->where("is_mail", 0)->get();
                $ticket->attachments = $attachments;
                $times_goodwill = Helper::getDiscountedEffortsAsMinute($ticket->id);
                $total_spent_minutes = Helper::getTotalEffortAsMinute($ticket->id);
                $all_tickets_good_will_minutes += $times_goodwill;
                $all_tickets_total_spent_minutes += $total_spent_minutes;
                $discount = $total_spent_minutes != 0 ? (($total_spent_minutes - $times_goodwill) / $total_spent_minutes) * 100 : 0;
                $ticket->discount = intval(round($discount));
                $ticket->good_will_time = Helper::convert_minute_to_clock($times_goodwill);
                $ticket->total_spent_time = Helper::convert_minute_to_clock($total_spent_minutes);
            }

            $status = Status::query()->find($status2);

            if(!$status && $status2 == "proofed") {
                $status = Status::query()->find(6);
                $status->name = "Done & Proofed";
            }


            if($organization->personnel_org == 3) {
                $organization->customer_no_text = "Kunden Nr";
                $organization->arranger = "Bearbeiter";
                $organization->date = "Datum";
                $organization->period = "Zeitraum";

                if($id == "all") {
                    $organization->title = "Zusammenfassung der " . $status->name . " Tickets";
                }
                else {
                    $organization->title = "Zusammenfassung der Ticket ID #" . $data["tickets"][0]->id;
                }
            }
            elseif($organization->personnel_org == 8) {
                $organization->customer_no_text = "Customer ID";
                $organization->arranger = "Editor";
                $organization->date = "Date";
                $organization->period = "Tickets Reporting";

                if($id == "all") {
                    $organization->title = "Summary of " . $status->name . " Tickets";
                }
                else {
                    $organization->title = "Summary of Ticket with Ticket ID #" . $data["tickets"][0]->id;
                }
            }

            $data["data"] = $organization;
            $data["title"] = 'Organization';
            $data["total_spent_time"] = Helper::convert_minute_to_clock($all_tickets_total_spent_minutes);
            $data["total_good_will_time"] = Helper::convert_minute_to_clock($all_tickets_good_will_minutes);
            $data["id"] = $id;
            $data["type"] = null;
            $pdf = App::make("dompdf.wrapper");
            $pdf->loadView("pdftemplates.reports.summary-report", $data);

            $pdfTempFileName = time() . rand(0,1000000);
            $pdfTempFileName = $id[0] . "_Ticket_Report_".$pdfTempFileName.".pdf";
            $pdfPath = storage_path("app/tempfiles/" . $pdfTempFileName);
            $pdf->save($pdfPath);

            ob_clean();
            ob_end_clean();
            return [
                "file" => $pdfTempFileName
            ];

        }
        catch(Exception $exception) {
            Helper::create_debug_log(
                __CLASS__,
                __FUNCTION__,
                "Something went wrong while trying to export a report!",
                9,
                $exception->getMessage() . " Line:" . $exception->getLine()
            );
        }
    }
}