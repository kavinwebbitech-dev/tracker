@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">This Month Project</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Branches</li>
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
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <select class="form-control select2 @error('customer') is-invalid @enderror" id="customer" name="customer" onchange="SelectStaff()">
                                <option value="">Select Staff</option>
                                @if($user_list)
                                @foreach($user_list as $key => $users)
                                <?php
                                    // dump($users);
                                    $user_details = \App\Models\User::where('id', $users->added_by)->first();
                                    // dd($user_details);
                                ?>
                                <option value="{{ $user_details->id }}" @if($staff_id == $user_details->id) selected @endif>{{ $user_details->name ?? '' }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                      <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Project Amount</th>
                                <th>Sales Person</th>
                                <th>Order Amount</th>
                                <th>Received Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total_order_amount = 0;
                                $today_receive_amount = 0;
                            ?>
                            @if(count($month_project) > 0)
                            @foreach($month_project as $key1 => $value)
                            <?php
                                $total_order_amount += $value->bid_amount;
                            ?>
                            <tr>
                                <td>{{ $key1 + 1}}</td>
                                <td>
                                    {{ $value->name }}
                                </td>
                                <td>
                                    {{ $value->added_user_details->name ?? '' }}
                                </td>
                                <td>{{ $value->bid_amount }}</td>
                                <td>
                                    <?php
                                        $today_bid = \App\Models\ProjectBitAmount::where('fld_project_id', $value->id)->get();
                                        $today_bid_amount = 0;
                                        if ($today_bid) {
                                            foreach ($today_bid as $key3 => $value3) {
                                                $today_bid_amount += $value3->fld_project_amount;
                                            }
                                        }
                                        $today_receive_amount += $today_bid_amount;
                                    ?>
                                    {{ $today_bid_amount }}
                                </td>
                                <td>
                                    @if($value->status == "0")
                                        Pending
                                    @elseif($value->status == "1")
                                        On Progress
                                    @elseif($value->status == "3")
                                        On Hold
                                    @elseif($value->status == "5")
                                        Done
                                    @elseif($value->status == "6")
                                        Cancel
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td><b>Total</b></td>
                                <td></td>
                                <td>{{ $total_order_amount }}</td>
                                <td>{{ $today_receive_amount }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
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
  <?php
    $current_route = Route::currentRouteName();
  ?>
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script type="text/javascript">
    function SelectStaff() {

        var staff_id = $("#customer").val();

        var expert_search_url   = "{{ route($current_route, [$month, $year]) }}";

        if (staff_id) {
            staff_id = staff_id;
        }

        var str_search_request   = "&staff_id="+staff_id;
        
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }

    }
  </script>
@endsection