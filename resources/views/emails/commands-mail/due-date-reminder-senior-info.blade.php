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
                                    <p>The tickets on the listed personnel have past the due dates.</p>
                                    <p>Please take action.</p>
                                    <p>Listed personnels:</p>

                                    <table class="ticket_table" cellspacing="0" cellpadding="0" width="100%"
                                           style="border:1px solid black;border-collapse: collapse;">

                                        <tr style="border: 1px solid black;">
                                            <td colspan="2" style="border: 1px solid black;padding: 5px;">
                                                <strong><small>Assigned Personnels</small></strong></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong><small>Personnel</small></strong></td>
                                            <td style="border: 1px solid black;padding: 5px;"><strong><small>Due Date Ticket Count</small></strong></td>
                                        </tr>
                                        @foreach($personnels as $personnel)
                                        <tr>
                                            <td style="border: 1px solid black;padding: 5px;">{{ $personnel["name_surname"] }}</td>
                                            <td style="border: 1px solid black;padding: 5px;"><a href="{{ url('/tickets?due_date_personnel=' . $personnel["ids"]) }}" target="_blank" style="font-weight: bold;"> {{ $personnel["total"] }}</a></td>
                                        </tr>
                                        @endforeach
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
