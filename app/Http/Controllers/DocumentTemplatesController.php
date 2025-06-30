<?php

namespace App\Http\Controllers;

use App\Company;
use App\DocumentTemplates;
use App\DocumentTemplatesCategories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DocumentTemplatesController extends Controller
{
    public function index(Request $request){


        if ($request->ajax()) {

            try {


                $accountings = DocumentTemplates::
                selectRaw('document_templates.*,CONCAT(users.first_name," ",users.surname) as user')
                    ->leftJoin('users', 'users.id', 'document_templates.add_by')
                    ->orderBy("document_templates.id","desc");


                return DataTables::of($accountings)
                    ->editColumn("category",function ($row){
                        return DocumentTemplatesCategories::where("id",$row->category)->first()->name;
                    })
                    ->editColumn("company",function ($row){
                        return Company::where("id",$row->company)->first()->name;
                    })
                    ->editColumn("size",function ($row){
                        return number_format($row->size / 1048576,2);
                    })
                    ->addColumn("actions",function ($row){
                        return $row->id;
                    })
                    ->filterColumn('user', function($query, $keyword) {
                        $query->whereRaw('CONCAT(users.first_name," ",users.surname) LIKE ?', ["%{$keyword}%"]);
                    })
                    ->filterColumn("company",function ($q,$k){
                        $companies = Company::where("name","like","%".$k."%")->select("id")->pluck("id");
                        $q->whereIn("company",$companies);
                    })
                    ->filterColumn("category",function ($q,$k){
                        $categories = DocumentTemplatesCategories::where("name","like","%".$k."%")->select("id")->pluck("id");
                        $q->whereIn("category",$categories);
                    })
                    ->editColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->format('d.m.Y');
                    })
                    ->filterColumn('created_at', function($query, $keyword) {
                        $query->whereRaw('document_templates.created_at LIKE ?', ["%{$keyword}%"]);
                    })
                    ->make(true);
            } catch (\Exception $e){

                return ['error' => 'Something went wrong'];
            }
        } else {
            $companies = Company::all();
            $categories = DocumentTemplatesCategories::all();
            return view('document-templates.index',['companies'=>$companies,'categories'=>$categories]);
        }
    }
    public function upload(Request $request){
        $validatedFile = $request->validate([
            'file' => 'required|mimes:pdf,xls,xlsx,docx,doc',
        ]);

        $inputFile = $request->file;

        if($inputFile){
            $size = $inputFile->getSize();
            $new_name = time() . '.' . $inputFile->getClientOriginalExtension();
            $inputFile->move(storage_path("app/uploads/"), $new_name);

            $insertFile = [
                'company' => $request->company,
                'category' => $request->category,
                'title' => $request->title,
                'file' => $new_name,
                'orginal_file_name' => $inputFile->getClientOriginalName(),
                'type' => $inputFile->getClientOriginalExtension(),
                'size' => $size,
                'add_by' => Auth::user()->id
            ];

            DocumentTemplates::create($insertFile);


        }

        return response(\GuzzleHttp\json_encode(['status' => 'ok']), 200)->header('Content-Type', 'application/json');
    }

    public function delete(Request $request){
        $id = $request->id;
        $file = DocumentTemplates::where('id',$id)->first();
        if($file){

            $file_path = storage_path('app/uploads/')  . $file->file;
            DocumentTemplates::where('id',$id)->delete();

            try {
                unlink($file_path); // delete file
            }catch (\Exception $e){

            }

            return response(\GuzzleHttp\json_encode(['status' => 'ok']), 200)->header('Content-Type', 'application/json');

        } else {
            return response(\GuzzleHttp\json_encode(['status' => 'error']), 500)->header('Content-Type', 'application/json');
        }
    }
}
