<html>
<head>
    <style>
        div {
            font-family: "Tahoma", sans-serif;
        }

        .email-table {
            font-size: 10.0pt;
            font-family: "Tahoma", sans-serif;
        }

        .email-table td {
            padding: 5px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .email-table p {
            font-family: "Tahoma", sans-serif;
            font-size: 10.0pt;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background: #FFFFFF 0 -3% no-repeat;">
<div align=center>

    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width="100%" style='width:100.0%;background:white;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0'>
        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:3.75pt'>
            <td style='padding:0; width:100%; background:#1d1868; height:3.75pt;font-size: 10pt;color: #1d1868' align="center">Invoice Repeat Reminder</td>
        </tr>
        <tr style='mso-yfti-irow:1'>
            <td style="padding:0; width:100%;">
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600 style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=280 style='width:210.0pt;padding:26.25pt 0 22.5pt 0'><p class=MsoNormal><span style='font-size:10.0pt;font-family:"Tahoma",sans-serif'><a href="https://www.getucon.de" target="_blank"><img border=0 width=300 src="{{ asset('/images/crmgetucon.png') }}" style='width:3.125in' alt="CRM getucon" v:shapes="_x0000_i1026"></a><o:p></o:p></span></p></td>
                            <td width=20 style='width:15.0pt;padding:0 0 0 0'></td>
                            <td width=280 style='width:210.0pt;padding:26.25pt 0 22.5pt 0'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style='mso-yfti-irow:3'>
            <td style="background:#E5E5E5; padding:0; width:100%;">
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600 style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;height:.75pt'>
                            <td style='padding:0 0 0 0;height:.75pt'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style='mso-yfti-irow:3'>
            <td style="background:#F9F9F9; padding:0; width:100%;">
                <div align=center>
                    <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600 style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0'>
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=5 style='width:3.75pt;padding:0 0 0 0'></td>
                            <td width=280 style='width:210.0pt;padding:0 0 0 0'><br>
                                <div style='font-size:10.0pt;font-family:"Tahoma",sans-serif'>
                                    <p>Dear Managers,</p>
                                    <p>There is an upcoming repeated invoice. Please check it out.</p>
                                    <table class="email-table" cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse; border: 1px solid black;">
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black; padding: 5px;"><strong>Invoice Number:</strong></td>
                                            <td style="border: 1px solid black; padding: 5px;"><a href="{{ url('/' . $link) }}" target="_blank"><strong>{{$invoice_number}}</strong></a></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black; padding: 5px;"><strong>Organization:</strong></td>
                                            <td style="border: 1px solid black; padding: 5px;"><p>{{$organization}}</p></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black; padding: 5px;"><strong>Customer:</strong></td>
                                            <td style="border: 1px solid black; padding: 5px;"><p>{{$customer}}</p></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black; padding: 5px;"><strong>Invoice Amount:</strong></td>
                                            <td style="border: 1px solid black; padding: 5px;"><p>{{number_format($invoice_amount, 2, ',', '.')}} €</p></td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black; padding: 5px;"><strong>Repeat Date:</strong></td>
                                            <td style="border: 1px solid black; padding: 5px;"><p>{{\Carbon\Carbon::parse($repeat_date)->format('d.m.Y')}}</p></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <strong>CRM getucon</strong><br>
                                    [automatic e-mail from crm.getucon.de]
                                    <div><br>
                                        <div align=center>
                                            <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width="100%" style='width:100.0%;background:#F9F9F9;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0'>
                                                <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                    <td style="padding:0; width:100%;">
                                                        <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=600 style='width:450.0pt;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0 0 0 0'>
                                                            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                                <td style="padding:0; width:100%;"><p class=MsoNormal><strong><span style='font-size:7.0pt;font-family:"Tahoma",sans-serif;color:#0D385C'>getucon<sup>®</sup></span></strong><span style='font-size:7.0pt;font-family:"Tahoma",sans-serif;color:#0D385C'>| Management & Technology Consultancy</span></p></td>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                            <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                                <td style="padding:0; width:100%;">
                                                                    <table border="0" cellpadding="0" cellspacing="0" style='font-size:8.5pt;font-family:"Tahoma",sans-serif;color:#0D385C'>
                                                                        <tr>
                                                                            <td>[Germany]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>+49 69-34866710</td>
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
                                                                            <td><a style="color:#000 !important;text-decoration:none" href="mailto:buchhaltung@getucon.de" target="_blank" title="Email"><span style='color:#0D385C'>buchhaltung@getucon.de</span></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>[Web]</td>
                                                                            <td>&nbsp;</td>
                                                                            <td>&nbsp;</td>
                                                                            <td><a style="color:#000 !important;text-decoration:none" href="https://www.getucon.de" target="_blank" title="Website"><span style='color:#0D385C'>www.getucon.de</span></a></td>
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
                                    </div><br><br><br>
                                </div>
                            </td>
                            <td width=5 style='width:3.75pt;padding:0 0 0 0'></td>
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
