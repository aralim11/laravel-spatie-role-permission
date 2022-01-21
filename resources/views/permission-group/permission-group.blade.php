@extends('layouts.app')

@section('title', 'Permission Group')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span><button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addpermissionGroupModal()">Add @yield('title')</button></div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Permission Group Name</th>
                                    <th>Action</th>
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

    <div class="modal fade" id="addPermissionGroupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="permission_group_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add @yield('title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Permission Group Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Permission Group Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storePermissionGroup()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $( document ).ready(function() {
            viewPermissionGroup();
        });

        function addpermissionGroupModal(){
            $("#addPermissionGroupModal").modal('show');
        }

        function storePermissionGroup(){
            var validation = formValidation('permission_group_add_form');
            if(!validation){
                var name = $("#name").val();
                $.ajax({
                    type: 'POST',
                    url: "/permission-group-store",
                    data: {name: name},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            $("#name").val('');
                            successAlert(resultData.msg);
                            viewPermissionGroup();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function viewPermissionGroup(){
            $.ajax({
                type: 'GET',
                url: "/permission-group-view",
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

        function openEditpermissionGroupModal(id){
            $.ajax({
                type: 'GET',
                url: "/permission-group-edit/" + id,
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

        function updatePermissionGroup(id){
            var validation = formValidation('main_modal_content');
            if(!validation){
                var name = $("#edit_name").val();
                $.ajax({
                    type: 'POST',
                    url: "/permission-group-update",
                    data: {name: name, id: id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            successAlert(resultData.msg);
                            viewPermissionGroup();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }
    </script>
@endpush