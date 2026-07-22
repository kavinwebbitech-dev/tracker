
<thead>
    <tr>
        <th>S No</th>
        <th>Domain Name</th>
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
    <tr>
        <td>{{ $key + 1}}</td>
        <td>{{ $value->fld_domain_name }}</td>
        <td>{{ date('d-m-Y', strtotime($value->fld_gsuite_end_date)) }}</td>
        <td>
            <?php
                $gsuite_end_date = $value->fld_gsuite_end_date;
                $today=date('Y-m-d');
                $date1=date_create($today);
                $date2=date_create($gsuite_end_date);
                $diff=date_diff($date1,$date2);
                $gsuite_plus_days = $diff->format("%R%a");
                $gsuite= $diff->format("%R%a days");
                // dd($gsuite);
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
                // $toDate = \Carbon\Carbon::parse(date('Y-m-d'));
                // $fromDate = \Carbon\Carbon::parse($value->fld_gsuite_end_date);
          
                // $days = $toDate->diffInDays($fromDate);

                $total_amount += $value->fld_total_amount;
            ?>
            {{ $gsuite_days_show }}
        </td>
        <td>
            {{ $value->fld_total_amount }}
        </td>
        <td>
            <p style="display: flex;">
                <a href="{{ route('admin.gsuide.edit1', $value->id) }}" type="Edit" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                <a href="{{ route('admin.gsuide.not.interest', $value->id) }}" data-confirm="Are you Sure Move Not Interest?" title="Not interest" class="delete btn btn-warning" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-unlink text-white"></i></a>
                <a href="{{ route('admin.gsuide.delete', $value->id) }}" title="Delete" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                <a href="{{ route('admin.gsuide.invoice', $value->id) }}" title="Invoice" class="btn btn-dark" style="padding: 4px 12px;margin: 0px 4px;" target="_blank"><i class="ti-files text-white"></i></a>
                <a onclick="CustomerDetails('{{$value->fld_cust_id}}')" title="Customer Details" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-user text-white"></i></a>
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
        <td>{{ $total_amount }}</td>
        <td></td>
    </tr>
</tfoot>