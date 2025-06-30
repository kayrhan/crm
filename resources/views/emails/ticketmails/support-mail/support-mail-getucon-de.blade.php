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
        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:3.75pt'>
            <td width="100%" style='width:100.0%;background:#1d1868;padding:0cm 0cm 0cm 0cm;
  height:3.75pt'></td>
        </tr>
        <tr style='mso-yfti-irow:1'>
            <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600
                           style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
   mso-padding-alt:0cm 0cm 0cm 0cm'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=280 style='width:210.0pt;padding:26.25pt 0cm 22.5pt 0cm'>
                                <p class=MsoNormal><span style='font-size:10.0pt;font-family:"Tahoma",sans-serif'><img
                                            border=0 width=300
                                            src="{{ asset('/images/crmgetucon.png') }}"
                                            style='width:3.125in' alt="CRM getucon" v:shapes="_x0000_i1026"><o:p></o:p></span>
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
                                    <p>Sehr geehrte Damen und Herren,</p>

                                    <p>wir haben Ihre Anfrage erhalten und werden uns schnellstmöglich mit Ihnen in Verbindung setzen.</p>


                                    <table class="ticket_table" cellspacing="0" cellpadding="0" width="100%"
                                           style="border:1px solid black;border-collapse: collapse;">
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Ticket
                                                    ID:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong><a href="{{ url('/update-ticket/' . $ticket->id) }}" target="_blank">#{{$ticket->id}}</a></strong></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Betreff:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;"><p>{{$ticket->name}}</p>
                                            </td>
                                        </tr>

                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <strong>Beschreibung:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">{!! $ticket->description!!}</td>
                                        </tr>

                                    </table>
                                    <p>Für Rückfragen stehen wir Ihnen gerne zur Verfügung.</p>

                                    <strong>Support Team</strong><br>
                                    [automatic e-mail from crm.getucon.de]

                                    <div><br>
                                        <div align=center>
                                            <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
                                                   width="100%" style='width:100.0%;background:#F9F9F9;border-collapse:collapse;
     mso-yfti-tbllook:1184;mso-padding-alt:0cm 0cm 0cm 0cm'>
                                                <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                    <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                                                        <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
                                                               width=600 style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:
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
                                                                            <td>[Germany]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>+49 69-34866710</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>[E-mail]</td>
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
        <tr style='mso-yfti-irow:4;mso-yfti-lastrow:yes'>
            <td width="100%" style='width:100.0%;background:#1d1868;padding:0cm 0cm 0cm 0cm;'>
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600
                           style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;
   mso-padding-alt:0cm 0cm 0cm 0cm'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;'>
                            <td width=600 style='width:450.0pt;padding:3.0pt 0cm 2.0pt 0cm'>
                                <p class=MsoNormal align=center style='text-align:center'><span
                                        style='font-size:7.5pt;font-family:"Tahoma",sans-serif;color:#FFFFFF'>getucon GmbH | Taunusanlage 8 | DE-60329 Frankfurt am Main
                                    </span></p>
                            </td>
                        </tr>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;'>
                            <td width=600 style='width:450.0pt;padding:2.0pt 0cm 2.0pt 0cm'>
                                <p class=MsoNormal align=center style='text-align:center'><span
                                        style='font-size:7.5pt;font-family:"Tahoma",sans-serif;color:#FFFFFF'>
    [T] 069-34866710 | [W] <a href="https://www.getucon.de"  target="_blank" style="color:#fff !important;text-decoration:none" >www.getucon.de</a> | [E] <a style="color:#fff !important;text-decoration:none" href="mailto:info@getucon.de" target="_blank"
                                                         title="Email"><span style='color:#ffffff'>info@getucon.de</span></a>
                                    </span></p>
                            </td>
                        </tr>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;'>
                            <td width=600 style='width:450.0pt;padding:2.0pt 0cm 3.0pt 0cm'>
                                <p class=MsoNormal align=center style='text-align:center'><span
                                        style='font-size:7.5pt;font-family:"Tahoma",sans-serif;color:#FFFFFF'>Please visit our sites: <a href="https://www.getudc.de"  target="_blank" style="color:#fff !important;text-decoration:none" >www.getudc.de</a> | <a href="https://www.getusys.de"  target="_blank" style="color:#fff !important;text-decoration:none" >www.getusys.de</a> | <a href="https://www.getusoft.de"  target="_blank" style="color:#fff !important;text-decoration:none" >www.getusoft.de</a> | <a href="https://www.getumedia.de"  target="_blank" style="color:#fff !important;text-decoration:none" >www.getumedia.de</a>
                                    </span></p>
                            </td>
                        </tr>

                    </table>
                </div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
