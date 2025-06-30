<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAttachment extends Model {
    use SoftDeletes;
    protected $table = "ticket_attachments";
    protected $guarded = [];

    function getUserNameAttribute() {
        $user = User::query()->where("id", $this->add_by)->first();
        $user ? $name = $user->first_name : $name = '';
        return $name;
    }

    function getSurNameAttribute()
    {
        $name = '';
        $user = User::where('id', $this->add_by)->first();
        if ($user)
            $name = $user->surname;
        else
            $name = '';
        return $name;
    }
    function getTicketNameAttribute()
    {
        $name = '';
        $ticket = Ticket::where('id', $this->ticket_id)->first();
        if ($ticket)
            $name = $ticket->name;
        return $name;
    }
    function getOrganizationNameAttribute()
    {
        $name = '';
        $ticket = Ticket::where('id', $this->ticket_id)->first();
        $organization = Organization::where('id', $ticket->org_id)->first();
        if ($organization)
            $name = $organization->org_name;
        return $name;
    }
    function getParsedCreatedAtAttribute()
    {
        $parsedDate = '';
        if ($this->created_at)
            $parsedDate = Carbon::parse($this->created_at)->format('d.m.Y [H:i:s]');
        return $parsedDate;
    }

    public function getDiscussion(){

        return $this->belongsTo(Discussion::class,"discussion_id");
    }

    protected $appends = ['UserName', 'TicketName', 'OrganizationName', 'ParsedCreatedAt', 'SurName'];
}
