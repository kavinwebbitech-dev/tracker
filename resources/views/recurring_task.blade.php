<table>
    
    <thead>
        <tr>
            <th>S.No</th>
            <th>Task Review for Riser</th>
            <th>Task Name</th>
            <th>Description</th>
            <th>Task Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>User Comments</th>
            <th>Admin Comments</th>
            <th>Status</th>
            <th>Points</th>
        </tr>
    </thead>

    <tbody>
    @foreach($invoices as $key => $invoice)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $invoice->task_main->admin_details->name }}</td>
            <td>{{ $invoice->task_main->name }}</td>
            <td>{!! $invoice->task_main->description !!}</td>
            <td>{{ $invoice->task_main->task_type }}({{ $invoice->task_main->date_type }})</td>
            <td>{{ date('d-m-Y', strtotime($invoice->start_date)) }}</td>
            <td>{{ date('d-m-Y', strtotime($invoice->end_date)) }}</td>
            <td>{{ $invoice->user_comments }}</td>
            <td>{{ $invoice->admin_comments }}</td>
            <td>
                @if($invoice->status == "pending")
                    Pending
                @elseif($invoice->status == "progress")
                    Progress
                @elseif($invoice->status == "user_completed")
                    User Completed
                @elseif($invoice->status == "completed")
                    Completed
                @elseif($invoice->status == "canceled")
                    Canceled
                @elseif($invoice->status == "rejected")
                    Rejected
                @elseif($invoice->status == "hold")
                    Hold
                @elseif($invoice->status == "reopen")
                    Reopen
                @endif
            </td>
            <td>{{ $invoice->points }}</td>
        </tr>
    @endforeach
    </tbody>
</table>