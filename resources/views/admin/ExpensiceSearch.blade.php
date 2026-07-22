<thead>
    <tr>
        <th>S No</th>
        <th>Name</th>
        <th>Amount</th>
        <th>Date</th>
        <th>description</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    $total_amount1 = 0;
    $total_pening = 0;
    ?>
    @if ($IncomeAmount)
        @foreach ($IncomeAmount as $key => $value)
            <?php
            $total_amount1 += $value->amount;
            ?>
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->amount }}</td>
                <td>{{ date('d-m-Y', strtotime($value->expensive_date)) }}</td>
                <td>{{ $value->description ?? '' }}</td>
                <td>
                    <a onclick="ViewTaskModel('{{ $value->id }}')" class="btn btn-primary"
                        style="padding: 4px 12px;margin: 10px 4px;"><i class="ion ion-edit text-white"></i></a>
                    <a href="javascript:void(0);"
                        onclick="deleteProject('{{ route('admin.expenses.amount.delete', $value->id) }}')"
                        class="btn btn-danger" style="padding: 4px 12px;margin: 10px 4px;"><i
                            class="ti-trash text-white"></i></a>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>
<tfoot>
    <tr>
        <td></td>
        <td>Total</td>
        <td>
            {{ $total_amount1 }}
        </td>
        <td></td>
        <td></td>
    </tr>
</tfoot>
