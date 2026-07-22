@extends('layouts.sub_admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit AMC Hosting</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">AMC</li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
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
						<form class="form" action="{{ route('sub_admin.amc.update', $domainhosting->id) }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Branches</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control select2" id="fld_branch_id" name="fld_branch_id">
													@if($branches)
													@foreach($branches as $key => $branch)
					                              	<option value="{{ $branch->id }}" @if($domainhosting->fld_branch_id == $branch->id) selected @endif>{{ $branch->fld_branch_name }}</option>
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
											<div class="input-group in-bord mb-3">
												<select class="form-control select2" id="fld_cust_id" name="fld_cust_id" onchange="CustomerDetails()">
													<option value="">Select Person</option>
													@if($customers)
													@foreach($customers as $key => $users)
					                              	<option value="{{ $users->id }}" data-name="{{ $users->fld_name }}" data-email="{{ $users->fld_email }}" data-cname="{{ $users->fld_company_name }}"@if($domainhosting->fld_cust_id == $users->id) selected @endif>{{ $users->fld_name }} ({{ $users->fld_phone }}) - {{ $users->fld_company_name }}</option>
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
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('cname') is-invalid @enderror" name="cname" id="cname" placeholder="Name" value="{{ $customer_details->fld_name }}" readonly>
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
											<div class="input-group in-bord mb-3">
												<input type="email" class="form-control @error('cemail') is-invalid @enderror" name="cemail" id="cemail" placeholder="Email" value="{{ $customer_details->fld_email }}" readonly>
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
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('ccname') is-invalid @enderror" name="ccname" id="ccname" placeholder="Company Name" value="{{ $customer_details->fld_company_name }}" readonly>
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
											<label class="form-label">Details</label>
											<div class="input-group in-bord mb-3">
												<textarea type="text" class="form-control @error('fld_amc_dh_details') is-invalid @enderror" name="fld_amc_dh_details" placeholder="Details" required>{{ $domainhosting->fld_amc_dh_details }}</textarea>
											</div>
											@error('fld_amc_dh_details')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Register Date</label>
											<div class="input-group in-bord mb-3">
												<input type="date" class="form-control @error('fld_amc_reg_date') is-invalid @enderror" name="fld_amc_reg_date" value="{{ $domainhosting->fld_amc_reg_date }}" id="fld_amc_reg_date" required>
											</div>
											@error('fld_amc_reg_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Tenure(In Month)</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control select2 @error('fld_amc_dh_tenure') is-invalid @enderror" name="fld_amc_dh_tenure" id="fld_amc_dh_tenure" onchange="checkDate()">
													<option value="">Month</option>
													<option value="1" @if($domainhosting->fld_amc_dh_tenure == 1) selected @endif>1</option>
													<option value="2" @if($domainhosting->fld_amc_dh_tenure == 2) selected @endif>2</option>
													<option value="3" @if($domainhosting->fld_amc_dh_tenure == 3) selected @endif>3</option>
													<option value="4" @if($domainhosting->fld_amc_dh_tenure == 4) selected @endif>4</option>
													<option value="5" @if($domainhosting->fld_amc_dh_tenure == 5) selected @endif>5</option>
													<option value="6" @if($domainhosting->fld_amc_dh_tenure == 6) selected @endif>6</option>
													<option value="7" @if($domainhosting->fld_amc_dh_tenure == 7) selected @endif>7</option>
													<option value="8" @if($domainhosting->fld_amc_dh_tenure == 8) selected @endif>8</option>
													<option value="9" @if($domainhosting->fld_amc_dh_tenure == 9) selected @endif>9</option>
													<option value="10" @if($domainhosting->fld_amc_dh_tenure == 10) selected @endif>10</option>
													<option value="11" @if($domainhosting->fld_amc_dh_tenure == 11) selected @endif>11</option>
													<option value="12" @if($domainhosting->fld_amc_dh_tenure == 12) selected @endif>12</option>
												</select>
											</div>
											@error('fld_amc_dh_tenure')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Expiry Date</label>
											<div class="input-group in-bord mb-3">
												<input type="date" class="form-control @error('fld_amc_end_date') is-invalid @enderror" name="fld_amc_end_date" value="{{ $domainhosting->fld_amc_end_date }}" id="fld_amc_end_date" required>
											</div>
											@error('fld_amc_end_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Tax Rate(%)</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('fld_amc_tax_rate') is-invalid @enderror" name="fld_amc_tax_rate" id="fld_amc_tax_rate" placeholder="Tax Rate" value="{{ $domainhosting->fld_amc_tax_rate }}" required>
											</div>
											@error('fld_amc_tax_rate')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Amount</label>
											<div class="input-group in-bord mb-3">

												<input type="text" class="form-control @error('fld_amc_amount') is-invalid @enderror" name="fld_amc_amount" id="fld_amc_amount" placeholder="Amount" value="{{ $domainhosting->fld_amc_amount }}" required onkeyup="TaxCalculate()">
											</div>
											@error('fld_amc_amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Total Amount</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('fld_amc_total_amount') is-invalid @enderror" name="fld_amc_total_amount" id="fld_amc_total_amount" placeholder="Total Amount" value="{{ $domainhosting->fld_amc_total_amount }}" readonly>
											</div>
											@error('fld_amc_total_amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-8">
										<h4 class="text-success">Payment List</h4>
										<div class="row" id="dynamicTable">
											<div class="col-12 text-center">
												@if(count($domainhosting->payment_list) > 0)
                        						@foreach($domainhosting->payment_list as $key => $varient)
							                    <div class="row mb-3">
							                        <div class="col-md-5">
							                            <input type="text" name="addmore[0][images]" class="form-control" value="{{ $varient->fld_amc_payment_amount }}">
							                        </div>
							                        <div class="col-md-3">
							                            <input type="date" name="addmore[0][price]" class="form-control" value="{{ $varient->fld_amc_payment_date }}">
							                        </div>
							                        <div class="col-md-2">
							                            <input type="text" name="addmore[0][stock]" class="form-control inputdecimal" value="{{ $varient->fld_amc_payment_desc }}">
							                        </div>
							                        @if($key == 0)
							                        <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
							                        @else
							                        <div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>
							                        @endif
							                    </div>
							                    @endforeach
							                    @else
							                    <div class="row mb-3">
							                        <div class="col-md-5">
							                            <input type="text" name="addmore[0][images]" class="form-control" value="">
							                        </div>
							                        <div class="col-md-3">
							                            <input type="date" name="addmore[0][price]" class="form-control" value="">
							                        </div>
							                        <div class="col-md-2">
							                            <input type="text" name="addmore[0][stock]" class="form-control inputdecimal" value="">
							                        </div>
							                        <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
							                    </div>
							                    @endif
							                </div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Description</label>
											<div class="input-group in-bord mb-3">
												<textarea type="text" class="form-control @error('fld_amc_description') is-invalid @enderror" name="fld_amc_description" placeholder="Description" rows="4">{{ $domainhosting->fld_amc_description }}</textarea>
											</div>
											@error('fld_amc_description')
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
            <input type="text" name="addmore['+i+'][images]" class="form-control" multiple>\
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
		var amc_dh_tenure = document.getElementById("fld_amc_dh_tenure").value;
        var amc_reg_date = $.trim($("#fld_amc_reg_date").val());                                 
        var newDate = moment(amc_reg_date, "YYYY-MM-DD").add(amc_dh_tenure, 'months').format('YYYY-MM-DD');
        $("#fld_amc_end_date").val(newDate);
	}
	
</script>
@endsection