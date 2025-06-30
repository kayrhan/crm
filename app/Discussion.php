<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    //
    use SoftDeletes;
    function getUserNameAttribute()
    {
        $name = '';
        $user = User::where('id', $this->user_id)->first();
        if ($user)
            $name = $user->first_name." ".$user->surname;
        else
            $name = '';
        return $name;
    }
    function getSurNameAttribute()
    {
        $name = '';
        $user = User::where('id', $this->user_id)->first();
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
        $organization = Organization::where('id', $this->org_id)->first();
        if ($organization)
            $name = $organization->org_name;
        return $name;
    }


    protected $appends = ['UserName', 'TicketName', 'OrganizationName', 'SurName'];
}
