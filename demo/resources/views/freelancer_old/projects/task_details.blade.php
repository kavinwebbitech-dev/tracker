@extends('layouts.freelancer')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Task Details</h3>
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
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Name</div>
                                            <div class="col-md-6 col_padding">- {{ $client_task->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Date</div>
                                            <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($client_task->date)) }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($client_task->amount != "")
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Amount</div>
                                            <div class="col-md-6 col_padding">- ₹ {{ $client_task->amount }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Description</div>
                                            <div class="col-md-6 col_padding">{!! $client_task->description !!}</div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="col-12 col-md-12">

                                <div class="table-responsive">
                                  <table class="table table-bordered table-hover display nowrap margin-top-10 w-p100" style="table-layout: fixed !important;">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">S No</th>
                                            <th>Key Points</th>
                                            <th class="noExport" style="width: 100px;">Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @if($client_task->client_task_details)
                                        @foreach($client_task->client_task_details as $key => $value)
                                        <tr id="key-{{ $value->id }}" @if($value->status == "Approved") style="background: #9ff39f;" @endif>
                                            <td id="request_view_{{$value->id}}" @if($value->alert_Status == 1 && $value->alert_user == Auth::user()->id) style="background: #00afef;" @endif>{{ $key + 1 }}</td>
                                            <td>{{ $value->name ?? '' }}</td>
                                            <td>
                                                <p style="display: flex;margin-bottom: 0px;">
                                                    <a onclick="TaskComments('{{ $value->id }}')" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                                                </p>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        
                                    </tbody>

                                    </table>
                                    
                                </div>
                                

                            </div>

                            {{-- <div class="row">
                                <form class="" action="{{ route('freelancer.projects.extra.store') }}" method="post">
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
                                    </div>
                                </form>
                            </div> --}}

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="text/javascript">
        function TaskComments(id) {
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('freelancer.task.request.update') }}",
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
@endsection