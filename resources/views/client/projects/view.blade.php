@extends('layouts.client')

<?php
    $page_user_tile = "Project List - ".$user_title_name;
?>

@section('meta_name') {{ $page_user_tile }} @stop

@section('content')


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
                                <li class="breadcrumb-item"><a href="{{ route('client.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Client</li>
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
                    
                    <!-- <form action="{{ route('admin.projects.filter') }}" method="post"> -->
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-2">
                            <!-- <a href="" id="image_url" class="btn btn-success" target="_blank">Report Download</a> -->
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search" class="form-control" onkeyup="text_value_search()" style="margin-bottom: 10px;">
                        </div>
                    </div>
                    <!-- </form> -->
                    <style type="text/css">
                        .add_button
                        {
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Project</th>
                                <th>Total Amount</th>
                                <th>Pending</th>
                                <th>Confirm Date</th>
                                <th class="noExport">Status</th>
                                <th class="noExport">Action</th>
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
                            // dd($value->added_user_details);
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
                                $total_pening += $total_penc;
                            ?>
                            <tr @if($value->status == 6) style="background: #dc3545;" @endif>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->name }}</td>
                                <td>
                                    {{ $value->bid_amount }}
                                </td>
                                <td>
                                    {{ $total_penc }}
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
                                        <a href="{{ route('client.projects.view.details', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            
                        </tbody>

                        {{-- <tfoot>
                            <tr>
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
                        </tfoot> --}}
                        </table>
                        <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
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
  <!-- /.content-wrapper -->
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <?php
    $current_route = Route::currentRouteName();
    $current_route1 = route('client.download.projects.view');
    $current_route2 = route('client.completed.payment.project');
    $current_route3 = route('client.projects.cancel');
  ?>

  <script type="text/javascript">

    function text_value_search()
    {
        var text_value_search = $("#text_value_search").val();
        var start_date        = $("#start_date").val();
        var end_date          = $("#end_date").val();
        var salesperson       = $("#salesperson").val();
        var service           = $("#service").val();
        var status            = $("#status").val();

        if (text_value_search) {
            $.ajax({
                url: "{{ route('client.projects.search.view') }}",
                type: "POST",
                data: {
                    text_value_search: text_value_search,
                    start_date: start_date,
                    end_date: end_date,
                    salesperson: salesperson,
                    status: status,
                    service: service,
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
            // var pdf_url = expert_search_url1 + '?' + str_search_request
            // $('#image_url').attr('href', pdf_url);
            window.location.href = expert_search_url + '?' + str_search_request;
        }

        
        
    }

    function PdfDownload() 
    {
        var expert_search_url1  = "{{ $current_route1 }}";
        var expert_search_url2  = "{{ $current_route2 }}";
        var expert_search_url3  = "{{ $current_route3 }}";

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
            var pdf_url = expert_search_url1 + '?' + str_search_request
            var pdf_url1 = expert_search_url2 + '?' + str_search_request
            var pdf_url2 = expert_search_url3 + '?' + str_search_request
            $('#image_url').attr('href', pdf_url);
            $('#image_url1').attr('href', pdf_url1);
            $('#image_url2').attr('href', pdf_url2);
        }

    }

    function ViewTaskModel1(id) {
        var project_id = id;

        if (project_id) {
            $('#modal-default1').modal('show');
            $("#project_id").val(project_id);
        }
    }

    function ViewTaskModel(id) {
        var project_id = id;

        if (project_id) {
            $.ajax({
                url: "{{ route('client.projects.payment.details') }}",
                type: "POST",
                data: {
                    id: project_id,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    $('#modal-default').modal('show');
                    $("#load_html1").html(response.html);
                }
            });
        }

    }

    PdfDownload();

  </script>

  <div class="modal fade" id="modal-default1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Payment Update</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('admin.projects.payment.update') }}" method="post">
                @csrf
                <input type="hidden" name="project_id" id="project_id">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Payment Date</label>
                            <div class="input-group in-bord mb-3">
                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date') }}" required>
                            </div>
                            @error('payment_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Payment Amount</label>
                            <div class="input-group in-bord mb-3">
                                <input type="text" class="form-control @error('payment_amount') is-invalid @enderror" name="payment_amount" value="{{ old('payment_amount') }}" required>
                            </div>
                            @error('payment_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </form>
          </div>
          <!-- <div class="modal-footer">
            <span class="btn btn-danger" data-bs-dismiss="modal">Close</span>
          </div> -->
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Payment Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html1">
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

@endsection