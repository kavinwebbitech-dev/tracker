
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#e8ebef">

    <tr>
        <td align="center" valign="top" class="container" style="padding:50px 10px;">
            <!-- Container -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-shell" style="border: 1px solid #4697cb;">
                            <tr>
                                <td class="td" bgcolor="#ffffff" style="width:750px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                    <!-- Header -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                        <tr>
                                            <td class="p30-15-0" style="padding: 10px 20px 10px 10px;border-bottom: 2px solid #4697cb;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <th class="column" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img m-center" style=" text-align:left;"><img src="{{ $logo ?? '' }}" width="250px" height="100%" border="0" alt="Webbitech logo" style="margin-left: auto;  margin-right: auto;display: block;" /></td>
                                                                </tr>
                                                            </table>
                                                        </th>
                                                        
                                                    </tr>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END Header -->

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="padding: 10px;">
                                        <tr>
                                            <td>
                                            <table style="width: 100%;margin:20px 0px;border-collapse: collapse;padding: 10px;">
                                                
                                                <tr>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">S.no</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Task Id</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Assign By</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Staff Name </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Task Name </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Description </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Start Date</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">End Date</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">User Comments </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Sub Admin Comments </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Admin Comments </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left;line-height: 18px; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;">Status </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;font-weight: 600;padding: 13px 10px;line-height: 18px;">Points </td>
                                                </tr>
                                                @foreach($book_details as $key => $invoice)
                                                <tr>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $key + 1 }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->task_details->task_no ?? '' }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->task_details->assign_details->name ?? '' }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->staff_details->name ?? '' }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->task_details->name }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{!! $invoice->task_details->description !!} </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ date('d-m-Y', strtotime($invoice->start_date)) }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ date('d-m-Y', strtotime($invoice->end_date)) }}</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->user_comments }} </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->sub_admin_comment }} </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->admin_comments }} </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">
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
                                                    <td style="border: 1px solid #d9d9d9;text-align:left; font-size:13px; color:#000;padding: 13px 10px;line-height: 18px;">{{ $invoice->points }} </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                            
                            <tr>
                                <td class="text-footer" style="padding-top: 30px; color:#1f2125; font-family:'Fira Mono', Arial,sans-serif; font-size:12px; line-height:22px; text-align:center;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- END Container -->
        </td>
    </tr>
</table>