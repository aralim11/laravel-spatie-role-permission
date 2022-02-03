@extends('layouts.app')

@section('title', 'Category')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span>@if(Auth::User()->can('category.add'))<button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addCategoryModal()">Add @yield('title')</button>@endif</div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Category Name</th>
                                    @if(Auth::User()->can('category.edit'))<th>Action</th>@endif
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

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="category_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add @yield('title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Category Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storeCategory()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            viewCategory();
        });

        function addCategoryModal(){
            $("#addCategoryModal").modal('show');
        }

        function storeCategory(){
            var validation = formValidation('category_add_form');
            if(!validation){
                var name = $("#name").val();
                $.ajax({
                    type: 'POST',
                    url: "/category-store",
                    data: {name: name},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            $("#name").val('');
                            successAlert(resultData.msg);
                            viewCategory();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function viewCategory(){
            $.ajax({
                type: 'GET',
                url: "/category-view",
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

        function openEditCategoryModal(id){
            Swal.fire('Please Wait. Data Processing!!');
			Swal.showLoading();
            
            $.ajax({
                type: 'GET',
                url: "/category-edit/" + id,
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

        function updateCategory(id){
            var validation = formValidation('main_modal_content');
            if(!validation){
                var name = $("#edit_name").val();
                $.ajax({
                    type: 'POST',
                    url: "/category-update",
                    data: {name: name, id: id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            successAlert(resultData.msg);
                            viewCategory();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function deleteCategory(id)
        {
            Swal.fire({
                title: 'Are You Sure To Delete?',
                text: "If You Delete This Category Automatically Delete All Blog Under This Category.",
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
                        url: "/category-delete/" + id,
                        dataType: "json",
                        success: function(resultData) {
                            swal.close();
                            if (resultData.status === "success") {
                                successAlert(resultData.msg);
                                viewCategory();
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
