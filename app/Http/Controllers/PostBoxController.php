<?php

namespace App\Http\Controllers;

use App\PostBox;
use App\PostBoxAttachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\True_;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class PostBoxController extends Controller
{
    public function index(){

        return view("post-box.post-box");
    }

    public function create(){
        $id = DB::select("SHOW TABLE STATUS LIKE 'postbox'");

        $counter = sprintf("%04d",$id[0]->Auto_increment);// get auto increment value
        return view("post-box.add-post-box",compact('counter'));
    }

    public function create_post(Request $request){


        $post = new PostBox;

        $post->consignor = $request->consignor;
        $post->received_date = $request->received_date;
        $post->add_by = auth()->id();
        $post->updated_by = auth()->id();
        $post->save();


        foreach ($request->ticketAttachments as $size => $filename){
            $post_attachment = new PostBoxAttachment;
            $post_attachment->post_id = $post->id;
            $post_attachment->attachment = $filename;
            $post_attachment->size  = $size;
            $post_attachment->add_by = auth()->id();

            $post_attachment->save();

        }

        return redirect()->route("post-box.index");


    }

    public function show(){

        $posts = PostBox::all();

        try {
            return DataTables::of($posts)
                ->editColumn("id",function ($row){

                    return sprintf("%04d",$row->id);
                })
                ->editColumn("received_date",function ($row){

                    return Carbon::parse($row->received_date)->format("d.m.Y");
                })
                ->addColumn('review',function ($row){
                    $post_id = $row->id;
                    $attachment = PostBoxAttachment::where('post_id',$post_id)->first();
                    if ($attachment == null)
                        return  "-";
                    else
                    return "<a class='btn btn-sm btn-primary'  target='_blank' href='".asset('/uploads')."/".$attachment->attachment."'><i style='color: whitesmoke;' class='fa fa-eye'></i></a>";
                })
                ->addColumn('actions',function ($row){

                    return $row->id;
                })
                ->rawColumns(['review','actions'])
                ->make(true);
        }
        catch (\Exception $e){

        }


    }

    public function update($id){

        $post = PostBox::find($id);
        $attachment = PostBoxAttachment::where('post_id',$post->id)->first();

        return view("post-box.update-post-box",compact('post','attachment'));

    }

    public function update_post(Request $request){

        $post = PostBox::find($request->post_id);

        $post->consignor = $request->consignor;
        $post->received_date = $request->received_date;
        $post->updated_by = auth()->id();


            if($request->ticketAttachments){
                foreach ($request->ticketAttachments as $size => $filename){
                $post_attachment =  new PostBoxAttachment;
                $post_attachment->post_id = $post->id;
                $post_attachment->attachment = $filename;
                $post_attachment->add_by = auth()->id();
                $post_attachment->size  = $size;
                $post->updated_at = Carbon::now();
                $post_attachment->save();

            }

        }
            $post->save();

            return redirect()->route("post-box.index");
    }

    public function delete(Request $request){

        $post = PostBox::find($request->post_id);
        $post_attachment = PostBoxAttachment::where("post_id",$request->post_id)->first();
        if ($post_attachment) {
            $file_path = storage_path('app/uploads/')  . $post_attachment->attachment;

            try {
                 unlink($file_path); // delete file
            }catch (\Exception $e){

            }

            $post_attachment->delete();
        }
        $post -> delete();

        return response()->json(["status"=>1]);
    }

    public function deleteAttachment($id){

        $post_attachment = PostBoxAttachment::find($id);
        $post = PostBox::find($post_attachment->post_id);
        $post->updated_by = auth()->id();
        $post->updated_at = Carbon::now();
        $post->save();
        try {//if is file not in storage low possibility
             $file_path = storage_path('app/uploads/').$post_attachment->attachment;
             unlink($file_path); // delete file
        }
        catch (\Exception $exception){

        }

        $post_attachment->delete();

        return response()->json(["status" => 1]);

    }
}
