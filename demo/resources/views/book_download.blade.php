
<table>

    <thead>
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Company Name</th>
            <th>Address</th>
            <th>GST No</th>
            <th>Status</th>
        </tr>
    </thead>
   
    <tbody>
    @foreach($invoices as $key => $invoice)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $invoice->fld_name ?? '' }}</td>
            <td>{{ $invoice->fld_email ?? '' }}</td>
            <td>{{ $invoice->fld_phone ?? '' }}</td>
            <td>{{ $invoice->fld_company_name ?? '' }}</td>
            <td>{{ $invoice->fld_address ?? '' }}</td>
            <td>{{ $invoice->fld_customer_gstno ?? '' }}</td>
            <td>
                @if($invoice->fld_status == 1)
                    Active
                @else
                    In Active
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>