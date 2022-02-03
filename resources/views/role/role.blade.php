@extends('layouts.app')

@section('title', 'Role')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span>@if(Auth::User()->can('role.add'))<button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addRoleModal()">Add @yield('title')</button>@endif</div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped hundred_percent">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Role Name</th>
                                    @if(Auth::User()->can('role.edit'))<th>Action</th>@endif
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

                    <h5>All Permissions</h5><hr style="background-color: black!important;">
                    <table class="hundred_percent">
                        <tbody>
                            @foreach($permission_groups as $permission_group)
                                <tr class="table_bottom_border">
                                    <td class="fifty_percent">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $permission_group->id }}" id="permission_group_{{ $permission_group->id }}" onclick="checkAllPermissionByGroup({{ $permission_group->id }})">
                                            <label class="form-check-label" for="permission_group_{{ $permission_group->id }}">{{ $permission_group->name }}</label>
                                        </div>
                                    </td>

                                    @php
                                       $permissions = DB::table('permissions')->where('group_id', $permission_group->id)->get();
                                    @endphp

                                    <td class="fifty_percent">
                                        @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input checkAllPermissionByGroup_{{$permission_group->id}}" onclick="checkGroupByPermission({{$permission_group->id}},{{count($permissions)}})" name="checkPermission" type="checkbox" value="{{$permission->name}}" id="checkPermission{{ $permission->id }}">
                                                <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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
        $( document ).ready(function() {
            viewRole();
        });

        function addRoleModal(){
            $("#addRoleModal").modal('show');
        }

        function storeRole(){
            var validation = formValidation('role_add_form');
            var checkBoxValidation = checkBoxNullValidation();

            if(!validation && (checkBoxValidation != false)){
                var name = $("#name").val();
                $.ajax({
                    type: 'POST',
                    url: "/role-store",
                    data: {name: name, permission: checkBoxValidation},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            $("#name").val('');
                            $('input:checkbox').prop('checked', false);
                            successAlert(resultData.msg);
                            viewRole();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function viewRole(){
            $.ajax({
                type: 'GET',
                url: "/role-view",
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

        function checkAllPermissionByGroup(id){
            var groupIdName = $('#permission_group_'+id);

            if (groupIdName.is(':checked')) {
                $('.checkAllPermissionByGroup_'+id).prop('checked', true);
            } else {
                $('.checkAllPermissionByGroup_'+id).prop('checked', false);
            }
        }

        function edit_checkAllPermissionByGroup(id){
            var groupIdName = $('#edit_permission_group_'+id);

            if (groupIdName.is(':checked')) {
                $('.edit_checkAllPermissionByGroup_'+id).prop('checked', true);
            } else {
                $('.edit_checkAllPermissionByGroup_'+id).prop('checked', false);
            }
        }

        function openEditRoleModal(id){
            Swal.fire('Please Wait. Data Processing!!');
			Swal.showLoading();

            $.ajax({
                type: 'GET',
                url: "/role-edit/" + id,
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

        function checkGroupByPermission(group_id, count){
            var permissionClass = $('.checkAllPermissionByGroup_'+group_id+':checked');
            var groupCheckBox = $('#permission_group_'+group_id);

            if (permissionClass.length === count) {
                $('#permission_group_'+group_id).prop('checked', true);
            } else {
                $('#permission_group_'+group_id).prop('checked', false);
            }
        }

        function edit_checkGroupByPermission(group_id, count){
            var permissionClass = $('.edit_checkAllPermissionByGroup_'+group_id+':checked');
            var groupCheckBox = $('#edit_permission_group_'+group_id);

            if (permissionClass.length === count) {
                $('#edit_permission_group_'+group_id).prop('checked', true);
            } else {
                $('#edit_permission_group_'+group_id).prop('checked', false);
            }
        }

        function updateRole(id){
            var validation = formValidation('edit_role_add_form');
            var checkBoxValidation = edit_checkBoxNullValidation();

            if(!validation && (checkBoxValidation != false)){
                Swal.fire('Please Wait. Updating!!');
				Swal.showLoading();

                var name = $("#edit_name").val();
                $.ajax({
                    type: 'POST',
                    url: "/role-update",
                    data: {name: name, permission: checkBoxValidation, id: id},
                    dataType: "json",
                    success: function(resultData) {
    					swal.close();
                        if (resultData.status === "success") {
                            successAlert(resultData.msg);
                            viewRole();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function deleteRole(id)
        {
            Swal.fire({
                title: 'Are You Sure To Delete?',
                text: "If You Delete This Role Automatically Delete All Under This Role.",
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
                        url: "/role-delete/" + id,
                        dataType: "json",
                        success: function(resultData) {
                            swal.close();
                            if (resultData.status === "success") {
                                successAlert(resultData.msg);
                                viewRole();
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
