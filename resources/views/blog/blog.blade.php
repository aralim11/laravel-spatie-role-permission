@extends('layouts.app')

@section('title', 'Blog')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><span>@yield('title')</span>@if(Auth::User()->can('blog.add'))<button type="button" class="btn btn-info btn-sm card_btn_xs float_right" onclick="addBlogModal()">Add @yield('title')</button>@endif</div>
                    <div class="card-body">
                        <table id="permissionTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Category Name</th>
                                    <th>Title</th>
                                    @if(Auth::User()->can('blog.edit') || Auth::User()->can('blog.delete'))<th>Action</th>@endif
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

    <div class="modal fade" id="addBlogModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="blog_add_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add @yield('title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="col-form-label">Blog Title <span class="req">*</span></label>
                        <input type="text" class="form-control" id="title" placeholder="Enter Blog Title" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="col-form-label">Category Name <span class="req">*</span></label>
                        <select class="form-select" id="category_id" aria-label="Default select example" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="col-form-label">Blog Description <span class="req">*</span></label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storeBlog()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            viewBlog();
        });

        function addBlogModal(){
            $("#addBlogModal").modal('show');
        }

        function storeBlog(){
            var validation = formValidation('blog_add_form');
            if(!validation){
                var title = $("#title").val();
                var category_id = $("#category_id").val();
                var description = $("#description").val();
                $.ajax({
                    type: 'POST',
                    url: "/blog-store",
                    data: {title: title, category_id: category_id, description: description},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            $("#name").val('');
                            $("#category_id").val('');
                            $("#description").val('');
                            successAlert(resultData.msg);
                            viewBlog();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function viewBlog(){
            $.ajax({
                type: 'GET',
                url: "/blog-view",
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

        function openEditBlogModal(id){
            $.ajax({
                type: 'GET',
                url: "/blog-edit/" + id,
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

        function updateBlog(id){
            var validation = formValidation('main_modal_content');
            if(!validation){
                var title = $("#edit_title").val();
                var category_id = $("#edit_category_id").val();
                var description = $("#edit_description").val();
                $.ajax({
                    type: 'POST',
                    url: "/blog-update",
                    data: {title: title, category_id: category_id, description: description, id: id},
                    dataType: "json",
                    success: function(resultData) {
                        if (resultData.status === "success") {
                            successAlert(resultData.msg);
                            viewBlog();
                        } else {
                            errorAlert(resultData.msg);
                        }
                    }
                });
            }
        }

        function deleteBlog(id){
            $.ajax({
                type: 'DELETE',
                url: "/blog-delete/" + id,
                dataType: "json",
                success: function(resultData) {
                    if (resultData.status === "success") {
                        successAlert(resultData.msg);
                        viewBlog();
                    } else {
                        errorAlert(resultData.msg);
                    }
                }
            });
        }
    </script>
@endpush
