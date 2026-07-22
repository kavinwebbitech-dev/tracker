@extends('layouts.sub_admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit Admin</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Admin</li>
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
						<form class="form" action="{{ route('sub_admin.proposal.update', $sub_admin->id) }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Name</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ $sub_admin->name }}" required>
											</div>
											@error('name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Date</label>
											<div class="input-group in-bord mb-3">
												<input type="date" class="form-control @error('proposal_date') is-invalid @enderror" name="proposal_date" placeholder="Date" value="{{ $sub_admin->proposal_date }}" required>
											</div>
											@error('proposal_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
								</div>
								
								<h4 class="text-success">Document List</h4>
								<div class="col-12" id="dynamicTable">
									<div class="row mb-3">
										<div class="col-md-3">
											<label>Documents</label>
										</div>
										<div class="col-md-4">
											<label>Document Type</label>
										</div>
										<div class="col-md-3">
											<label>Amount</label>
										</div>
										<div class="col-md-2">
											<label>Action</label>
										</div>
									</div>
									@if($sub_admin->proposal_details)
									@foreach($sub_admin->proposal_details as $key => $value)
		                            <div class="row dynamicrow mb-3">
		                            	<div class="col-md-3">
		                            		<input type="file" name="title_list[{{$key}}][proposal_documents]" placeholder="Quantity" class="form-control inputnum" />
		                            		@if($value->status)
			                            		<a href="{{ url('/') }}/public/proposal_documents/{{ $value->documents }}" target="_blank">Document {{ $key + 1 }}</a>
			                            		<input type="hidden" name="title_list[{{$key}}][hidden_file]" value="{{ $value->documents }}">
			                            	@endif
		                            	</div>
								        <div class="col-md-4">
								            <select class="form-control form-control-sm" name="title_list[{{$key}}][proposal_type]">
								                <option value="New Proposal" @if($value->status == "New Proposal") selected  @endif>New Proposal</option>
								                <option value="Re-Proposal" @if($value->status == "Re-Proposal") selected  @endif>Re-Proposal</option>
								            </select>
								        </div>
								        <div class="col-md-3"><input type="text" name="title_list[0][amount]" placeholder="Amount" class="form-control inputnum" value="{{ $value->amount }}" /></div>
								        @if($key == 0)
		                                	<div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus" style="padding: 3px 12px;"></i></a></div>
		                                @else
		                                	<div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close" style="padding: 3px 12px;"></i></a></div>
		                                @endif
		                            </div>
		                            @endforeach
		                            @else
		                            <div class="row mb-3">
		                            	<div class="col-md-3"><input type="file" name="title_list[0][proposal_documents]" placeholder="Quantity" class="form-control inputnum" /></div>
								        <div class="col-md-4">
								            <select class="form-control form-control-sm" name="title_list[0][proposal_type]">
								                <option value="New Proposal">New Proposal</option>
								                <option value="Re-Proposal">Re-Proposal</option>
								            </select>
								        </div>
								        <div class="col-md-3"><input type="text" name="title_list[0][amount]" placeholder="Amount" class="form-control inputnum" /></div>
		                                <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus" style="padding: 3px 12px;"></i></a></div>
		                            </div>
		                            @endif
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
  <script type="text/javascript">
    var i = "{{ count($sub_admin->proposal_details) }}";
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<div class="row dynamicrow mb-3">\
        <div class="col-md-3"><input type="file" name="title_list['+i+'][proposal_documents]" placeholder="Quantity" class="form-control inputnum" /></div>\
        <div class="col-md-4">\
            <select class="form-control form-control-sm bookconditions'+i+'" name="title_list['+i+'][proposal_type]">\
                <option value="New Proposal">New Proposal</option>\
                <option value="Re-Proposal">Re-Proposal</option>\
            </select>\
        </div>\
        <div class="col-md-3"><input type="text" name="title_list[0][amount]" placeholder="Amount" class="form-control inputnum" /></div>\
        <div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close" style="padding: 3px 12px;"></i></a></div>\
        </div>');
    });
    $(document).on('click', '.remove-tr', function(){  
        $(this).parents('.dynamicrow').remove();
    });
</script>
@endsection