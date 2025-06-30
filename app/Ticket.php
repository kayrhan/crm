<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $guarded = [];

    function getStatusNameAttribute() {
        $status = Status::query()->find($this->status_id);
        return $status ? $status->name : "";
    }

    function getOrganizationNameAttribute() {
        $organization = Organization::query()->find($this->org_id);
        return $organization ? $organization->org_name : "";
    }

    function getUserNameAttribute() {
        $user = User::query()->find($this->user);
        return $user ? $user->first_name : "";
    }

    function getPersonnelNameAttribute() {
        $user = User::query()->find($this->personnel);
        return $user ? $user->first_name : "";
    }

    function getPersonnelEmailAttribute() {
        $user = User::query()->find($this->personnel);
        return $user ? $user->email : "";
    }

    function getCategoryNameAttribute() {
        $category = Category::query()->find($this->category);
        return $category ? $category->name : "";
    }

    function getParsedCreatedAtAttribute() {
        return $this->created_at ? Carbon::parse($this->created_at)->format("d.m.Y") : "";
    }

    function getParsedDueDateAttribute() {
        return $this->due_date ? Carbon::parse($this->due_date)->format("d.m.Y") : "";
    }

    function getPriorityNameAttribute() {
        switch($this->priority) {
            case 4:
                $priority = "Low";
                break;
            case 1:
                $priority = "Normal";
                break;
            case 2:
                $priority = "High";
                break;
            case 3:
                $priority = "Very High";
                break;
            default:
                $priority = "";
        }

        return $priority;
    }

    function getSurNameAttribute() {
        $user = User::query()->find($this->user);
        return $user ? $user->surname : "";
    }

    function getPersonnelSurNameAttribute() {
        $user = User::query()->find($this->personnel);
        return $user ? $user->surname : "";
    }

    function getIsContractedAttribute() {
        $organization = Organization::query()->find($this->org_id);
        return $organization ? $organization->is_contracted : null;
    }

    function getSecondaryUserAttribute() {
        $user = User::query()->whereRaw('id IN (SELECT personnel FROM ticket_personnels WHERE ticket_id = ' . $this->id .' AND deleted_at IS NULL)')->pluck('surname', 'first_name');
        return $user ?: null;
    }

    public function getNameWithID() {
        return "#" . $this->id . " | " . $this->name;
    }

    public function getProofedName() {
        if($this->proof_by) {
            $user = User::query()->find($this->proof_by);
            return $user->first_name . " " . $user->surname;
        }
        else {
            return "";
        }
    }

    public function getInvoicedUser() {
        if($this->invoiced_by) {
            $user = User::query()->find($this->invoiced_by);
            return $user->first_name . " " . $user->surname;
        }
        else {
            return "";
        }
    }

    public function getCorrectionUser() {
        if($this->correction_by) {
            $user = User::query()->find($this->correction_by);
            return $user->first_name . " " . $user->surname;
        }
        else {
            return "";
        }
    }

    public function getTicketHolderName() {
        return $this->ticketHolder ? $this->ticketHolder->first_name . " " . $this->ticketHolder->surname : "";
    }

    public function getTicketAssignedUserName(){
        return $this->assignedTo ? $this->assignedTo->first_name . " " . $this->assignedTo->surname : "";
    }

    // Relationships
    public function organization() {
        return $this->belongsTo(Organization::class, "org_id");
    }

    public function ticketHolder() {
        return $this->belongsTo(User::class, "user");
    }

    public function assignedTo() {
        return $this->belongsTo(User::class, "personnel");
    }

    public function status() {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function secondary_users() {
        return $this->hasMany(TicketPersonnel::class, "ticket_id", "id")->pluck("personnel")->toArray();
    }

    public function assigned_users() {
        $users = [];

        foreach($this->secondary_users() as $user_id) {
            $users[] = [
                "id" => $user_id,
                "name" => User::query()->selectRaw("Concat(first_name, ' ', surname) as name")->where("id", $user_id)->first()->name ?? "",
                "mail" => User::query()->find($user_id)->email ?? ""
            ];
        }

        return $users;
    }

    protected $appends = [
        'StatusName',
        'OrganizationName',
        'UserName',
        'PersonnelName',
        'PersonnelEmail',
        'PriorityName',
        'CategoryName',
        'ParsedCreatedAt',
        'SurName',
        'PersonnelSurName',
        'ParsedDueDate',
        'IsContracted',
        'SecondaryUser',
    ];
}