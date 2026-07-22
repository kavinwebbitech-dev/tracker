@extends('layouts.sub_admin')

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
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Task</li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Proposal Name</div>
                                        <div class="col-md-6 col_padding">- {{ $sub_admin->name ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Proposal Amount</div>
                                        <div class="col-md-6 col_padding">- {{ $sub_admin->amount ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Proposal Date</div>
                                        <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($sub_admin->proposal_date)) }}</div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-success">Document List</h4>
                            @if(count($sub_admin->proposal_details) > 0)
                            @foreach($sub_admin->proposal_details as $key => $value)
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ url('/') }}/public/proposal_documents/{{ $value->documents }}" target="_blank">Document {{ $key + 1 }}</a>
                                </div>
                                <div class="col-md-3">
                                    - {{ $value->status }}
                                </div>
                            </div>
                            @endforeach
                            @endif
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
  

    

@endsection