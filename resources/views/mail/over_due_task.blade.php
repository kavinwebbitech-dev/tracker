
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
                                    
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                        <tr>
                                            <td class="p30-15" style="padding: 20px 30px 20px 30px;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="h5 blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:15px; line-height:26px; text-align:left; color:#000;font-weight: 700;padding-top: 10px;padding-bottom: 10px;">Dear {{ $staff_details->name }},</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td style="font-size:13px;line-height: 18px;padding-bottom: 10px;">
                                                          {{ $task_details->name }} - Task has been <span style="color: red;">Over Due</span>.
                                                        </td>
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
