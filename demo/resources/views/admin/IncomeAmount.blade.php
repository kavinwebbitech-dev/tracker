    @extends('layouts.dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Income Amount</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Income Amount</li>
                                <!-- <li class="breadcrumb-item active" aria-current="page">View</li> -->
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
                    <form action="{{ route('admin.imcome.amount.export') }}" method="post" enctype="multipart/form-data">
                    @csrf
                
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;">
                                Add Income Amount
                            </span>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('upload_file') is-invalid @enderror" id="start_date" onfocus="'showPicker' in this && this.showPicker()" name="start_date" placeholder="Business Name" value="{{ $start_date }}">
                                </div>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('upload_file') is-invalid @enderror" id="end_date" onfocus="'showPicker' in this && this.showPicker()" name="end_date" placeholder="Business Name" value="{{ $end_date }}">
                                </div>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-1">
                            <span class="btn btn-primary" onclick="sort_book()">Submit</span>
                            <!--<input type="submit" onclick="sort_book()" name="submit" class="btn btn-primary" value="Submit">-->
                        </div>
                        <div class="col-md-1">
                            <input type="submit" name="submit" class="btn btn-primary" value="Download">
                        </div>
                    </div>
                    
                    </form>
                    
                    <div class="table-responsive">
                      <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total_amount1 = 0;
                                $total_pening = 0;
                            ?>
                            @if($IncomeAmount)
                            @foreach($IncomeAmount as $key => $value)
                            <?php
                                $total_amount1 += $value->amount;
                            ?>
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->amount }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->income_date)) }}</td>
                                <td>{{ $value->description ?? '' }}</td>
                                <td>
                                    <a onclick="ViewTaskModel('{{ $value->id }}')" class="btn btn-primary" style="padding: 4px 12px;margin: 10px 4px;"><i class="ion ion-edit text-white"></i></a>
                                    <a href="{{ route('admin.imcome.amount.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 10px 4px;"><i class="ti-trash text-white"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td>Total</td>
                                <td>
                                    {{ $total_amount1 }}
                                </td>
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
                                {!! $IncomeAmount->withQueryString()->links('pagination::bootstrap-5') !!}
                                
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
  <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Income Amount</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form action="{{ route('admin.imcome.amount.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id">

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <div class="input-group in-bord mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}" required>
                            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Amount</label>
                        <div class="input-group in-bord mb-3">
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" placeholder="Amount" value="{{ old('amount') }}">
                            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <div class="input-group in-bord mb-3">
                            <input type="date" class="form-control @error('income_date') is-invalid @enderror" id="income_date" name="income_date" onfocus="'showPicker' in this && this.showPicker()" placeholder="Business Name" value="{{ old('income_date') }}">
                            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('income_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div class="input-group in-bord mb-3">
                            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Description" value="{{ old('description') }}">
                            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box-footer text-end">
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    </div> 
                </div>
            </form>

          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <?php
    $current_route = Route::currentRouteName();
  ?>
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script type="text/javascript">
    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('admin.imcome.amount.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id").val(response.conference_id);
                $("#name").val(response.name);
                $("#amount").val(response.amount);
                $("#income_date").val(response.income_date);
                $("#description").val(response.description);
                $('#modal-default').modal('show');
            }
        });
    }

    function ViewTaskModel1(ref) 
    {
        $("#leads_from1").val(ref);
        $('#modal-default1').modal('show');
    }

    document.getElementById("whatsapp-id").addEventListener("click", function () {
        $("#report_type").val('whatsapp')
      form.submit();
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

    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date         = $("#start_date").val();
        var end_date            = $("#end_date").val();

        if (start_date) {
            start_date = start_date;
        }
        else
        {
            start_date = "";
        }
        if (end_date) {
            end_date = end_date;
        }
        else
        {
            end_date = "";
        }
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date;
        
  
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
        
    }

  </script>
@endsection