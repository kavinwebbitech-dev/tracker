@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Hosting Servers</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Hosting Servers</li>
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
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <span class="corp-btn corp-btn-primary" onclick="addHostingServer()" style="float: right;">
                                Add Hosting Server
                            </span>
                        </div>
                    </div>
                    <div class="creative-table-wrapper table-responsive">
                      <table id="example1" class="table table-bordered table-striped creative-table">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Hosting Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($hosting_servers)
                            @foreach($hosting_servers as $key => $value)
                            <tr>
                                <td class="text-muted fw-bold sorting_1">{{ $key + 1}}</td>
                                <td>
                                    <div class="icon-text-wrapper">
                                        <div class="cell-icon icon-company"><i class="bi bi-hdd-network"></i></div>
                                        <span class="fw-semibold">{{ $value->fld_hosting_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($value->fld_status == 1)
                                        <span class="creative-badge creative-badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>
                                    @else
                                        <span class="creative-badge creative-badge-inactive"><i class="bi bi-x-circle-fill"></i> In Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="creative-action-box">
                                        <a href="javascript:void(0);" onclick="ViewTaskModel('{{ $value->id }}')" class="creative-btn-action action-edit"><i class="ion ion-edit text-white"></i></a>
                                        <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.hosting_server.delete', $value->id) }}')" class="creative-btn-action action-delete"><i class="ti-trash text-white"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                      </table>
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
            <h4 class="modal-title" id="hosting_modal_title">Add Hosting Server</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">

            <form class="form" id="hosting_server_form" action="{{ route('admin.hosting_server.store') }}" method="post">
            @csrf
            <input type="hidden" name="_method" id="form_method" value="">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Hosting Name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_hosting_name') is-invalid @enderror" id="fld_hosting_name" name="fld_hosting_name" placeholder="Hosting Name" value="{{ old('fld_hosting_name') }}" required>
                    </div>
                    @error('fld_hosting_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="input-group mb-3">
                        <select name="fld_status" class="form-control" id="fld_status">
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                        </select>
                    </div>
                    @error('fld_status')
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

  <script type="text/javascript">
    // Base URLs (Laravel route() output with a placeholder swapped at call time)
    const storeUrl  = "{{ route('admin.hosting_server.store') }}";
    const editUrl   = "{{ route('admin.hosting_server.edit', ':id') }}";
    const updateUrl = "{{ route('admin.hosting_server.update', ':id') }}";

    // Reset the form and open the modal for a fresh "Add" entry
    function addHostingServer() {
        $("#hosting_server_form")[0].reset();
        $("#hosting_server_form").attr('action', storeUrl);
        $("#form_method").val(''); // plain POST -> store()
        $("#hosting_modal_title").text('Add Hosting Server');
        $('#modal-default').modal('show');
    }

    // Fetch a record via AJAX (GET) and populate the modal for editing
    function ViewTaskModel(id) {
        $.ajax({
            url: editUrl.replace(':id', id),
            method: "GET",
            success: function (response) {
                $("#fld_hosting_name").val(response.fld_hosting_name);
                $("#fld_status").val(response.fld_status);

                $("#hosting_server_form").attr('action', updateUrl.replace(':id', id));
                $("#form_method").val('PUT'); // spoof PUT -> update()

                $("#hosting_modal_title").text('Edit Hosting Server');
                $('#modal-default').modal('show');
            },
            error: function () {
                alert('Unable to load record. Please try again.');
            }
        });
    }
  </script>
@endsection