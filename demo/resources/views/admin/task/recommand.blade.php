@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Recommend Task</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Recommend Task</li>
								<li class="breadcrumb-item active" aria-current="page">Create</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>	  

		<!-- Main content -->
		<section class="content">
			<div class="row">	
				@include('layouts.flash-message')		  
				@if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
				<div class="col-lg-12 col-12">
					
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('recommand.task.store') }}" method="post">
							@csrf
							<div class="box-body">
								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Task Type</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('task_type') is-invalid @enderror" name="task_type" id="date_type" onchange="cusom_date()">
													<option value="">Select Task Type</option>
													<option value="custom">One time Task</option>
													<option value="recurring">Recurring</option>
													<option value="recommend">Recommend</option>
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('date_type')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Task Name</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" placeholder="Task Name" value="{{ old('task_name') }}" required>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('task_name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

								</div>

								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-label">Description</label>
											<textarea id="editor1" name="editor1" rows="10" cols="80" required>
													
											</textarea>
										</div>
									</div>
								</div>

								<div class="row">
									<!-- <div class="col-md-6"></div> -->
									<div class="col-md-12">
										<div class="box-footer text-end">
											<input type="submit" name="submit" class="btn btn-primary" value="Submit">
											<!-- <button type="submit" class="btn btn-primary">
											  <i class="ti-save-alt"></i> Save
											</button> -->
										</div> 
									</div>
								</div>
								
							</div>
							<!-- /.box-body -->
							 
						</form>
					  </div>
					  <!-- /.box -->			
				</div>

		    </div>
			
		  </div>
		  <!-- /.row -->

		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <script type="text/javascript">
  	function cusom_date()
    {
    	
    	var date_check = $("#date_type").val();
    	var user_type = $("#user_type").val();

    	if (user_type == "sub_admin" && date_check == "custom") {
    		$("#date_display").show();
    		$("#check_dynamic2").hide();
    		$("#check_dynamic1").hide();
    		$("#check_dynamic3").show();
    		$("#drop_display").hide();
    		$("#check_dynamic").hide();
    		$("#customer_choice_options").hide();
    	}
    	else if (user_type == "staff" && date_check == "custom") {
    		$("#drop_display").hide();
    		$("#date_display").show();
    		$("#check_dynamic2").show();
    		$("#check_dynamic1").show();
    		$("#check_dynamic3").hide();
    		$("#customer_choice_options").hide();
    	}
    	else if (user_type == "sub_admin" && date_check == "recurring") {
    		$("#date_display").hide();
    		$("#drop_display").show();
    		$("#check_dynamic2").hide();
    		$("#check_dynamic1").hide();
    		$("#check_dynamic").show();
    		$("#check_dynamic3").hide();
    		$("#customer_choice_options").hide();
    		var user_type = 'sub_admin';
    		$("#staff_id_1").html('');
    		$.ajax({
                url: "{{ route('admin.staff.get') }}",
                type: "POST",
                data: {
                    user_type: user_type,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                	console.log(response);
                	$("#staff_id_1").append(response);

                }
            });
    	}
    	else if (user_type == "staff" && date_check == "recurring") {
    	    $("#date_display").hide();
    		$("#drop_display").show();
    		$("#check_dynamic2").hide();
    		$("#check_dynamic1").hide();
    		$("#check_dynamic").show();
    		$("#check_dynamic3").hide();
    		$("#customer_choice_options").hide();
    		var user_type = 'staff';
    		$("#staff_id_1").html('');
    		$.ajax({
                url: "{{ route('admin.staff.get') }}",
                type: "POST",
                data: {
                    user_type: user_type,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                	console.log(response);
                	$("#staff_id_1").append(response);

                }
            });
    	}
    	else
    	{
    		// alert("Hi");
    		$("#date_display").hide();
    		$("#drop_display").hide();
    		$("#check_dynamic2").hide();
    		$("#check_dynamic1").hide();
    		$("#check_dynamic").hide();
    		$("#customer_choice_options").hide();
    		$("#check_dynamic3").hide();
    	}
    	
    }

    // function sub_admin_check() {

    // }

    function sub_admin_check() {

    	var sub_admin_id = $("#sub_admin_id").val();

    	if (sub_admin_id) {
    		$('.inner li').remove();
			$.ajax({
                url: "{{ route('admin.staff.check') }}",
                type: "POST",
                data: {
                    id: sub_admin_id,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                	console.log(response);
                	// $('#multiple_staff').empty();

                	var obj = JSON.parse(response.html);
                	var obj1 = JSON.parse(response.staff);
                	
                	if (obj) {
                		$("#check_dynamic2").show();
	                	$('#multiple_staff').append(obj);
	                	$('.inner').append(obj1);
                	}

                }
            });
		}
    }

    // $(document).ready(function() {
	//     $('.multiple_staff button').click(function() {
	//         $('.inner').show(); 
	//         $('.dropdown-menu.open').show(); 
	//     });
	// });

    function add_more_customer_choice_option(i, text) {
    	$('#customer_choice_options').show();
    	$('#customer_choice_options').append('\
            <div class="form-group row">\
                <div class="col-md-3">\
                	<label class="form-label">Name</label>\
                	<input type="hidden" name="addmore['+i+'][id]" value="'+i+'">\
                    <input type="text" class="form-control" name="addmore['+i+'][name]" value="'+text+'">\
                </div>\
                <div class="col-md-4">\
                	<label class="form-label">Start Date</label>\
                	<input type="date" class="form-control" name="addmore['+i+'][start_date]" placeholder="test">\
                </div>\
                <div class="col-md-4">\
                	<label class="form-label">End Date</label>\
                	<input type="date" class="form-control" name="addmore['+i+'][end_date]" placeholder="test1">\
                </div>\
            </div>');
    }
    
    $('#task_sub_admin').on('change', function() {
        $('#customer_choice_options').html(null);
        var admin_name = $(this).find("option:selected").text();

        add_more_customer_choice_option($(this).val(), admin_name);
    });

    $(document).ready(function() {
	    $('#multiple_staff').on('change', function() {
	        var selectedValue = $(this).val();
	        $('#customer_choice_options').html(null);
	        $.each($("#multiple_staff option:selected"), function(){
	            add_more_customer_choice_option($(this).val(), $(this).text());
	        });
	        console.log('Selected Value on Open: ' + selectedValue);
	    });
	});



  </script>
@endsection