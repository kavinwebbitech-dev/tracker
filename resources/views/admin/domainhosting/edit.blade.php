@extends('layouts.dashboard')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Edit Domain Hosting11</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Domain Hosting</li>
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
                            <form class="form" action="{{ route('admin.domain.hosting.update', $domainhosting->id) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Branches</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control select2" id="fld_branch_id"
                                                        name="fld_branch_id">
                                                        @if ($branches)
                                                            @foreach ($branches as $key => $branch)
                                                                <option value="{{ $branch->id }}"
                                                                    @if ($domainhosting->fld_branch_id == $branch->id) selected @endif>
                                                                    {{ $branch->fld_branch_name }}</option>
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
                                                    <select class="form-control select2" id="fld_cust_id" name="fld_cust_id"
                                                        onchange="CustomerDetails()">
                                                        <option value="">Select Person</option>
                                                        @if ($customers)
                                                            @foreach ($customers as $key => $users)
                                                                <option value="{{ $users->id }}"
                                                                    data-name="{{ $users->fld_name }}"
                                                                    data-email="{{ $users->fld_email }}"
                                                                    data-cname="{{ $users->fld_company_name }}"
                                                                    @if ($domainhosting->fld_cust_id == $users->id) selected @endif>
                                                                    {{ $users->fld_name }} ({{ $users->fld_phone }}) -
                                                                    {{ $users->fld_company_name }}</option>
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
                                                    <input type="text"
                                                        class="form-control @error('cname') is-invalid @enderror"
                                                        name="cname" id="cname" placeholder="Name"
                                                        value="{{ $customer_details->fld_name }}">
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
                                                    <input type="email"
                                                        class="form-control @error('cemail') is-invalid @enderror"
                                                        name="cemail" id="cemail" placeholder="Email"
                                                        value="{{ $customer_details->fld_email }}">
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
                                                    <input type="text"
                                                        class="form-control @error('ccname') is-invalid @enderror"
                                                        name="ccname" id="ccname" placeholder="Company Name"
                                                        value="{{ $customer_details->fld_company_name }}">
                                                </div>
                                                @error('ccname')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>



                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Domain Name</label>

                                                <div class="input-group mb-3">
                                                    <input type="text"
                                                        class="form-control @error('fld_domain_name') is-invalid @enderror"
                                                        name="fld_domain_name" placeholder="Domain Name"
                                                        value="{{ $domainhosting->fld_domain_name }}" required>

                                                    <button type="button" class="btn btn-outline-primary"
                                                        data-bs-toggle="modal" data-bs-target="#domainModal">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>

                                                @error('fld_domain_name')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Domain Register Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('fld_domain_start_date') is-invalid @enderror"
                                                        name="fld_domain_start_date"
                                                        value="{{ $domainhosting->fld_domain_start_date }}"
                                                        id="fld_domain_start_date" required>
                                                </div>
                                                @error('fld_domain_start_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Years</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('fld_domain_tenure') is-invalid @enderror"
                                                        id="fld_domain_tenure">
                                                        <option value="">Years</option>
                                                        <option value="1"
                                                            @if ($domainhosting->fld_domain_tenure == 1) selected @endif>1</option>
                                                        <option value="2"
                                                            @if ($domainhosting->fld_domain_tenure == 2) selected @endif>2</option>
                                                        <option value="3"
                                                            @if ($domainhosting->fld_domain_tenure == 3) selected @endif>3</option>
                                                        <option value="4"
                                                            @if ($domainhosting->fld_domain_tenure == 4) selected @endif>4</option>
                                                        <option value="5"
                                                            @if ($domainhosting->fld_domain_tenure == 5) selected @endif>5</option>
                                                        <option value="6"
                                                            @if ($domainhosting->fld_domain_tenure == 6) selected @endif>6</option>
                                                        <option value="7"
                                                            @if ($domainhosting->fld_domain_tenure == 7) selected @endif>7</option>
                                                        <option value="8"
                                                            @if ($domainhosting->fld_domain_tenure == 8) selected @endif>8</option>
                                                        <option value="9"
                                                            @if ($domainhosting->fld_domain_tenure == 9) selected @endif>9</option>
                                                        <option value="10"
                                                            @if ($domainhosting->fld_domain_tenure == 10) selected @endif>10</option>
                                                        <option value="11"
                                                            @if ($domainhosting->fld_domain_tenure == 11) selected @endif>11</option>
                                                        <option value="12"
                                                            @if ($domainhosting->fld_domain_tenure == 12) selected @endif>12</option>
                                                    </select>
                                                    <input type="hidden" id="fld_domain_tenure_hidden"
                                                        name="fld_domain_tenure"
                                                        value="{{ $domainhosting->fld_domain_tenure }}">
                                                </div>
                                                @error('fld_domain_tenure')
                                                    <span class="invalid-feedback"
                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Expiry Years</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('fld_expiry_domain_tenure') is-invalid @enderror"
                                                        name="fld_expiry_domain_tenure" id="fld_expiry_domain_tenure">
                                                        <option value="">Years</option>
                                                        <option value="1"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 1) selected @endif>1</option>
                                                        <option value="2"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 2) selected @endif>2</option>
                                                        <option value="3"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 3) selected @endif>3</option>
                                                        <option value="4"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 4) selected @endif>4</option>
                                                        <option value="5"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 5) selected @endif>5</option>
                                                        <option value="6"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 6) selected @endif>6</option>
                                                        <option value="7"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 7) selected @endif>7</option>
                                                        <option value="8"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 8) selected @endif>8</option>
                                                        <option value="9"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 9) selected @endif>9</option>
                                                        <option value="10"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 10) selected @endif>10</option>
                                                        <option value="11"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 11) selected @endif>11</option>
                                                        <option value="12"
                                                            @if ($domainhosting->fld_expiry_domain_tenure == 12) selected @endif>12</option>
                                                    </select>
                                                </div>
                                                @error('fld_expiry_domain_tenure')
                                                    <span class="invalid-feedback"
                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Domain Expiry Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('fld_domain_end_date') is-invalid @enderror"
                                                        name="fld_domain_end_date" id="fld_domain_end_date"
                                                        value="{{ $domainhosting->fld_domain_end_date }}" required>
                                                </div>
                                                @error('fld_domain_end_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Hosting Name</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control @error('fld_hosting_name') is-invalid @enderror"
                                                        name="fld_hosting_name" required>

                                                        <option value="">Select Hosting Server</option>

                                                        @foreach ($hostingServers as $server)
                                                            <option value="{{ $server->fld_hosting_name }}"
                                                                {{ old('fld_hosting_name', $domainhosting->fld_hosting_name) == $server->fld_hosting_name ? 'selected' : '' }}>
                                                                {{ $server->fld_hosting_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                @error('fld_hosting_name')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Hosting Register Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('fld_hosting_start_date') is-invalid @enderror"
                                                        name="fld_hosting_start_date" id="fld_hosting_start_date"
                                                        value="{{ $domainhosting->fld_hosting_start_date }}" required>
                                                </div>
                                                @error('fld_hosting_start_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Years</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('fld_hosting_tenure') is-invalid @enderror"
                                                        id="fld_hosting_tenure">
                                                        <option value="">Years</option>
                                                        <option value="1"
                                                            @if ($domainhosting->fld_hosting_tenure == 1) selected @endif>1</option>
                                                        <option value="2"
                                                            @if ($domainhosting->fld_hosting_tenure == 2) selected @endif>2</option>
                                                        <option value="3"
                                                            @if ($domainhosting->fld_hosting_tenure == 3) selected @endif>3</option>
                                                        <option value="4"
                                                            @if ($domainhosting->fld_hosting_tenure == 4) selected @endif>4</option>
                                                        <option value="5"
                                                            @if ($domainhosting->fld_hosting_tenure == 5) selected @endif>5</option>
                                                        <option value="6"
                                                            @if ($domainhosting->fld_hosting_tenure == 6) selected @endif>6</option>
                                                        <option value="7"
                                                            @if ($domainhosting->fld_hosting_tenure == 7) selected @endif>7</option>
                                                        <option value="8"
                                                            @if ($domainhosting->fld_hosting_tenure == 8) selected @endif>8</option>
                                                        <option value="9"
                                                            @if ($domainhosting->fld_hosting_tenure == 9) selected @endif>9</option>
                                                        <option value="10"
                                                            @if ($domainhosting->fld_hosting_tenure == 10) selected @endif>10</option>
                                                        <option value="11"
                                                            @if ($domainhosting->fld_hosting_tenure == 11) selected @endif>11</option>
                                                        <option value="12"
                                                            @if ($domainhosting->fld_hosting_tenure == 12) selected @endif>12</option>
                                                    </select>
                                                    <input type="hidden" id="fld_hosting_tenure_hidden"
                                                        name="fld_hosting_tenure"
                                                        value="{{ $domainhosting->fld_hosting_tenure }}">
                                                </div>
                                                @error('fld_hosting_tenure')
                                                    <span class="invalid-feedback"
                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Expiry Years</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('fld_expiry_hosting_tenure') is-invalid @enderror"
                                                        name="fld_expiry_hosting_tenure" id="fld_expiry_hosting_tenure">
                                                        <option value="">Years</option>
                                                        <option value="1"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 1) selected @endif>1</option>
                                                        <option value="2"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 2) selected @endif>2</option>
                                                        <option value="3"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 3) selected @endif>3</option>
                                                        <option value="4"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 4) selected @endif>4</option>
                                                        <option value="5"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 5) selected @endif>5</option>
                                                        <option value="6"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 6) selected @endif>6</option>
                                                        <option value="7"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 7) selected @endif>7</option>
                                                        <option value="8"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 8) selected @endif>8</option>
                                                        <option value="9"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 9) selected @endif>9</option>
                                                        <option value="10"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 10) selected @endif>10</option>
                                                        <option value="11"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 11) selected @endif>11</option>
                                                        <option value="12"
                                                            @if ($domainhosting->fld_expiry_hosting_tenure == 12) selected @endif>12</option>
                                                    </select>
                                                </div>
                                                @error('fld_expiry_hosting_tenure')
                                                    <span class="invalid-feedback"
                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Hosting Expiry Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('fld_hosting_end_date') is-invalid @enderror"
                                                        name="fld_hosting_end_date"
                                                        value="{{ $domainhosting->fld_hosting_end_date }}"
                                                        id="fld_hosting_end_date" required>
                                                </div>
                                                @error('fld_hosting_end_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Tax Rate(%)</label>
                                                <div class="input-group mb-3">
                                                    <input type="text"
                                                        class="form-control @error('fld_tax_percentage') is-invalid @enderror"
                                                        name="fld_tax_percentage" id="fld_tax_percentage"
                                                        placeholder="Tax Rate"
                                                        value="{{ $domainhosting->fld_tax_percentage }}" required
                                                        onkeyup="TaxCalculate()">
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

                                                    <input type="text"
                                                        class="form-control @error('fld_amount') is-invalid @enderror"
                                                        name="fld_amount" id="fld_amount" placeholder="Amount"
                                                        value="{{ $domainhosting->fld_amount }}" required
                                                        onkeyup="TaxCalculate()">
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
                                                    <input type="text"
                                                        class="form-control @error('fld_total_amount') is-invalid @enderror"
                                                        name="fld_total_amount" id="fld_total_amount"
                                                        placeholder="Total Amount"
                                                        value="{{ $domainhosting->fld_total_amount }}" readonly>
                                                </div>
                                                @error('fld_total_amount')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <div class="input-group mb-3">
                                                    <textarea type="text" class="form-control @error('fld_description') is-invalid @enderror" name="fld_description"
                                                        placeholder="Description" rows="4">{{ $domainhosting->fld_description }}</textarea>
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
    <div class="modal fade" id="domainModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Domain List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <ul class="list-group">
                        @forelse($domains as $domain)
                            <li class="list-group-item">
                                {{ $domain->fld_domain_server_name }}
                            </li>
                        @empty
                            <li class="list-group-item text-danger">
                                No Domain Found
                            </li>
                        @endforelse
                    </ul>

                </div>

            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>

    <script type="text/javascript">
        function TaxCalculate() {
            var fld_amount = $('#fld_amount').val();
            var fld_tax_percentage = $('#fld_tax_percentage').val();
            var tax_cal = (fld_amount * fld_tax_percentage) / 100;

            const sum = parseFloat(fld_amount) + parseFloat(tax_cal);
            if (sum) {
                $("#fld_total_amount").val(sum);
            } else {
                $("#fld_total_amount").val("");
            }

        }

        var selection = document.getElementById("fld_cust_id");

        selection.onchange = function(event) {
            var name = event.target.options[event.target.selectedIndex].dataset.name;
            var cname = event.target.options[event.target.selectedIndex].dataset.cname;
            var email = event.target.options[event.target.selectedIndex].dataset.email;
            $("#cname").val(name);
            $("#cemail").val(email);
            $("#ccname").val(cname);
        };
    </script>
    {{-- <script type="text/javascript">
        $(document).ready(function() {

            $('#fld_domain_tenure, #fld_hosting_tenure').select2();

            function checkDate() {
                var tenure = $("#fld_domain_tenure").val();
                var regDate = $.trim($("#fld_domain_start_date").val());

                if (!regDate) {
                    return;
                }

                if (tenure === "" || tenure === null) {
                    // No year picked yet — expiry just mirrors register date
                    $("#fld_domain_end_date").val(regDate);
                    return;
                }

                var arr = regDate.split("-");
                var year = parseInt(arr[0]);
                var month = arr[1];
                var day = arr[2];

                var newYear = year + parseInt(tenure);
                var endDate = newYear + '-' + month + '-' + day;

                $("#fld_domain_end_date").val(endDate);
            }

            function checkDate1() {
                var tenure = $("#fld_hosting_tenure").val();
                var regDate = $.trim($("#fld_hosting_start_date").val());

                if (!regDate) {
                    return;
                }

                if (tenure === "" || tenure === null) {
                    // No year picked yet — expiry just mirrors register date
                    $("#fld_hosting_end_date").val(regDate);
                    return;
                }

                var arr = regDate.split("-");
                var year = parseInt(arr[0]);
                var month = arr[1];
                var day = arr[2];

                var newYear = year + parseInt(tenure);
                var endDate = newYear + '-' + month + '-' + day;

                $("#fld_hosting_end_date").val(endDate);
            }

            // Recalculate whenever the year is changed...
            $("#fld_domain_tenure").on('change', checkDate);
            $("#fld_hosting_tenure").on('change', checkDate1);

            // ...and also whenever the register date itself is changed,
            // so expiry keeps mirroring it until a year is picked.
            $("#fld_domain_start_date").on('change', checkDate);
            $("#fld_hosting_start_date").on('change', checkDate1);

            // Run once on page load too, so an existing record shows
            // expiry = register date if tenure is still blank.
            checkDate();
            checkDate1();

        });
    </script> --}}

    <script type="text/javascript">
        $(document).ready(function() {

            $('#fld_domain_tenure, #fld_expiry_domain_tenure, #fld_hosting_tenure, #fld_expiry_hosting_tenure')
                .select2();

            function addYears(dateStr, years) {
                if (!dateStr) return null;
                var arr = dateStr.split("-");
                var year = parseInt(arr[0]);
                var month = arr[1];
                var day = arr[2];
                var newYear = year + parseInt(years);
                return newYear + '-' + month + '-' + day;
            }

            // ---------- DOMAIN ----------

            function getBaseDomainExpiry() {
                var tenure = $("#fld_domain_tenure").val();
                var regDate = $.trim($("#fld_domain_start_date").val());
                if (!regDate) return null;
                if (tenure === "" || tenure === null) return regDate;
                return addYears(regDate, tenure);
            }

            function checkDate() {
                var base = getBaseDomainExpiry();
                if (!base) return;
                $("#fld_domain_end_date").val(base);
            }

            function checkExpiryDomainDate() {
                var tenure = $("#fld_expiry_domain_tenure").val();
                var base = getBaseDomainExpiry();
                if (!base) return;

                if (tenure === "" || tenure === null || parseInt(tenure) <= 0) {
                    $("#fld_domain_end_date").val(base);
                    return;
                }
                $("#fld_domain_end_date").val(addYears(base, tenure));
            }

            function updateDomainTenureAvailability() {
                var gsuiteTenure = $("#fld_domain_tenure").val();
                var expiryTenure = $("#fld_expiry_domain_tenure").val();

                if (gsuiteTenure !== null) {
                    $("#fld_expiry_domain_tenure").prop('disabled', false);
                }

                if (expiryTenure && parseInt(expiryTenure) > 0) {
                    $("#fld_domain_tenure").prop('disabled', true).trigger('change.select2');
                    checkExpiryDomainDate();
                } else {
                    $("#fld_domain_tenure").prop('disabled', false).trigger('change.select2');
                    checkDate();
                }

                $("#fld_expiry_domain_tenure").trigger('change.select2');
                $("#fld_domain_tenure_hidden").val($("#fld_domain_tenure").val());
            }

            $("#fld_domain_tenure").on('change', function() {
                $("#fld_domain_tenure_hidden").val($(this).val());
                updateDomainTenureAvailability();
            });
            $("#fld_domain_start_date").on('change', updateDomainTenureAvailability);
            $("#fld_expiry_domain_tenure").on('change', updateDomainTenureAvailability);

            updateDomainTenureAvailability();

            // ---------- HOSTING ----------

            function getBaseHostingExpiry() {
                var tenure = $("#fld_hosting_tenure").val();
                var regDate = $.trim($("#fld_hosting_start_date").val());
                if (!regDate) return null;
                if (tenure === "" || tenure === null) return regDate;
                return addYears(regDate, tenure);
            }

            function checkDate1() {
                var base = getBaseHostingExpiry();
                if (!base) return;
                $("#fld_hosting_end_date").val(base);
            }

            function checkExpiryHostingDate() {
                var tenure = $("#fld_expiry_hosting_tenure").val();
                var base = getBaseHostingExpiry();
                if (!base) return;

                if (tenure === "" || tenure === null || parseInt(tenure) <= 0) {
                    $("#fld_hosting_end_date").val(base);
                    return;
                }
                $("#fld_hosting_end_date").val(addYears(base, tenure));
            }

            function updateHostingTenureAvailability() {
                var hostingTenure = $("#fld_hosting_tenure").val();
                var expiryTenure = $("#fld_expiry_hosting_tenure").val();

                if (hostingTenure !== null) {
                    $("#fld_expiry_hosting_tenure").prop('disabled', false);
                }

                if (expiryTenure && parseInt(expiryTenure) > 0) {
                    $("#fld_hosting_tenure").prop('disabled', true).trigger('change.select2');
                    checkExpiryHostingDate();
                } else {
                    $("#fld_hosting_tenure").prop('disabled', false).trigger('change.select2');
                    checkDate1();
                }

                $("#fld_expiry_hosting_tenure").trigger('change.select2');
                $("#fld_hosting_tenure_hidden").val($("#fld_hosting_tenure").val());
            }

            $("#fld_hosting_tenure").on('change', function() {
                $("#fld_hosting_tenure_hidden").val($(this).val());
                updateHostingTenureAvailability();
            });
            $("#fld_hosting_start_date").on('change', updateHostingTenureAvailability);
            $("#fld_expiry_hosting_tenure").on('change', updateHostingTenureAvailability);

            updateHostingTenureAvailability();

        });
    </script>
@endsection
