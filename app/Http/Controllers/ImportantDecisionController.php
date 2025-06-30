<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImportantDecisionResource;
use App\ImportantDecision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportantDecisionController extends Controller
{
    public function add(Request $req)
    {
        $decision = new ImportantDecision();
        $decision->text = $req->important_text;
        $decision->ticket_id = $req->ticket_id;
        $decision->add_by = Auth::id();
        $decision->save();

        $this->addAttachments($req->attachments, $decision->id);

        $response = new ImportantDecisionResource($decision);
        return response()->json($response);
    }

    public function get($id)
    {
        $decision = ImportantDecision::find($id);
        return response()->json($decision);
    }

    public function update(Request $req)
    {
        try {
            $decision = ImportantDecision::find($req->id);
            $decision->text = $req->text;
            $decision->update();

            return response()->json("success");
        } catch (\Throwable $th) {
            return response()->json("error");
        }
    }

    public function delete(Request $req)
    {
        $decision = ImportantDecision::find($req->id);
        $decision->delete();
        return response()->json("success");
    }
}
