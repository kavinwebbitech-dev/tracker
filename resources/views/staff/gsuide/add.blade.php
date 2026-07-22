@extends('layouts.staff')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create GSuite Hosting</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('staff.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">GSuite</li>
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
				
				<div class="col-lg-12 col-12">
					
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
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('staff.gsuide.store') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Branches</label>
											<div class="input-group mb-3">
												<select class="form-control select2" id="fld_branch_id" name="fld_branch_id">
													@if($branches)
													@foreach($branches as $key => $branch)
					                              	<option value="{{ $branch->id }}">{{ $branch->fld_branch_name }}</option>
					                              	@endforeach
					                              	@endif
					                          </select>
											</div>
											@error('fld_branch_id')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Customers List</label>
											<div class="input-group mb-3">
												<select class="form-control select2" id="fld_cust_id" name="fld_cust_id" onchange="CustomerDetails()">
													<option value="">Select Person</option>
													@if($customers)
													@foreach($customers as $key => $users)
					                              	<option value="{{ $users->id }}" data-name="{{ $users->fld_name }}" data-email="{{ $users->fld_email }}" data-cname="{{ $users->fld_company_name }}">{{ $users->fld_name }} ({{ $users->fld_phone }})</option>
					                              	@endforeach
					                              	@endif
					                          </select>
											</div>
											@error('fld_cust_id')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('cname') is-invalid @enderror" name="cname" id="cname" placeholder="Name" value="{{ old('cname') }}" readonly>
											</div>
											@error('cname')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Email</label>
											<div class="input-group mb-3">
												<input type="email" class="form-control @error('cemail') is-invalid @enderror" name="cemail" id="cemail" placeholder="Email" value="{{ old('cemail') }}" readonly>
											</div>
											@error('cemail')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Company Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('ccname') is-invalid @enderror" name="ccname" id="ccname" placeholder="Company Name" value="{{ old('ccname') }}" readonly>
											</div>
											@error('ccname')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Domain Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('fld_domain_name') is-invalid @enderror" name="fld_domain_name" placeholder="Domain Name" value="{{ old('fld_domain_name') }}" required>
											</div>
											@error('fld_domain_name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Domain Register Date</label>
											<div class="input-group mb-3">
												<input type="date" class="form-control @error('fld_gsuite_start_date') is-invalid @enderror" name="fld_gsuite_start_date" value="{{ old('fld_gsuite_start_date') }}" id="fld_gsuite_start_date" required>
											</div>
											@error('fld_gsuite_start_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Years</label>
											<div class="input-group mb-3">
												<select class="form-control select2 @error('fld_gsuite_tenure') is-invalid @enderror" name="fld_gsuite_tenure" onchange="checkDate()" id="fld_gsuite_tenure">
													<option value="">Years</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
												</select>
											</div>
											@error('fld_gsuite_tenure')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Domain Expiry Date</label>
											<div class="input-group mb-3">
												<input type="date" class="form-control @error('fld_gsuite_end_date') is-invalid @enderror" name="fld_gsuite_end_date" value="{{ old('fld_gsuite_end_date') }}" id="fld_gsuite_end_date" required>
											</div>
											@error('fld_domain_end_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Tax Rate(%)</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('fld_tax_percentage') is-invalid @enderror" name="fld_tax_percentage" id="fld_tax_percentage" placeholder="Tax Rate" value="{{ old('fld_tax_percentage') }}" required>
											</div>
											@error('fld_tax_percentage')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Amount</label>
											<div class="input-group mb-3">

												<input type="text" class="form-control @error('fld_amount') is-invalid @enderror" name="fld_amount" id="fld_amount" placeholder="Amount" value="{{ old('fld_amount') }}" required onkeyup="TaxCalculate()">
											</div>
											@error('fld_amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Total Amount</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('fld_total_amount') is-invalid @enderror" name="fld_total_amount" id="fld_total_amount" placeholder="Total Amount" value="{{ old('fld_total_amount') }}" readonly>
											</div>
											@error('fld_total_amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-8">
										<h4 class="text-success">Email List</h4>
						                <div class="col-12 text-center" id="dynamicTable">
						                    <div class="row mb-3">
						                        <div class="col-md-5">
						                            <input type="email" name="addmore[0][images]" class="form-control" multiple>
						                        </div>
						                        <div class="col-md-3">
						                            <input type="date" name="addmore[0][price]" class="form-control" />
						                        </div>
						                        <div class="col-md-2">
						                            <input type="text" name="addmore[0][stock]" class="form-control inputdecimal" />
						                        </div>
						                        <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
						                    </div>
						                </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Description</label>
											<div class="input-group mb-3">
												<textarea type="text" class="form-control @error('fld_description') is-invalid @enderror" name="fld_description" placeholder="Description" rows="4"></textarea>
											</div>
											@error('fld_description')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
								</div>
								
									
							</div>
							<!-- /.box-body -->
							<div class="box-footer text-end">
								<input type="submit" name="submit" class="btn btn-primary" value="Submit">
								<!-- <button type="submit" class="btn btn-primary">
								  <i class="ti-save-alt"></i> Save
								</button> -->
							</div>  
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
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  
  <script type="text/javascript">
  	
  	function TaxCalculate() {
  		var fld_amount = $('#fld_amount').val();
  		var fld_tax_percentage = $('#fld_tax_percentage').val();
  		var tax_cal = (fld_amount * fld_tax_percentage) / 100;
  		
  		const sum = parseFloat(fld_amount) + parseFloat(tax_cal);
  		$("#fld_total_amount").val(sum);
  		
  	}
  	
    var selection = document.getElementById("fld_cust_id");

	selection.onchange = function(event){
	  var name = event.target.options[event.target.selectedIndex].dataset.name;
	  var cname = event.target.options[event.target.selectedIndex].dataset.cname;
	  var email = event.target.options[event.target.selectedIndex].dataset.email;
	  $("#cname").val(name);
	  $("#cemail").val(email);
	  $("#ccname").val(cname);
	};
  </script>
<script type="text/javascript">
    var i = 0;
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<div class="row dynamicrow mb-3">\
        <div class="col-md-5">\
            <input type="email" name="addmore['+i+'][images]" class="form-control" multiple>\
        </div>\
        <div class="col-md-3"><input type="date" name="addmore['+i+'][price]"  class="form-control" /></div>\
        <div class="col-md-2"><input type="text" name="addmore['+i+'][stock]"  class="form-control" /></div>\
        <div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>\
        </div>');
    });
    $(document).on('click', '.remove-tr', function(){  
        $(this).parents('.dynamicrow').remove();
    });
    
</script>
<script type="text/javascript">
	function checkDate() {
		var gsuite_tenure = document.getElementById("fld_gsuite_tenure").value;
        var gsuite_reg_date = $.trim($("#fld_gsuite_start_date").val());                      
       	
        var arr = gsuite_reg_date.split("-");

        var year=arr[0];

        var month=arr[1];

        var day=arr[2];

        var gsuite_reg_date= parseFloat(year)+parseFloat(gsuite_tenure);

        var da = gsuite_reg_date +'-'+month+'-'+day;

        $("#fld_gsuite_end_date").val(da);
	}
</script>
@endsection