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
                    <h3 class="page-title">GSuite</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Sub Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">GSuite</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        <style type="text/css">
            /*switch*/
            .aiz-switch input:empty {
                height: 0;
                width: 0;
                overflow: hidden;
                position: absolute;
                opacity: 0;
            }
            .aiz-switch input:empty ~ span {
                display: inline-block;
                position: relative;
                text-indent: 0;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                line-height: 24px;
                height: 21px;
                width: 40px;
                border-radius: 12px;
            }
            .aiz-switch input:empty ~ span:after,
            .aiz-switch input:empty ~ span:before {
                position: absolute;
                display: block;
                top: 0;
                bottom: 0;
                left: 0;
                content: " ";
                -webkit-transition: all 0.1s ease-in;
                transition: all 0.1s ease-in;
                width: 40px;
                border-radius: 12px;
            }
            .aiz-switch input:empty ~ span:before {
                background-color: #b0dcda;
            }
            .aiz-switch input:empty ~ span:after {
                height: 17px;
                width: 17px;
                line-height: 20px;
                top: 2px;
                bottom: 2px;
                margin-left: 2px;
                font-size: 0.8em;
                text-align: center;
                vertical-align: middle;
                color: #f8f9fb;
                background-color: #fff;
            }
            .aiz-switch input:checked ~ span:after {
                background-color: var(--primary);
                margin-left: 20px;
            }
            .aiz-switch-secondary input:checked ~ span:after {
                background-color: var(--secondary);
            }
            .aiz-switch-success input:checked ~ span:after {
                background-color: #fff;
            }
            .aiz-switch-success input:checked ~ span:after {
                background-color: #ff5900;
            }
            .aiz-switch-info input:checked ~ span:after {
                background-color: var(--info);
            }
            .aiz-switch-warning input:checked ~ span:after {
                background-color: var(--warning);
            }
            .aiz-switch-secondary-base input:checked ~ span:after {
                background-color: var(--secondary-base);
            }
            .aiz-switch-danger input:checked ~ span:after {
                background-color: var(--danger);
            }
            .aiz-switch-light input:checked ~ span:after {
                background-color: var(--light);
            }
            .aiz-switch-dark input:checked ~ span:after {
                background-color: var(--dark);
            }
            .aiz-switch-blue input:checked ~ span:after {
                background-color: var(--blue);
            }
            .box_color{
                text-align: center;
                background: #00afef;
                height: 90px;
                border-radius: 10px;
                padding: 22px !important;
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
                    
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-sm-4">
                            <div class="three columns"> 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <span style="margin-top: 3px;    position: absolute;">
                                            Filter By (<30 Days)
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="aiz-switch aiz-switch-success">
                                            <input type="checkbox" name="permissions" class="toggle-input form-control" value="1">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(in_array('30', $role_section))
                        <div class="col-sm-8">
                            <a class="btn btn-primary" href="{{ route('sub_admin.gsuide.create') }}" style="float: right;">Add New</a>
                        </div>
                        @endif
                    </div>

                    <style type="text/css">
                        .add_button
                        {
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="table-responsive">
                      <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Domain Name</th>
                                <th>Expiry Date</th>
                                <th>Days</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_amount = 0; ?>
                            @if($domainhosting)
                            @foreach($domainhosting as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->fld_domain_name }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->fld_gsuite_end_date)) }}</td>
                                <td>
                                    <?php
                                        $gsuite_end_date = $value->fld_gsuite_end_date;
                                        $today=date('Y-m-d');
                                        $date1=date_create($today);
                                        $date2=date_create($gsuite_end_date);
                                        $diff=date_diff($date1,$date2);
                                        $gsuite_plus_days = $diff->format("%R%a");
                                        $gsuite= $diff->format("%R%a days");
                                        // dd($gsuite);
                                        if($gsuite_plus_days >= 1){
                                          $gsuite_days = $gsuite;
                                        }
                                        else
                                        {

                                          $gsuite_days='0'; 
                                        }

                                        if ($gsuite_plus_days >= 1)
                                        {
                                            $gsuite_days_show = $gsuite_days;
                                        }
                                        else
                                        {
                                            $gsuite_days_show = "0 Days";
                                        }
                                        $total_amount += $value->fld_total_amount;
                                        // $toDate = \Carbon\Carbon::parse(date('Y-m-d'));
                                        // $fromDate = \Carbon\Carbon::parse($value->fld_gsuite_end_date);
                                  
                                        // $days = $toDate->diffInDays($fromDate);
                                    ?>
                                    {{ $gsuite_days_show }}
                                </td>
                                <td>
                                    {{ $value->fld_total_amount }}
                                </td>
                                <td>
                                    <p style="display: flex;">
                                        @if(in_array('35', $role_section))
                                        <a href="{{ route('sub_admin.gsuide.interest', $value->id) }}" data-confirm="Are you Sure Move Interest?" title="Interest" class="delete btn btn-warning" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-link text-white"></i></a>
                                        @endif
                                        @if(in_array('36', $role_section))
                                        <a onclick="CustomerDetails('{{$value->fld_cust_id}}')" title="Customer Details" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-user text-white"></i></a>
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
                                <td></td>
                                <td>{{ $total_amount }}</td>
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
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $domainhosting->withQueryString()->links('pagination::bootstrap-5') !!}
                                
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
  <?php
    $current_route = Route::currentRouteName();
  ?>
  <script type="text/javascript">

    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date      = $("#start_date").val();
        var end_date    = $("#end_date").val();
        var status           = $("#status").val();

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
        if (status == 0) 
        {
            status = 0;
            
        }
        else if (status) 
        {
            status = status;
            
        }
        else
        {
            status = "";
        }
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&status="+status;
        
  
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
        
    }

    function ViewTaskModel1(id) {
        var project_id = id;

        if (project_id) {
            $('#modal-default1').modal('show');
            $("#project_id").val(project_id);
        }
    }

    function CustomerDetails(id) {
        var project_id = id;

        if (project_id) {
            $.ajax({
                url: "{{ route('sub_admin.amc.user.details') }}",
                type: "POST",
                data: {
                    id: project_id,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    $('#modal-default').modal('show');
                    $("#load_html").html(response.html);
                }
            });
        }

    }

  </script>

<script type="text/javascript">
    $(document).ready(function() {

        $('.toggle-input').change(function() {   
                 
            table = $('#example').DataTable();
            if($(this).is(':checked')) {      

                $.fn.dataTable.ext.search.push(
                    function( settings, data, dataIndex ) {
                        return (parseFloat(data[3])<30 || parseFloat(data[6])<30)
                            ? true
                            : false
                    }     
                );                                     
            }
            else
            {
                $.fn.dataTable.ext.search.push(
                    function( settings, data, dataIndex ) {
                        return true;
                    }     
                );       
            }
            table.draw();
            $.fn.dataTable.ext.search.pop();  
        });

    });

    var deleteLinks = document.querySelectorAll('.delete');

    for (var i = 0; i < deleteLinks.length; i++) {
      deleteLinks[i].addEventListener('click', function(event) {
          event.preventDefault();

          var choice = confirm(this.getAttribute('data-confirm'));

          if (choice) {
            window.location.href = this.getAttribute('href');
          }
      });
    }

</script>

    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Customer Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

@endsection