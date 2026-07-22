@extends('layouts.dashboard')

@section('content')

<style type="text/css">
    
    table.dataTable.nowrap th, table.dataTable.nowrap td
    {
        white-space: normal;
    }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Campaign</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Campaign</li>
                                <!-- <li class="breadcrumb-item active" aria-current="page">View</li> -->
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        <style type="text/css">
          #example_filter label
          {
            display: none;
          }
          #text_search_value
          {
            display: none;
          }
        </style>
        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-12">

             <div class="box">
                @include('layouts.flash-message')
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="{{ route('admin.customers.bulk.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-7"></div>
                        <div class="col-md-3">
                            {{-- <div class="form-group">
                                <div class="input-group mb-3">
                                    <select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id[]" id="project_id" data-placeholder="Select Status">
                                        <option value="">Select Status</option>
                                        @if($lead_status)
                                        @foreach($lead_status as $key => $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>
                                @error('campaign_user')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.campaign.details.add') }}" class="btn btn-primary" style="float: right;">Run New Campaign</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div> 
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search1" class="form-control" onkeyup="TextValueSearch()" style="margin-bottom: 20px;">
                        </div>
                    </div>
                    </form>
                    <div class="table-responsive creative-table-wrapper">
                    <table id="example_render" class="table table-bordered creative-table table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 5%;">S No</th>
                                <th style="width: 20%;">Campaign Name</th>
                                <th style="width: 20%;">Campaign Image</th>
                                <th style="width: 30%;">Campaign Content</th>
                                <th style="width: 5%;">Number of Campaign user</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($campaign_details)
                            @foreach($campaign_details as $key => $value)
                            
                            <tr>
                                <td class="text-muted fw-bold sorting_1">
                                    {{ $key + 1}}
                                </td>
                                <td>
                                    <div class="icon-text-wrapper">
                                        <div class="cell-icon icon-name"><i class="bi bi-person-fill"></i></div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $value->campaign_name }}</span>
                                        </div>
                                    </div>
                                </td> 
                                <td>
                                    <img src="{{ $value->campaign_image }}" alt="" style="width: 100px;">
                                </td>
                                <td class="fw-bold">{{ $value->campaign_content }}</td>
                                <td>
                                    <div class="icon-text-wrapper"> 
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $value->campaign_user }}</span>
                                        </div>
                                    </div>
                                </td>  
                                <td>
                                    <p style="display: flex;">
                                        <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.campaign.details.delete', $value->id) }}')" class="creative-btn-action action-delete" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                      </table>
                      <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $campaign_details->withQueryString()->links('pagination::bootstrap-5') !!}
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
      
      </div>
  </div>
  <!-- /.content-wrapper -->
  
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script type="text/javascript">
    

    function ViewTaskModel1(ref) {
        var ele = ref;

        $.ajax({
            url: '{{ route('admin.customers.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id1").val(response.conference_id);
                $("#fld_name1").val(response.fld_name);
                $("#fld_email1").val(response.fld_email);
                $("#fld_phone1").val(response.fld_phone);
                $('#modal-default1').modal('show');
            }
        });

    }
  </script>

    <script type="text/javascript">

        function TextValueSearch()
        {
            var text_value_search = $("#text_value_search1").val();

            if (text_value_search) {
                $.ajax({
                    url: "{{ route('admin.customers.search') }}",
                    type: "POST",
                    data: {
                        text_value_search: text_value_search,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        
                        if (response.status) 
                        {
                            $("#example").html(response.project);
                            $("#seach_hide").hide();
                        }
                    }
                });
            }

        }

        
    </script>
@endsection