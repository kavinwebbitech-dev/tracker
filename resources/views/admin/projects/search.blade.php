
<thead>
    <tr>
        <th>S No</th>
        <th>Project</th>
        <th>Sales User Name</th>
        <th>Total Amount</th>
        <th>Pending</th>
        <th>Confirm Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<?php
    $total_amount1 = 0;
    $total_pening = 0;
?>
<tbody>
    @if($users)
    @foreach($users as $key => $value)
    <?php
        $total_amount = 0;
        $total_penc = 0;
        $bit_amounts = $value->bit_amounts;
        if (count($bit_amounts) > 0) {
            foreach ($bit_amounts as $key1 => $value1) {
                $total_amount += $value1->fld_project_amount;
            }
        }
        $total_penc = $value->bid_amount - $total_amount;
        $total_amount1 += $value->bid_amount;
        $total_pening += $total_penc;
    ?>
    <tr @if($value->bid_amount == $total_amount) style="background: #f6e2f7;" @endif @if($value->refunded == "refunded") style="background: #ff6464;" @endif>
        <td>{{ $key + 1}}</td>
        <td>
            {{ $value->name }}
            @if($value->refunded == "refunded")
                <span style="font-size: 10px;color: #fff;">(Refunded)</span>
            @endif
        </td>
        <td>{{ $value->added_user_details->name ?? '' }}</td>
        <td>
            
            <div class="count-icons">
                <p>{{ $value->bid_amount }}</p>
                <i class="fa fa-add count-right-icon" style="cursor: pointer;font-size: 20px;" onclick='ViewTaskModel1("{{ $value->id }}")'></i> <i class="fa fa-eye" style="cursor: pointer;font-size: 17px;" onclick='ViewTaskModel("{{ $value->id }}")'></i>
            </div>
        </td>
        <td>
            {{ $total_penc }}
        </td>
        <td>
            @if($value->sales_user_date == "0000-00-00")
            @else
                {{ date('d-m-Y', strtotime($value->sales_user_date)) }}
            @endif
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
        <td>
            <p style="display: flex;">
                <a href="{{ route('admin.projects.view.details', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                <a href="{{ route('admin.projects.edit', $value->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.projects.delete', $value->id) }}')" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
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
        <td>
            {{ $total_amount1 }}
        </td>
        <td>
            {{ $total_pening }}
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</tfoot>