

<thead>
    <tr>
        <th>S No</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Company Name</th>
        <th>Address</th>
        <th>GST No</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if($client_details)
    @foreach($client_details as $key => $value)
    <tr>
        <td style="width: 20px">{{ $key + 1}}</td>
        <td>{{ $value->fld_name }}</td>
        <td>{{ $value->fld_email }}</td>
        <td>{{ $value->fld_phone }}</td>
        <td>{{ $value->fld_company_name }}</td>
        <td>
            <?php
                $cleanedString = strip_tags($value->fld_address);
                $get_word = Str::limit($value->fld_address, 50, '...');
            ?>
            {{ $get_word }}</td>
        <td>{{ $value->fld_customer_gstno }}</td>
        <td>
            @if($value->fld_status == 1)
                Active
            @elseif($value->fld_status == 0)
                In Active
            @endif
        </td>
        <td>
            <p style="display: flex;">
                <a onclick="ViewTaskModel('{{ $value->id }}')" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                <a href="javascript:void(0);"onclick="deleteProject('{{ route('admin.customers.delete', $value->id) }}')" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
            </p>
        </td>
    </tr>
    @endforeach
    @endif
</tbody>