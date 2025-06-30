<html>
<header>
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
</header>
<body style="margin: 0; padding: 0; background: #fff 0% -3% no-repeat;">
<div align=center>

    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width="100%"
           style='width:100.0%;background:white;border-collapse:collapse;mso-yfti-tbllook:
 1184;mso-padding-alt:0cm 0cm 0cm 0cm'>
        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:3.75pt'>
            <td width="100%" style='width:100.0%;background:#1d1868;padding:0cm 0cm 0cm 0cm;
  height:3.75pt;font-size: 10pt;color: #1d1868' align="center">----Update Ticket----</td>
        </tr>
        <tr style='mso-yfti-irow:1'>
            <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600
                           style='width:600.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
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
                           style='width:600.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
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
                           style='width:600.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
   mso-padding-alt:0cm 0cm 0cm 0cm'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=5 style='width:3.75pt;padding:0cm 0cm 0cm 0cm'></td>
                            <td width=280 style='width:210.0pt;padding:0cm 0cm 0cm 0cm'><br>
                                <div style='font-size:10.0pt;font-family:"Tahoma",sans-serif'>


                                    <p>Sehr geehrte Damen und Herren,</p>
                                    <p>es gibt ein Update zu diesem Ticket. Bitte zur Kenntnis nehmen oder Weiteres
                                        veranlassen.</p>


                                    <table class="ticket_table" cellspacing="0" cellpadding="0" width="100%"
                                           style="border:1px solid black;border-collapse: collapse;">
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Ticket
                                                   - ID:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong><a href="{{ url('/update-ticket/' . $ticket->id) }}" target="_blank">#{{$ticket->id}}</a></strong></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Betreff:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">{{$ticket->name}}</td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Organisation:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">{{$organization->org_name}}</td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Status:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{$ticket->StatusName}}</p></td>
                                        </tr>
                                        @if($personnel == 1)
                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Priorität:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$ticket->PriorityName}}</p></td>
                                            </tr>
                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Kategorie:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$ticket->CategoryName}}</p></td>
                                            </tr>
                                            @if($ticket->ParsedDueDate)
                                                <tr style="border: 1px solid black;">
                                                    <td style="border: 1px solid black;padding: 5px;">
                                                        <strong>Geburtstermin:</strong></td>
                                                    <td style="border: 1px solid black;padding: 5px;">
                                                        <p>{{$ticket->ParsedDueDate}}</p></td>
                                                </tr>
                                            @endif
                                        @endif
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Von:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">{{$user_discussion->first_name}} {{$user_discussion->surname}}
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Ticket erstellt am:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">{{$ticket_time}}</td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;" valign="top"><strong>Kommentar:</strong><br>
                                                {{\Carbon\Carbon::parse($discussion_date)->format("d.m.Y [H:i:s]")}}
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">{!!  $discussion_message!!}</td>
                                        </tr>
                                    </table>
                                    <br>

                                    <strong>Support Team</strong><br>
                                    [automatic E-Mail from crm.getucon.de]

                                    <div><br>
                                        <div align=center>
                                            <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
                                                   width="100%" style='width:100.0%;background:#F9F9F9;border-collapse:collapse;
     mso-yfti-tbllook:1184;mso-padding-alt:0cm 0cm 0cm 0cm'>
                                                <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                    <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                                                        <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
                                                               width=600 style='width:600.0pt;border-collapse:collapse;mso-yfti-tbllook:
       1184;mso-padding-alt:0cm 0cm 0cm 0cm'>
                                                            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                                <td width="100%"
                                                                    style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                                                                    <p class=MsoNormal><strong><span style='font-size:7.0pt;font-family:
        "Tahoma",sans-serif;color:#0D385C'>getucon GmbH</span></strong></p>
                                                                </td>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                                <td width="100%"
                                                                    style='width:100.0%;padding:0cm 0cm 0cm 0cm'>


                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                           style='font-size:8.5pt;
        font-family:"Tahoma",sans-serif;color:#0D385C'>
                                                                        <tr>
                                                                            <td>[Tel]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>+49 (0) 69-34866710</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>[E-Mail]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>
                                                                                <a style="color:#000 !important;text-decoration:none"
                                                                                   href="mailto:support@getucon.de"
                                                                                   target="_blank"
                                                                                   title="Email"><span
                                                                                        style='color:#0D385C'>support@getucon.de</span></a>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>[Web]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>
                                                                                <a style="color:#000 !important;text-decoration:none"
                                                                                   href="https://www.getucon.de"
                                                                                   target="_blank"
                                                                                   title="getucon Website"><span
                                                                                        style='color:#0D385C'>www.getucon.de</span></a>
                                                                            </td>
                                                                        </tr>
                                                                    </table>

                                                                </td>

                                                            </tr>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br><br><br>
                                </div>
                            </td>
                            <td width=5 style='width:3.75pt;padding:0cm 0cm 0cm 0cm'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        @include("emails.defines.getucon-de-footer")
    </table>

</div>

</body>
</html>
