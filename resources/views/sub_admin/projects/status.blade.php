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
    .count-icons{
        text-align: center;
    }
    .count-icons p{
        font-size: 16px;
        margin-bottom: 5px;
        font-weight: 400;
    }
    
    .count-icons .count-right-icon{
        margin-right: 10px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">{{ $page_title }} Project List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page_title }} Project List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-12">
                <div class="box">

                @include('layouts.flash-message')
                <!-- /.box-header -->
                <div class="box-body">
                    

                    <div class="table-responsive">
                      <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Project</th>
                                <th>Sales User Name</th>
                                <th>Total Amount</th>
                                <th>Received</th>
                                <th>Confirm Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                            $total_amount1 = 0;
                            $total_pening = 0;
                        ?>
                        <tbody>
                            @if($users)
                            @foreach($users as $key => $value)
                            <?php
                                $total_amount = 0;
                                $total_penc = 0;
                                $bit_amounts = $value->bit_amounts;
                                if (count($bit_amounts) > 0) {
                                    foreach ($bit_amounts as $key1 => $value1) {
                                        $total_amount += $value1->fld_project_amount;
                                    }
                                }
                                $total_penc = $value->bid_amount - $total_amount;
                                $total_amount1 += $value->bid_amount;
                                $total_pening += $total_amount;
                            ?>
                            <tr @if($value->status == 6) style="background: #dc3545;" @endif @if($total_penc == 0) style="background: #f6e2f7;" @endif @if($value->refunded == "refunded") style="background: #ff6464;" @endif>
                                <td>{{ $key + 1}}</td>
                                <td>
                                    {{ $value->name }}
                                    @if($value->refunded == "refunded")
                                        <span style="font-size: 10px;color: #fff;">(Refunded)</span>
                                    @endif
                                </td>
                                <td>{{ $value->sales_user_details->firstname ?? '' }} {{ $value->sales_user_details->lastname ?? '' }}</td>
                                <td>
                                    <div class="count-icons">
                                        <p>{{ $value->bid_amount }}</p>
                                    </div>
                                </td>
                                <td>
                                    {{ $total_amount }}
                                </td>
                                <td>
                                    @if($value->sales_user_date == "0000-00-00")
                                    @else
                                        {{ date('d-m-Y', strtotime($value->sales_user_date)) }}
                                    @endif
                                </td>
                                <td>
                                    @if($value->status == "0")
                                        Pending
                                    @elseif($value->status == "1")
                                        On Progress
                                    @elseif($value->status == "3")
                                        On Hold
                                    @elseif($value->status == "5")
                                        Done
                                    @elseif($value->status == "6")
                                        Cancel
                                    @endif
                                </td>
                                <td>
                                    <p style="display: flex;margin-bottom: 0px;">
                                        @if(in_array('85', $role_section))
                                        <a href="{{ route('sub_admin.projects.view.details', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                                        @endif
                                        @if(in_array('47', $role_section))
                                        <a href="{{ route('sub_admin.projects.edit', $value->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                        @endif
                                        @if(in_array('48', $role_section))
                                        <a href="javascript:void(0);"onclick="deleteProject('{{ route('sub_admin.projects.delete', $value->id) }}')" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    {{ $total_amount1 }}
                                </td>
                                <td>
                                    {{ $total_pening }}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        </table>
                        <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        <div class="row gy-4 align-items-center" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                                
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
  <?php
    $current_route = Route::currentRouteName();
  ?>
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script type="text/javascript">
      function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();
        var status              = $("#status").val();
        var salesperson         = $("#salesperson").val();
        var service             = $("#service").val();

        if (start_date) {
            start_date = start_date;
        }
        else
        {
            start_date = "";
        }
        if (end_date) 
        {
            end_date = end_date;
            
        }
        else
        {
            end_date = "";
        }
        if (status) 
        {
            status = status;
            
        }
        else
        {
            status = "";
        }
        if (salesperson) 
        {
            salesperson = salesperson;
            
        }
        else
        {
            salesperson = "";
        }
        if (service) 
        {
            service = service;
            
        }
        else
        {
            service = "";
        }
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&status="+status+"&salesperson="+salesperson+"&service="+service;
        
  
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
        
    }
  </script>
@endsection