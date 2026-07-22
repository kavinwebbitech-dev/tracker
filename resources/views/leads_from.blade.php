<table>

    <thead>
        <tr>
            <th>S No</th>
            <th>Date</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Business Name</th>
            <th>Service</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($leads_from as $key => $invoice)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($invoice->created_at)) }}</td>
                <td>{{ $invoice->name }}</td>
                <td>{{ $invoice->contact_no }}</td>
                <td>{{ $invoice->business_name }}</td>
                <td>{{ $invoice->service_get->name ?? '' }}</td>
                <td>
                    @if (is_numeric($invoice->status))
                        {{ optional($invoice->lead_status)->name }}
                    @else
                        {{ $invoice->status }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
