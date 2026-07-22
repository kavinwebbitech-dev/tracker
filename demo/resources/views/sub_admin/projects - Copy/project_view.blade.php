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
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Project Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Project</li>
                                <li class="breadcrumb-item active" aria-current="page">Project</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        <style type="text/css">
            .col_padding
            {
                padding: 10px;
            }
            .delete
            {
                width: 200px;
            }
            .delete1
            {
                width: 150px;
            }
        </style>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            @include('layouts.flash-message')
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="box-title">General Info</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Project Name</div>
                                            <div class="col-md-6 col_padding">- {{ $project->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Start Date</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->start_date == "0000-00-00")
                                                @else
                                                    - {{ date('d-m-Y', strtotime($project->start_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Description</div>
                                            <div class="col-md-6 col_padding">{!! $project->description !!}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">End Date</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->end_date == "0000-00-00")
                                                @else
                                                    - {{ date('d-m-Y', strtotime($project->end_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Sales Order Date</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->sales_user_date == "0000-00-00")
                                                @else
                                                    - {{ date('d-m-Y', strtotime($project->sales_user_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Status</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->status == "0")
                                                    - Pending
                                                @elseif($project->status == "1")
                                                    - On Progress
                                                @elseif($project->status == "3")
                                                    - On Hold
                                                @elseif($project->status == "5")
                                                    - Done
                                                @elseif($project->status == "6")
                                                    - Cancel
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Sales Person Name</div>
                                            <div class="col-md-6 col_padding">- {{ $project->sales_user_details->firstname ?? '' }} {{ $project->sales_user_details->lastname ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>              
                </div>

                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $ramian_amount = 0;
                                    ?>
                                    @foreach ($project->bit_amounts as $key => $value1)
                                    <?php
                                        $ramian_amount += $value1->fld_project_amount;
                                    ?>
                                    <tr>
                                        <td>{{ date('d-m-Y', strtotime($value1->fld_payment_date)) }}</td>
                                        <td>{{ $value1->fld_project_amount }}</td>
                                        <td>
                                            <p style="display: flex;margin: 3px 0px;">
                                                <a  onclick='ViewTaskModel("{{ $value1->id }}")' class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                                @if(in_array('48', $role_section))
                                                    <a href="{{ route('sub_admin.projects.amount.delete', $value1->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>Remaining Amount</td>
                                        <td>{{ $project->bid_amount - $ramian_amount }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </section>
        <!-- /.content -->
      </div>
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>

    <script type="text/javascript">
        function ViewTaskModel(id) {
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('sub_admin.projects.payment.edit.details') }}",
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
    </script>
  
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Payment Edit Details</h4>
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