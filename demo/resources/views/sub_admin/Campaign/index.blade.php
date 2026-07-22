@extends('layouts.sub_admin')

@section('content')
<?php
    $role_section = json_decode(Auth::user()->permissions);
    if ($role_section) {
        $role_section = $role_section;
    }
    else
    {
        $role_section = [];
    }
?>
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
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
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
                    <form action="{{ route('sub_admin.customers.bulk.store') }}" method="post" enctype="multipart/form-data">
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
                            @if(in_array('96', $role_section))
                            <a href="{{ route('sub_admin.campaign.details.add') }}" class="btn btn-primary" style="float: right;">Run New Campaign</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search1" class="form-control" onkeyup="TextValueSearch()" style="margin-bottom: 20px;">
                        </div>
                    </div>
                    </form>
                    <div class="table-responsive">
                    <table id="example_render" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
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
                                <td>
                                    {{ $key + 1}}
                                </td>
                                <td>{{ $value->campaign_name }}</td>
                                <td>
                                    <img src="{{ $value->campaign_image }}" alt="" style="width: 100px;">
                                </td>
                                <td>{{ $value->campaign_content }}</td>
                                <td>{{ $value->campaign_user }}</td>
                                <td>
                                    @if(in_array('97', $role_section))
                                    <p style="display: flex;">
                                        <a href="{{ route('sub_admin.campaign.details.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                    </p>
                                    @endif
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
  
@endsection