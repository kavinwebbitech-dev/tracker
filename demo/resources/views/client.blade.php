@extends('layouts.client')

@section('content')
<div class="content-wrapper">
      <div class="container-full">
        <!-- Main content -->
        <?php
            
            $total_project   = \App\Models\Project::where('customer_id', Auth::user()->cus_id)->get();
            $pending        = \App\Models\Project::where('customer_id', Auth::user()->cus_id)->where('status', '0')->get();
            $progress       = \App\Models\Project::where('customer_id', Auth::user()->cus_id)->where('status', '1')->get();
            $completed      = \App\Models\Project::where('customer_id', Auth::user()->cus_id)->where('status', '5')->get();
            // dd($event_details);
        ?>
        <style>
            .box_color1
            {
                background-color: #9b7693;
            }
            .box_color2
            {
                background-color: #68928c;
            }
            .box_color3
            {
                background-color: #5fbc87;
            }
            .box_color4
            {
                background-color: #e76b48;
            }
            .box_color5
            {
                background-color: #007da5;
            }
            .box_color6
            {
                background-color: #ff7096;
            }
        </style>
        <section class="content">

            <div class="row">

                <div class="col-md-12">
                    
                    <div class="row">
                        
                        {{-- <div class="col-lg-3 col-12">
                            <a href="{{ route('client.projects.view') }}" class="box box_color2">
                                <div class="box-body">
                                    <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Project</div>
                                    <div class="text-white fs-24 fw-800">{{ count($total_project) }}</div>
                                </div>
                            </a>
                        </div> --}}
                        
                    </div>

                </div>

                
                <style type="text/css">
                    .img-align
                    {
                        width: 100%;
                        height: 137px;
                    }
                    .img-align img
                    {
                        height: 100%;
                        object-fit: contain;
                        width: 100%;
                        border-radius: 10px;
                    }
                </style>

                <div class="col-lg-4 col-12">
                    


                </div>
                
            </div>
            <hr>
            <div class="row">
                
                
            </div>

            
        </section>
        <!-- /.content -->
      </div>
</div>

<?php
    $current_route = Route::currentRouteName();
?>

<script type="text/javascript">
    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();

        if (start_date) {
            start_date = start_date;
        }
        else
        {
            start_date = "";
        }
        if (end_date) 
        {
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
