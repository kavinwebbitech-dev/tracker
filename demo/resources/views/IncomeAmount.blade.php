<table>
    
    <thead>
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    
    <tbody>
    @foreach($invoices as $key => $invoice)
    
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $invoice->name }}</td>
            <td>{{ $invoice->amount }}</td>
            <td>{{ date('d-m-Y', strtotime($invoice->income_date)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>