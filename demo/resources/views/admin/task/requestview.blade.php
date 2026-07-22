@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Request Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Task</li>
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
            .modal-dialog
            {
                max-width: 1200px;
            }
        </style>
        <style>
            .content1
            {
                font-size: 14px;
                font-weight: 400;
                color: #000;
                max-width: 100%;
                 display: -webkit-box;
                text-overflow: ellipsis;
                -webkit-line-clamp: 5;
                -webkit-box-orient: vertical;
                overflow: hidden;
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
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-9" style="border-right: 1px solid #d3d3d3;">
                                        <div class="row">
                                            <div class="col-md-3 col_padding">Name</div>
                                            <div class="col-md-9 col_padding"> - {{ $client_task->name }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col_padding">Date</div>
                                            <div class="col-md-9 col_padding"> - {{ date('d-m-Y', strtotime($client_task->date)) }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col_padding">Description</div>
                                            <div class="col-md-9 col_padding">
                                                @if($client_task->description)
                                                <div class="content1">
                                                    {!! $client_task->description !!}
                                                </div>
                                                <span onclick="ReadMore('{{ $client_task->id }}')" style="cursor: pointer;color: #00afef;font-size: 14px;">Read More</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        
                                    </div>

                                </div>

                            </div>

                            <div class="col-12 col-md-12">

                                <div class="table-responsive">
                                  <table class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                    <thead>
                                        <tr>
                                            <th>S No</th>
                                            <th>Key Points</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Estimate Time</th>
                                            <th style="width: 403px;">Client Comment</th>
                                            <th class="noExport">Status</th>
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @if($client_task->client_task_details)
                                        @foreach($client_task->client_task_details as $key => $value)
                                        <?php
                                            // dump($value);
                                            $task_comments = \App\Models\ClientTaskComment::where('client_task_id', $value->client_task_id)->where('client_task_details_id', $value->id)->where('user_id', $value->user_id)->orderBy('id', 'DESC')->first();
                                            $task_comments1 = \App\Models\ClientTaskComment::where('client_task_id', $value->client_task_id)->where('client_task_details_id', $value->id)->first();
                                            if ($value->amount) {
                                                // code...
                                            }
                                            else
                                            {
                                                $update_appro = \App\Models\ClientTaskDetails::find($value->id);
                                                $update_appro->total_amount = 0;
                                                $update_appro->save();
                                            }
                                            
                                        ?>
                                        <tr @if($value->status == "Approved") style="background: #90EE90;" @endif>
                                            <td id="request_view_{{$value->id}}" @if($value->alert_Status == 1 && $value->alert_user == 1) style="background: #00afef;" @endif>{{ $key + 1 }}</td>
                                            <td>{{ $value->name ?? '' }}</td>
                                            <td>{{ $value->category ?? '' }}</td>
                                            <td>
                                                @if($value->amount_type == "fixed_amount")
                                                    @if($value->amount) ₹ {{ $value->amount ?? '' }} @endif
                                                @else
                                                    @if($value->amount) $ {{ $value->amount ?? '' }} <span style="font-size: 10px;">( Per Hour)</span> @endif
                                                @endif
                                            </td>
                                            <td>{{ $value->estimate_time ?? '' }} {{ $value->estimate_type ?? '' }}</td>
                                            <td>
                                                @if($value->status == "Approved")
                                                    Client Approved
                                                @elseif(isset($task_comments) && $task_comments->comments)
                                                    {!! $task_comments->comments !!}
                                                @endif
                                            </td>
                                            <td>{{ $value->status ?? '' }}</td>
                                            <td>
                                                <p style="display: flex;margin-bottom: 0px;">
                                                    <a onclick="TaskComments('{{ $value->id }}')" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                                                    <a href="{{ route('admin.projects.task.details.delete', $value->id) }}" class="delete btn btn-danger" data-confirm="Are you Sure Delete this Point?" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                                </p>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        
                                    </tbody>

                                    </table>
                                    
                                </div>
                                

                            </div>

                            <div class="row">
                                <form class="" action="{{ route('admin.task.request.extra.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="client_task_id" value="{{ $client_task->id }}">
                                    <h4 class="text-success">Add Extra Key Point</h4>
                                    <div class="col-12 text-center" id="dynamicTable">
                                        <div class="row mb-3">
                                            <div class="col-md-10">
                                                <textarea name="addmore[0][points]" class="form-control" rows="2" style="width: 100%;"></textarea>
                                            </div>
                                            <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
                                        </div>
                                    </div>

                                    <div class="box-footer text-left">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                        <!-- <button type="submit" class="btn btn-primary">
                                          <i class="ti-save-alt"></i> Save
                                        </button> -->
                                    </div>
                                </form>
                            </div>

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
        function TaskComments(id) {
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('admin.task.request.update') }}",
                    type: "POST",
                    data: {
                        id: project_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        
                        $('#modal-default').modal('show');
                        $("#load_html1").html(response.html);

                        var request_view = document.getElementById(response.request_view);

                        if (response.background) 
                        {
                            request_view.style.background = "white";
                        }

                        var script = document.createElement('script');
                        script.src = '<?php echo url('');?>/public/admin_assets/js/pages/editor.js';
                        script.onload = function() {
                            // Reinitialize CKEditor after the script is loaded
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .catch(error => {
                                    console.error(error);
                                });
                        };
                        document.head.appendChild(script);
                    }
                });
            }

        }
    </script>
  
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Key Point Update</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html1">
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
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
        });
    </script>

    <script type="text/javascript">
        var i = 0;
        $("#add").click(function(){
            ++i;
            $("#dynamicTable").append('<div class="row dynamicrow mb-3">\
            <div class="col-md-10">\
                <textarea name="addmore['+i+'][points]" class="form-control" rows="2" style="width: 100%;"></textarea>\
            </div>\
            <div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>\
            </div>');
        });
        $(document).on('click', '.remove-tr', function(){  
            $(this).parents('.dynamicrow').remove();
        });
        
    </script>
    
    <div class="modal fade" id="modal-default1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Task Estimate Description</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html2">

            

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
        
        function ReadMore(id)
        {
            
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('admin.task.request.description') }}",
                    type: "POST",
                    data: {
                        id: project_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        
                        $('#modal-default1').modal('show');
                        $("#load_html2").html(response.html);

                    }
                });
            }
        }
    </script>
    
@endsection