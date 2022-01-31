@extends('layouts.app')

@section('title', 'Permission')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span>@if(Auth::User()->can('permission.add'))<button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addpermissionModal()">Add @yield('title')</button>@endif</div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped hundred_percent">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Permission Group Name</th>
                                    <th>Permission Name</th>
                                    @if(Auth::User()->can('permission.edit'))<th>Action</th>@endif
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

    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="permission_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add @yield('title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Permission Group</label>
                        <select class="form-select" id="group_id" aria-label="Default select example" required>
                            <option value="">Select Permission Group</option>
                            @foreach($permissionGroups as $permissionGroup)
                            <option value="{{ $permissionGroup->id }}">{{ $permissionGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="col-form-label">Permission Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Permission Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storePermission()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $( document ).ready(function() {
            viewPermission();
        });

        function addpermissionModal(){
            $("#addPermissionModal").modal('show');
        }

        function storePermission(){
            var validation = formValidation('permission_add_form');
            if(!validation){
                var name = $("#name").val();
                var group_id = $("#group_id").val();
                $.ajax({
                    type: 'POST',
                    url: "/permission-store",
                    data: {name: name, group_id: group_id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            $("#name").val('');
                            $("#group_id ").val('');
                            successAlert(resultData.msg);
                            viewPermission();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function viewPermission(){
            $.ajax({
                type: 'GET',
                url: "/permission-view",
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

        function openEditpermissionGroupModal(id){
            $.ajax({
                type: 'GET',
                url: "/permission-edit/" + id,
                dataType: "json",
                success: function(resultData) {
                    if (resultData.status === "success") {
                        $("#main_modal_content").html(resultData.msg);
                        $("#mainModal").modal('show');
                    } else {
                        errorAlert("No Data Found By ID!!");
                    }
                }
            });
        }

        function updatePermission(id){
            var validation = formValidation('main_modal_content');
            if(!validation){
                var name = $("#edit_name").val();
                var group_id = $("#edit_group_id").val();
                $.ajax({
                    type: 'POST',
                    url: "/permission-update",
                    data: {name: name, id: id, group_id: group_id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            successAlert(resultData.msg);
                            viewPermission();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }
    </script>
@endpush
