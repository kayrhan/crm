<div class="card">
    <div class="card-header justify-content-between">
        <h3 class="card-title">{{ ucfirst(trans('words.users')) }}</h3>

        <a class="btn btn-info" href="{{ url('/add-user/' . $organization->id) }}">
            <i class="fa fa-plus-circle mr-1"></i> Add User</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap" id="usersData">
                <thead>
                    <tr>
                        <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.first_name')) }}</th>
                        <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.email')) }}</th>
                        <th class="wd-20p border-bottom-0">{{ ucfirst(trans('words.role')) }}</th>
                        <th class="wd-15p border-bottom-0">{{ ucfirst(trans('words.phone')) }}</th>
                        <th class="wd-10p border-bottom-0">{{ ucfirst(trans('words.action')) }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>