@extends('layouts.app')

@section('title', 'User')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span>@if(Auth::User()->can('user.add'))<button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addUserModal()">Add @yield('title')</button>@endif</div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped hundred_percent">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    @if(Auth::User()->can('user.edit'))<th>Action</th>@endif
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

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="user_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add @yield('title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">User Name <span class="req">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Enter User Name" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="col-form-label">Email Address <span class="req">*</span></label>
                        <input type="email" class="form-control" id="email" placeholder="Enter User Email" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="col-form-label">User Role</label>
                        <select class="form-select" id="role_id" aria-label="Default select example">
                            <option value="">Select User Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storeUser()">Save</button>
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

        function addUserModal(){
            $("#addUserModal").modal('show');
        }

        function storeUser(){
            var validation = formValidation('user_add_form');

            if(!validation){
                var name = $("#name").val();
                var email = $("#email").val();
                var role_id = $("#role_id").val();
                $.ajax({
                    type: 'POST',
                    url: "/user-store",
                    data: {name: name, email: email, role_id: role_id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            $("#name").val('');
                            $("#email").val('');
                            $("#role_id").val('');
                            successAlert(resultData.msg);
                            viewRole();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function viewUser(){
            $.ajax({
                type: 'GET',
                url: "/user-view",
                dataType: "json",
                success: function(resultData) {
                    if (resultData.status === "success") {
                        $("#table_content").html(resultData.msg);
                        $("#permissionTable").dataTable();
                    } else {
                        $("#table_content").html('ok');
                    }
                }
            });
        }

        function openEditUserModal(id){
            Swal.fire('Please Wait. Data Processing!!');
			Swal.showLoading();

            $.ajax({
                type: 'GET',
                url: "/user-edit/" + id,
                dataType: "json",
                success: function(resultData) {
                    swal.close();
                    if (resultData.status === "success") {
                        $("#main_modal_content").html(resultData.msg);
                        $("#mainModal").modal('show');
                    } else {
                        errorAlert("No Data Found By ID!!");
                    }
                }
            });
        }

        function updateUser(id){
            var validation = formValidation('main_modal_content');
            if(!validation){
                var name = $("#edit_name").val();
                var email = $("#edit_email").val();
                var role_id = $("#edit_role_id").val();
                $.ajax({
                    type: 'POST',
                    url: "/user-update",
                    data: {name: name, email: email, role_id: role_id, id: id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            successAlert(resultData.msg);
                            viewUser();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function deleteUser(id)
        {
            Swal.fire({
                title: 'Are You Sure To Delete?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete It!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Please Wait. Data Deleting!!');
                    Swal.showLoading();

                    $.ajax({
                        type: 'DELETE',
                        url: "/user-delete/" + id,
                        dataType: "json",
                        success: function(resultData) {
                            swal.close();
                            if (resultData.status === "success") {
                                successAlert(resultData.msg);
                                viewUser();
                            } else {
                                errorAlert(resultData.msg);
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush
