

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
    <tr>
        <td>{{ $key + 1}}</td>
        <td>{{ $value->fld_domain_name }}</td>
        <td>{{ date('d-m-Y', strtotime($value->fld_domain_end_date)) }}</td>
        <td>
            <?php
                $toDate = \Carbon\Carbon::parse(date('Y-m-d'));
                $fromDate = \Carbon\Carbon::parse($value->fld_domain_end_date);
          
                $days = $toDate->diffInDays($fromDate);
            ?>
            +{{ $days }} days
        </td>
        <td>{{ $value->fld_hosting_name }}</td>
        <td>{{ date('d-m-Y', strtotime($value->fld_hosting_end_date)) }}</td>
        <td>
            <?php
                $toDate1 = \Carbon\Carbon::parse(date('Y-m-d'));
                $fromDate1 = \Carbon\Carbon::parse($value->fld_hosting_end_date);
          
                $days1 = $toDate1->diffInDays($fromDate1);

                $total_amount += $value->fld_total_amount;
            ?>
            +{{ $days1 }} days
        </td>
        <td>
            {{ $value->fld_total_amount }}
        </td>
        <td>
            <p style="display: flex;margin-bottom: 5px;margin-top: 5px;">
                <a href="{{ route('admin.domain.hosting.edit1', $value->id) }}" type="Edit" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                <a href="{{ route('admin.domain.hosting.not.interest', $value->id) }}" data-confirm="Are you Sure Move Not Interest?" title="Not interest" class="delete btn btn-warning" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-unlink text-white"></i></a>
                <a href="{{ route('admin.domain.hosting.invoice', $value->id) }}" title="Invoice" class="btn btn-dark" style="padding: 4px 12px;margin: 0px 4px;" target="_blank"><i class="ti-files text-white"></i></a>
                <a onclick="CustomerDetails('{{$value->fld_cust_id}}')" title="Customer Details" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-user text-white"></i></a>
                <a href="{{ route('admin.domain.hosting.delete', $value->id) }}" title="Delete" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
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
                        