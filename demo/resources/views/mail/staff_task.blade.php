<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#e8ebef">

    <tr>
        <td align="center" valign="top" class="container" style="padding:50px 10px;">
            <!-- Container -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center">
                        <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell" style="border: 1px solid #4697cb;">
                            <tr>
                                <td class="td" bgcolor="#ffffff" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                    <!-- Header -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                        <tr>
                                            <td class="p30-15-0" style="padding: 10px 20px 10px 10px;border-bottom: 2px solid #4697cb;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <th class="column" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img m-center" style=" text-align:left;"><img src="{{ $logo }}" width="250px" height="100%" border="0" alt="Webbitech logo" style="margin-left: auto;  margin-right: auto;display: block;" /></td>
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
                                                    <td style="border: 1px solid #d9d9d9;text-align:left;padding: 12px;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">Task Name </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:lefy;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">{{ $task_details->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left;padding: 12px;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">Assign by </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:lefy;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">{{ $name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left;padding: 12px;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">Start Date </td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:lefy;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">{{ date('d-m-Y', strtotime($start_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #d9d9d9;text-align:left;padding: 12px;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">End Date</td>
                                                    <td style="border: 1px solid #d9d9d9;text-align:lefy;font-family:'Ubuntu', Arial,sans-serif; font-size:13px; color:#000;">{{ date('d-m-Y', strtotime($end_date)) }}</td>
                                                </tr>

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