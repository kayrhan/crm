<html>
<head>
    <style>
        div {
            font-family: "Tahoma", sans-serif;
        }

        .ticket-table {
            font-size: 10.0pt;
            font-family: "Tahoma", sans-serif;
        }

        .ticket-table td {
            padding: 5px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .ticket-table p {
            font-family: "Tahoma", sans-serif;
            font-size: 10.0pt;
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#fff 0 -3% no-repeat;">
<div align=center>
    <table class="MsoNormalTable" cellspacing=0 cellpadding=0 style="width:100.0%;background:white;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0; border: none">
        <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:3.75pt">
            <td style="width:100.0%; background:#1d1868; padding:0; height:3.75pt; font-size: 10pt; color: #1d1868" align="center">New Ticket Assignment</td>
        </tr>
        <tr style="mso-yfti-irow:1">
            <td style="width:100.0%;padding:0">
                <div align=center>
                    <table class="MsoNormalTable" cellpadding=0 style="width:600.0pt; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0 0 0 0; border: none">
                        <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes">
                            <td style="width:210.0pt;padding:26.25pt 0 22.5pt 0"><p class="MsoNormal"><span style="font-size:10.0pt; font-family:'Tahoma',sans-serif"><a href="https://www.getucon.de" target="_blank"><img src="{{ asset('/images/crmgetucon.png') }}" width=300 style="width:3.125in; border: none" alt="CRM getucon" v:shapes="_x0000_i1026"></a><o:p></o:p></span></p></td>
                            <td style="width:15.0pt;padding:0"></td>
                            <td style="width:210.0pt;padding:26.25pt 0 22.5pt 0"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="mso-yfti-irow:3">
            <td style="width:100.0%;background:#E5E5E5;padding:0">
                <div align=center>
                    <table class="MsoNormalTable" cellspacing=0 cellpadding=0 style="width:600.0pt; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0 0 0 0; border: none">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes; height:.75pt">
                            <td style="padding:0;height:.75pt"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="mso-yfti-irow:3">
            <td style="width:100.0%;background:#F9F9F9;padding:0">
                <div align=center>
                    <table class="MsoNormalTable" cellspacing=0 cellpadding=0 style="width:600.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0; border: none">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                            <td style="width:3.75pt;padding:0"></td>
                            <td style="width:210.0pt;padding:0"><br>
                                <div style="font-size:10.0pt; font-family:'Tahoma',sans-serif">
                                    <p>Hi,</p>
                                    <p>You have just assigned to a ticket. Please check it out.</p>
                                    <table class="ticket-table" cellspacing="0" cellpadding="0" style="width: 100%; border:1px solid black;border-collapse: collapse;">
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Ticket ID:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <a href="{{ url('/update-ticket/' . $ticket->id) }}" target="_blank"><strong> #{{ $ticket->id }}</strong></a>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Subject:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{$ticket->name}}</p>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Organization:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{$organization_name}}</p>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Assigned By:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{$update_by}}</p>
                                            </td>
                                        </tr>
                                        <tr style="border:1px solid black;">
                                            <td style="border:1px solid black; padding:5px;">
                                                <strong>Status:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{$ticket->StatusName}}</p>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Creation Time:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{ Carbon\Carbon::parse($ticket->created_at)->format('d.m.Y [H:i:s]') }}</p>
                                            </td>
                                        </tr>
                                    </table><br>
                                    <strong>Support Team</strong><br>
                                    [automatic e-mail from crm.getucon.de]
                                    <div><br>
                                        <div align=center>
                                            <table class="MsoNormalTable" cellspacing=0 cellpadding=0 style="width:100.0%;background:#F9F9F9;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0; border: none">
                                                <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes">
                                                    <td style="width:100.0%;padding:0">
                                                        <table class="MsoNormalTable" cellspacing=0 cellpadding=0 style="width:600.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0; border: none">
                                                            <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes">
                                                                <td style="width:100.0%;padding:0"><p class="MsoNormal"><strong><span style="font-size:7.0pt;font-family:'Tahoma',sans-serif;color:#0D385C">getucon<sup>®</sup></span></strong><span style="font-size:7.0pt;font-family:'Tahoma',sans-serif;color:#0D385C">| Management & Technology Consultancy</span></p></td>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                            <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes">
                                                                <td style="width:100.0%; padding:0">
                                                                    <table cellpadding="0" cellspacing="0" style="font-size:8.5pt;font-family:'Tahoma',sans-serif;color:#0D385C; border: none">
                                                                        <tr>
                                                                            <td>[Germany]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>+49 (0) 69-34866710</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>[Turkey]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>+90 850 804 0585</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>[E-mail]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td><a style="color:#000 !important;text-decoration:none" href="mailto:support@getucon.de" target="_blank" title="Email"><span style="color:#0D385C">support@getucon.de</span></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>[Web]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td><a style="color:#000 !important;text-decoration:none" href="https://www.getucon.de" target="_blank" title="getucon Website"><span style="color:#0D385C">www.getucon.de</span></a></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div><br><br><br>
                                </div>
                            </td>
                            <td style="width:3.75pt;padding:0"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        @include('emails.defines.getucon-tr-footer')
    </table>
</div>
</body>
</html>