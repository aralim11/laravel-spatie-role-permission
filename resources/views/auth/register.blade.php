@extends('layouts.app')

@section('title', 'User')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span><button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addUserModal()">Add @yield('title')</button></div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped hundred_percent">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody id="table_content">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="role_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add @yield('title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Role Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            viewUser();
        });

        function viewUser(){
            $.ajax({
                type: 'GET',
                url: "/user-view",
                dataType: "json",
                success: function(resultData) {
                    if (resultData.status === "success") {
                        $("#table_content").html(resultData.msg);
                    } else {
                        $("#table_content").html('ok');
                    }
                }
            });
        }
    </script>
@endpush