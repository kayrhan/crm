<html>
<head>
    <style>


        div {
            font-family: "Tahoma", sans-serif;
        }

        .othertable, .othertable td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .ticket_table {
            font-size: 10.0pt;
            font-family: "Tahoma", sans-serif;


        }

        .ticket_table td {
            padding-left: 5px;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-right: 5px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .ticket_table p {
            font-family: "Tahoma", sans-serif;
            font-size: 10.0pt;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background: #fff 0% -3% no-repeat;">
<div align=center>

    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width="100%"
           style='width:100.0%;background:white;border-collapse:collapse;mso-yfti-tbllook:
 1184;mso-padding-alt:0cm 0cm 0cm 0cm'>
        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:6pt'>
            <td width="100%" style='width:100.0%;background:#1d1868;padding:0cm 0cm 0cm 0cm;
  height:10pt'></td>
        </tr>
        <tr style='mso-yfti-irow:1'>
            <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600
                           style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
   mso-padding-alt:0cm 0cm 0cm 0cm'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=280 style='width:210.0pt;padding:26.25pt 0cm 22.5pt 0cm'>
                                <p class=MsoNormal><span style='font-size:10.0pt;font-family:"Tahoma",sans-serif'><a href="https://www.getucon.de" target="_blank"><img
                                                border=0 width=300
                                                src="{{ asset('/images/crmgetucon.png') }}"
                                                style='width:3.125in' alt="CRM getucon" v:shapes="_x0000_i1026"></a><o:p></o:p></span>
                                </p>
                            </td>
                            <td width=20 style='width:15.0pt;padding:0cm 0cm 0cm 0cm'></td>
                            <td width=280 style='width:210.0pt;padding:26.25pt 0cm 22.5pt 0cm'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style='mso-yfti-irow:3'>
            <td width="100%" style='width:100.0%;background:#E5E5E5;padding:0cm 0cm 0cm 0cm'>
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600
                           style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
   mso-padding-alt:0cm 0cm 0cm 0cm'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;
    height:.75pt'>
                            <td style='padding:0cm 0cm 0cm 0cm;height:.75pt'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style='mso-yfti-irow:3'>
            <td width="100%" style='width:100.0%;background:#F9F9F9;padding:0cm 0cm 0cm 0cm'>
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600
                           style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
   mso-padding-alt:0cm 0cm 0cm 0cm'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=5 style='width:3.75pt;padding:0cm 0cm 0cm 0cm'></td>
                            <td width=280 style='width:210.0pt;padding:0cm 0cm 0cm 0cm'><br>
                                <div style='font-size:10.0pt;font-family:"Tahoma",sans-serif'>


                                    <p>Hello,</p>
                                    <p>The due date of the ticket assigned to you has past.</p>
                                    <p>Please check your ticket.</p>
                                    <p>Ticket Information:</p>

                                    <table class="ticket_table" cellspacing="0" cellpadding="0" width="100%"
                                           style="border:1px solid black;border-collapse: collapse;">
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Ticket
                                                    ID:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong><a href="{{ url('/update-ticket/' . $ticket->id) }}" target="_blank">#{{$ticket->id}}</a></strong></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Subject:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;"><p>{{$ticket->name}}</p>
                                            </td>
                                        </tr>

                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Organization:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$ticket->organization->org_name}}</p></td>
                                            </tr>

                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Assigned Personnel:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$assigned_user->first_name. " ". $assigned_user->surname}}</p></td>
                                            </tr>

                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Status:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$ticket->StatusName}}</p></td>
                                            </tr>

                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Priority:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$ticket->PriorityName}}</p></td>
                                            </tr>
                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Category:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$ticket->CategoryName}}</p></td>
                                            </tr>
                                            @if($ticket->ParsedDueDate)
                                                <tr style="border: 1px solid black;">
                                                    <td style="border: 1px solid black;padding: 5px;">
                                                        <strong>Due Date:</strong></td>
                                                    <td style="border: 1px solid black;padding: 5px;">
                                                        <p style="color: #c00707"><b>{{$ticket->ParsedDueDate}}</b></p></td>
                                                </tr>
                                            @endif
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Description:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">{!! $ticket->description!!}</td>
                                        </tr>

                                    </table>
                                    <br>

                                    <strong>Support Team</strong><br>
                                    [automatic e-mail from crm.getucon.de]

                                    <br>
                                    <br>
                                    <br>
                                </div>
                            </td>
                            <td width=5 style='width:3.75pt;padding:0cm 0cm 0cm 0cm'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:6pt'>
            <td width="100%" style='width:100.0%;background:#1d1868;padding:0cm 0cm 0cm 0cm;
  height:10pt'></td>
        </tr>
    </table>

</div>
</body>
</html>
