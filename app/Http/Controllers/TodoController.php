<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Todo;
use App\TodoAttachment;
use App\TodoStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class TodoController extends Controller
{


    public function index()
    {

        $user_id = auth()->id();

        $user_todos = Todo::where("user_id", $user_id)->orderBy("todo_number", "desc")->get();
        $todos_status = TodoStatus::all();
        return view("todo.index", compact("user_todos", "todos_status"));
    }


    public function addTodo()
    {

        $user_id = auth()->id();

        $last_todo = Todo::where("user_id", $user_id)->orderBy("todo_number", "desc")->first();

        $todo_status = TodoStatus::all();

        return view("todo.add-todo", compact("last_todo", "todo_status"));
    }

    public function addTodoPost(Request $request)
    {


        $request->validate([
            "todo_id" => "required",
            "subject" => "required|max:255",
            "status" => "required",
        ]);

        $todo = new Todo;

        $user_id = auth()->id();

        $last_todo = Todo::where("user_id", $user_id)->orderBy("todo_number", "desc")->first();
        if ($last_todo) {
            $last_todo_number = $last_todo->todo_number;
            $todo->todo_number = $last_todo_number + 1;
        } else {
            $todo->todo_number = 1;
        }

        $todo->user_id = $user_id;

        $todo->subject = $request->subject;
        $todo->description = $request->description;
        $todo->status = $request->status;
        $todo->due_date = $request->due_date;
        if ($request->org_id != null) {
            $todo->org_name = Organization::find($request->org_id)->org_name;
        }
        $todo->save();
        if ($request->todoAttachments != null) {
            foreach ($request->todoAttachments as $size => $filename) {

                $attachment = new TodoAttachment;
                $attachment->todo_id = $todo->id;
                $attachment->attachment = $filename;
                $attachment->size = $size;
                $attachment->add_by = auth()->id();
                $attachment->save();
            }
        }

        return redirect()->route("todo.index");

    }

    public function updateTodo($todo_number)
    {

        $todo = Todo::where("user_id", auth()->id())->where("todo_number", $todo_number)->first();

        $todos_status = TodoStatus::all();
        if($todo) {
            $todo_attachments = TodoAttachment::where("todo_id", $todo->id)->get();
            return view("todo.update-todo", compact("todo", "todos_status", "todo_attachments"));
        }
        else {
            return redirect("/todos");
        }

    }

    public function updateTodoPost(Request $request)
    {

        $request->validate([
            "todo_id" => "required",
            "subject" => "required|max:255",
            "status" => "required",
        ]);

        $todo = Todo::where("user_id", auth()->id())->where("todo_number", $request->todo_id)->first();

        $todo->subject = $request->subject;
        $todo->description = $request->description;
        $todo->status = $request->status;
        $todo->due_date = $request->due_date;
        if ($request->org_id != null) {
            $todo->org_name = Organization::find($request->org_id)->org_name;
        } else {
            $todo->org_name = null;
        }
        $todo->save();

        if ($request->todoAttachments != null) {
            foreach ($request->todoAttachments as $size => $filename) {

                $attachment = new TodoAttachment;
                $attachment->todo_id = $todo->id;
                $attachment->attachment = $filename;
                $attachment->size = $size;
                $attachment->add_by = auth()->id();
                $attachment->save();
            }
        }

        return redirect()->route("todo.index");

    }


    public function deleteTodo($todo_number)
    {

        $todo = Todo::where("user_id", auth()->id())->where("todo_number", $todo_number)->first();

        $todo->delete();

        return ["status" => 1];


    }

    public function deleteTodoAttachment($attach_id)
    {
        $attach = TodoAttachment::find($attach_id);
        $attach->delete();
        return response()->json(["success" => 1]);
    }


    public function list(Request $request)
    {
        try {
            $user_id = auth()->id();
            $todos = Todo::where("user_id", $user_id)->orderBy("todo_number", "desc");
            return DataTables::of($todos)
                ->editColumn('description', function ($row) {
                    return strip_tags($row->description);
                })->editColumn('org_name', function ($row) {

                    return $row->org_name;

                })
                ->editColumn('subject', function ($row) {

                    return Str::limit(strip_tags($row->subject), 60, "...");
                })
                ->editColumn('due_date', function ($row) {
                    if($row->due_date)
                        return Carbon::parse($row->due_date)->format('d.m.Y');
                    else
                        return null;

                })
                ->addColumn('actions', function ($row) {
                    return '<div style="display:flex;justify-content:center;"><a class="btn btn-sm btn-info small mr-1" href="/update-todo/' . $row->todo_number . '"  ><i class="fa fa-edit "></i></a><a class="btn btn-sm btn-danger deleteTodo small" data-todo-number="' . $row->todo_number . '"><i class="fa fa-trash"></i></a></div>';
                })
                ->rawColumns(['actions', 'description']) //
                ->make(true);

        } catch (Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
}
