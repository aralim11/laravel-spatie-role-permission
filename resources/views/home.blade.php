@extends('layouts.app')

@section('title', 'Home')

@push('style')
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/> 
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        @foreach ($blogs as $blog)
                        <div class="col-sm-4">
                            <div class="card m-t-10">
                              <div class="card-body">
                                <h4 class="card-title">{{ substr($blog->title, 0, 35) }}</h4>
                                <small class="text-muted cat">
                                  <i class="far fa-clock text-info"></i> {{ getTime($blog->created_at) }}
                                  <i class="fas fa-users text-info"></i> {{ getBlogCategory($blog->category_id) }}
                                </small>
                                <p class="card-text">{{ substr($blog->description, 0, 200) }}</p>
                                <a class="btn btn-info" onclick="openViewModal({{ $blog->id }})">Read More</a>
                              </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function openViewModal(id){
            Swal.fire('Please Wait. Data Processing!!');
			Swal.showLoading();
            $.ajax({
                type: 'GET',
                url: "/read-blog/" + id,
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
    </script>
@endpush
