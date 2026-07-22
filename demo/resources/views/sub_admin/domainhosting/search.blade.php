<?php
    $role_section = json_decode(Auth::user()->permissions);
    if ($role_section) {
        $role_section = $role_section;
    }
    else
    {
        $role_section = [];
    }
?>

<table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
    <thead>
        <tr>
            <th>S No</th>
            <th>Domain Name</th>
            <th>Expiry Date</th>
            <th>Days</th>
            <th>Hosting Name</th>
            <th>Expiry Date</th>
            <th>Days</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $total_amount = 0; ?>
        @if($domainhosting)
        @foreach($domainhosting as $key => $value)
        <?php
            $gsuite_end_date = $value->fld_domain_end_date;
            $today=date('Y-m-d');
            $date1=date_create($today);
            $date2=date_create($gsuite_end_date);
            $diff=date_diff($date1,$date2);
            $gsuite_plus_days = $diff->format("%R%a");
            $gsuite= $diff->format("%R%a days");
            
            if($gsuite_plus_days >= 1){
              $gsuite_days = $gsuite;
            }
            else
            {

              $gsuite_days='0'; 
            }

            if ($gsuite_plus_days >= 1)
            {
                $gsuite_days_show = $gsuite_days;
            }
            else
            {
                $gsuite_days_show = "0 Days";
            }


            $gsuite_end_date1 = $value->fld_hosting_end_date;
            $today1=date('Y-m-d');
            $date3=date_create($today1);
            $date4=date_create($gsuite_end_date1);
            $diff1=date_diff($date3,$date4);
            $gsuite_plus_days1 = $diff1->format("%R%a");
            $gsuite1 = $diff1->format("%R%a days");
            
            if($gsuite_plus_days1 >= 1){
              $gsuite_days1 = $gsuite1;
            }
            else
            {

              $gsuite_days1='0'; 
            }

            if ($gsuite_plus_days1 >= 1)
            {
                $gsuite_days_show1 = $gsuite_days1;
            }
            else
            {
                $gsuite_days_show1 = "0 Days";
            }
            $total_amount += $value->fld_total_amount;
        ?>
        <tr @if($gsuite_days == 0) style="background: #f6e2f7;" @endif>
            <td>{{ $key + 1}}</td>
            <td>{{ $value->fld_domain_name }}</td>
            <td>{{ date('d-m-Y', strtotime($value->fld_domain_end_date)) }}</td>
            <td>
                
                {{ $gsuite_days_show }}
            </td>
            <td>{{ $value->fld_hosting_name }}</td>
            <td>{{ date('d-m-Y', strtotime($value->fld_hosting_end_date)) }}</td>
            <td>
                {{ $gsuite_days_show1 }}
            </td>
            <td>
                {{ $value->fld_total_amount }}
            </td>
            <td>
                <p style="display: flex;">
                    @if(in_array('23', $role_section))
                    <a href="{{ route('sub_admin.domain.hosting.edit1', $value->id) }}" type="Edit" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                    @endif
                    @if(in_array('26', $role_section))
                    <a href="{{ route('sub_admin.domain.hosting.not.interest', $value->id) }}" data-confirm="Are you Sure Move Not Interest?" title="Not interest" class="delete btn btn-warning" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-unlink text-white"></i></a>
                    @endif
                    @if(in_array('25', $role_section))
                    <a href="{{ route('sub_admin.domain.hosting.invoice', $value->id) }}" title="Invoice" class="btn btn-dark" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-files text-white"></i></a>
                    @endif
                    @if(in_array('28', $role_section))
                    <a onclick="CustomerDetails('{{$value->fld_cust_id}}')" title="Customer Details" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-user text-white"></i></a>
                    @endif
                    @if(in_array('24', $role_section))
                    <a href="{{ route('sub_admin.domain.hosting.delete', $value->id) }}" title="Delete" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                    @endif
                </p>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $total_amount }}</td>
            <td></td>
        </tr>
    </tfoot>
  </table>