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


                                    <p>Hello,</p>
                                    <p>Your service is about to expire.</p><br>


                                    <table class="ticket_table" cellspacing="0" cellpadding="0" width="100%"
                                           style="border:1px solid black;border-collapse: collapse;">
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>ID:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <a href="{{ url("/services/" . $service->id) }}" target="_blank"><strong> #{{ $service->id }}</strong></a>
                                              </td>
                                        </tr>

                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Povider:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$service->provider}}</p></td>
                                            </tr>
                                        <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Service Type:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{$service_type}}</p></td>
                                            </tr>
                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Service Amount:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{number_format($service->service_amount, 2, ",", ".")}}</p>
                                                </td>
                                            </tr>
                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <strong>Beginning Date:</strong></td>
                                                <td style="border: 1px solid black;padding: 5px;">
                                                    <p>{{\Carbon\Carbon::parse($service->beginning_date)->format("d.m.Y")}}</p></td>
                                            </tr>


                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Expiring Date:</strong>
                                            </td>
                                            <td style="border: 1px solid black;padding: 5px;">
                                                <p>{{\Carbon\Carbon::parse($service->expiring_date)->format("d.m.Y")}}</p>
                                            </td>
                                        </tr>
                                        <tr style="border: 1px solid black;">
                                            <td style="border: 1px solid black;padding: 5px;"><strong>Created By:</strong></td>
                                            <td style="border: 1px solid black;padding: 5px;"><p>{{ $user }}</p>
                                            </td>
                                        </tr>

                                    </table>
                                    <br>


                                    <strong>CRM getucon</strong><br>
                                    [automatic email from crm.getucon.de]

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
        "Tahoma",sans-serif;color:#0D385C'>getucon<sup>®</sup></span></strong><span
                                                                            style='font-size:7.0pt;font-family:"Tahoma",sans-serif;color:#0D385C'>
        | Management & Technology Consultancy</span></p>
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
                                                                            <td>
                                                                                <a style="color:#000 !important;text-decoration:none"
                                                                                   href="mailto:no-reply@getucon.de"
                                                                                   target="_blank"
                                                                                   title="Email"><span
                                                                                        style='color:#0D385C'>no-reply@getucon.de</span></a>
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
                        <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                            <td width=600 style='width:450.0pt;padding:6.0pt 0cm 6.0pt 0cm'>
                                <p class=MsoNormal align=center style='text-align:center'><span
                                        style='font-size:8.5pt;font-family:"Tahoma",sans-serif;color:#FFFFFF;font-weight: bold'>getucon® | Management & Technology Consultancy
<br>


                                    </span></p>
                            </td>
                        </tr>
                        @include("emails.defines.getucon-tr-footer")
                    </table>
                </div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
