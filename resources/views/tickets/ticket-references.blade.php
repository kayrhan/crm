@if($ticket["parent_ticket"])
<div class="row py-4 mb-2">
    <div class="col-lg-10 col-md-10">
        <div class="table-responsive border">
            <table class="table table-bordered text-wrap w-100 mb-0">
                <thead>
                    <tr class="justify-content-center">
                        <th colspan="8" class="text-primary"><strong>Referenced From</strong></th>
                    </tr>
                    <tr>
                        <th class="text-dark text-center" style="width: 3%"><strong>ID</strong></th>
                        <th class="text-dark text-center" style="width: 37%"><strong>Name</strong></th>
                        <th class="text-dark text-center" style="width: 10%"><strong>Status</strong></th>
                        <th class="text-dark text-center" style="width: 7%"><strong>Priority</strong></th>
                        <th class="text-dark text-center" style="width: 14%"><strong>Creation Date</strong></th>
                        <th class="text-dark text-center" style="width: 8%"><strong>Due Date</strong></th>
                        <th class="text-dark text-center" style="width: 11%"><strong>Done Date</strong></th>
                        @if(auth()->user()->role_id === 1)
                        <th class="text-dark text-center" style="width: 10%"><strong>Actions</strong></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center align-middle"><a href="/update-ticket/{{ $ticket["parent_ticket"]->id }}" target="_blank" class="font-weight-bold text-azure" data-toggle="tooltip" title="Go to the ticket.">{{ $ticket["parent_ticket"]->id }}</a></td>
                        <td class="text-center align-middle">{{ $ticket["parent_ticket"]->name }}</td>
                        <td class="text-center align-middle">{{ $ticket["parent_ticket"]->getStatusNameAttribute() }}</td>
                        <td class="text-center align-middle">{{ $ticket["parent_ticket"]->getPriorityNameAttribute() }}</td>
                        <td class="text-center align-middle">{{ \Carbon\Carbon::parse($ticket["parent_ticket"]->created_at)->format('d.m.Y [H:i:s]') }}</td>
                        <td class="text-center align-middle">{{ $ticket["parent_ticket"]->getParsedDueDateAttribute() }}</td>
                        <td class="text-center align-middle">{{ \Carbon\Carbon::parse($ticket["parent_ticket"]->close_date)->format('d.m.Y') }}</td>
                        @if(auth()->user()->role_id === 1)
                        <td class="align-middle">
                            <div class="d-flex justify-content-center align-items-center" style="min-height: 100% !important;">
                                <button type="button" onclick="removeReference({{ $ticket["parent_ticket"]->id }}, {{ $ticket->id }})" class="btn btn-sm btn-danger remove-reference-button" data-toggle="tooltip" title="Remove this reference."><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@if($ticket["child_ticket"])
@if(count($ticket["child_ticket"]) > 0)
<div class="row py-4 mb-2">
    <div class="col-lg-10 col-md-10">
        <div class="table-responsive border">
            <table class="table table-bordered text-wrap w-100 mb-0">
                <thead>
                    <tr class="justify-content-center">
                        <th colspan="8" class="text-primary"><strong>References To</strong></th>
                    </tr>
                    <tr>
                        <th class="text-dark text-center" style="width: 3%"><strong>ID</strong></th>
                        <th class="text-dark text-center" style="width: 37%"><strong>Name</strong></th>
                        <th class="text-dark text-center" style="width: 10%"><strong>Status</strong></th>
                        <th class="text-dark text-center" style="width: 7%"><strong>Priority</strong></th>
                        <th class="text-dark text-center" style="width: 14%"><strong>Creation Date</strong></th>
                        <th class="text-dark text-center" style="width: 8%"><strong>Due Date</strong></th>
                        <th class="text-dark text-center" style="width: 11%"><strong>Done Date</strong></th>
                        @if(auth()->user()->role_id === 1)
                        <th class="text-dark text-center" style="width: 10%"><strong>Actions</strong></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticket["child_ticket"] as $child)
                    <tr>
                        <td class="text-center align-middle"><a href="/update-ticket/{{ $child->id }}" target="_blank" class="font-weight-bold text-azure" data-toggle="tooltip" title="Go to the ticket.">{{ $child->id }}</a></td>
                        <td class="text-center align-middle">{{ $child->name }}</td>
                        <td class="text-center align-middle">{{ $child->getStatusNameAttribute() }}</td>
                        <td class="text-center align-middle">{{ $child->getPriorityNameAttribute() }}</td>
                        <td class="text-center align-middle">{{ \Carbon\Carbon::parse($child->created_at)->format('d.m.Y [H:i:s]') }}</td>
                        <td class="text-center align-middle">{{ $child->getParsedDueDateAttribute() }}</td>
                        <td class="text-center align-middle">{{ \Carbon\Carbon::parse($child->close_date)->format('d.m.Y') }}</td>
                        @if(auth()->user()->role_id === 1)
                        <td class="align-middle">
                            <div class="d-flex justify-content-center align-items-center" style="min-height: 100% !important;">
                                <button type="button" onclick="removeReference({{ $ticket->id }}, {{ $child->id }})" class="btn btn-sm btn-danger remove-reference-button" data-toggle="tooltip" title="Remove this reference."><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endif