@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>{{ __('Role') }}</span><button type="button" class="btn btn-info btn-sm float_right" onclick="addRoleModal()">Add Role</button></div>
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="role_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Role Name</label>
                        <input type="text" class="form-control" id="role_name" placeholder="Enter Role Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storeRole()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function addRoleModal(){
            $("#addRoleModal").modal('show');
        }

        function storeRole(){
            var validation = formValidation('role_add_form');
            if(!validation){
                var roleName = $("#role_name").val();
                $.ajax({
                    type: 'POST',
                    url: "/role-store",
                    data: {roleName: roleName},
                    dataType: "json",
                    success: function(resultData) {
                        console.log(resultData); 
                    }
                });
            }
        }
    </script>
@endpush