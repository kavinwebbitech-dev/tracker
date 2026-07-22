<table>
    
    <thead>
        <tr>
            <th>S.No</th>
            <th>Task No</th>
            <th>Assign By</th>
            <th>Staff Name</th>
            <th>Task Name</th>
            <th>Description</th>
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
            <td>{{ $invoice->task_no }}</td>
            <td>{{ $invoice->assign_details->name ?? '' }}</td>
            <td>{{ $invoice->staff_details->name ?? '' }}</td>
            <td>{{ $invoice->name }}</td>
            <td>{!! $invoice->description !!}</td>
            <td>{{ date('d-m-Y', strtotime($invoice->start_date)) }}</td>
            <td>{{ date('d-m-Y', strtotime($invoice->end_date)) }}</td>
            <td>{{ $invoice->user_comments }}</td>
            <td>{{ $invoice->admin_comments }}</td>
            <td>
                @if($invoice->status == "pending")
                    <span style="color: red;">Pending</span>
                @elseif($invoice->status == "inprogress")
                    <span style="color: #f7a20f;">In Progress</span>
                @elseif($invoice->status == "user_completed")
                    <span style="color: #14bcfc;">To be Apprd</span>
                @elseif($invoice->status == "over_due")
                    <span style="color: #14bcfc;">Over Due</span>
                @elseif($invoice->status == "recommend_to_admin")
                    <span style="color: #cf2bc4;">Recomment to Completed</span>
                @elseif($invoice->status == "completed")
                    <span style="color: #2de033;">Closed</span>
                @elseif($invoice->status == "canceled")
                    <span style="color: #f21f35;">Canceled</span>
                @elseif($invoice->status == "rejected")
                    <span style="color: #1ff2e8;">Rejected</span>
                @elseif($invoice->status == "hold")
                    <span style="color: red;">Hold</span>
                @elseif($invoice->status == "reopen")
                    <span style="color: red;">Reopen</span>
                @endif
            </td>
            <td>{{ $invoice->points }}</td>
        </tr>
    @endforeach
    </tbody>
</table>