<html><header>
    <style>

        .othertable,.othertable td{
            border: 1px solid black;
            border-collapse: collapse;
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
                                <p class=MsoNormal><span style='font-size:10.0pt;font-family:"Tahoma",sans-serif'><a href="https://www.getucon.de" target="_blank"><img border=0 width=300
                                                                                                                       src="{{ asset('/images/getucon-management.png') }}"
                                                                                                                       style='width:3.125in' alt="CRM getucon" v:shapes="_x0000_i1026"></a><o:p></o:p></span></p>
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


                                     Dear Customer,<br><br>

                                    We sent you our first reminder on <strong>{{\Carbon\Carbon::parse($invoice->post_mail_1)->format('d.m.Y')}}</strong> and the second on <strong>{{\Carbon\Carbon::parse($invoice->post_mail_2)->format('d.m.Y')}}</strong> but until now we have not received any response from your side.<br><br>

                                    Therefore, we must inform you that if you have not paid your outstanding payment of <strong>EUR {{number_format($invoice_amount - $total_payments, 2, ',', '.')}} </strong> by <strong>{{\Carbon\Carbon::now()->addDays(7)->format('d.m.Y')}}</strong>, we will have to stop our ongoing service and any kind of services to you.<br><br>

                                    Otherwise, we will be forced to take action and add you to our internal and external blacklist.<br><br>
Behind our services is a team of motivated employees whose commitment we want to reward appropriately and on time. You will certainly understand that we depend on the punctual payment of our invoices.<br><br>
                                    Attached you will find the <strong>Invoice {{$invoice->invoice_no}}</strong> again.<br><br>

                                    <table  width="100%" class="othertable">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Invoice amount:</td>
                                            <td style="padding:2px 5px;text-align: center"><p style=" text-align: right; margin-right:12%;">{{number_format($invoice_amount, 2, ',', '.')}} EUR</p></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Payments received:</td>
                                            <td style="padding:2px 5px;text-align: center"><p style=" text-align: right; margin-right: 12%;">{{number_format($total_payments, 2, ',', '.')}} EUR</p></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px  5px;"><strong>Open payment:</strong></td>
                                            <td style="padding:2px  5px;text-align: center"><p style=" text-align: right; margin-right: 12%;"><strong>{{number_format($invoice_amount - $total_payments, 2, ',', '.')}} EUR</strong></p></td>
                                        </tr>
                                        </tbody>
                                    </table><br><br>


                                    <table width="100%" class="othertable">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Date of Invoice:</td>
                                            <td style="text-align: center;padding:2px 5px;"><p style=" text-align: right; margin-right: 12%;">{{\Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y')}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Payment term was:</td>
                                            <td style="text-align: center;padding:2px 5px;"><p style=" text-align: right; margin-right: 12%;">{{$invoice->day}} DAYS</p></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;"><strong>Deadline for payment was:</strong></td>
                                            <td style="text-align: center;padding:2px 5px;"><p style=" text-align: right; margin-right: 12%;"><strong>{{\Carbon\Carbon::parse($invoice->deadline)->format('d.m.Y')}}</strong></p></td>
                                        </tr>
                                        </tbody>
                                    </table><br><br>

                                    <strong>Delay with payment since: {{count(\Carbon\CarbonPeriod::create(\Carbon\Carbon::parse($invoice->deadline)->format('d.m.Y'), \Carbon\Carbon::now()))-1}} days.</strong><br><br>

                                    @if($invoice->mail_text_3)
                                        <strong>Additional Information:</strong><br>
                                    @endif
                                    @if($invoice->mail_text_3)
                                        {!! $invoice->mail_text_3 !!}<br><br>
                                    @endif



                                    If you have already transferred the payment, please ignore this email.<br><br>

                                    If you have any questions, please do not hesitate to contact us.<br><br><br>

                                                                      Best Regards,<br><br>

                                    <strong>Accounting</strong><br>
                                    [automatic e-mail from crm.getucon.de]<br><br>



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
                                                                <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>
                                                                    <p class=MsoNormal><strong><span style='font-size:7.0pt;font-family:
        "Tahoma",sans-serif;color:#0D385C'>getucon<sup>®</sup></span></strong><span
                                                                            style='font-size:7.0pt;font-family:"Tahoma",sans-serif;color:#0D385C'>
        | Management & Technology Consultancy</span></p>
                                                                </td>
                                                                <tr><td>&nbsp;</td></tr>
                                                                <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
                                                                <td width="100%" style='width:100.0%;padding:0cm 0cm 0cm 0cm'>





                                                                <table border="0" cellpadding="0" cellspacing="0" style='font-size:8.5pt;
        font-family:"Tahoma",sans-serif;color:#0D385C'>
                                                                    <tr><td>[Türkiye]</td><td>&nbsp;</td><td>&nbsp;</td><td>+90 850 804 0585</td></tr>
                                                                    <tr><td>[Germany]</td><td>&nbsp;</td><td>&nbsp;</td><td>+49 69-34866710</td></tr>
                                                                      <tr><td>&nbsp;</td></tr>
                                                                    <tr><td>[E-mail]</td><td>&nbsp;</td><td>&nbsp;</td><td><a style="color:#000 !important;text-decoration:none" href="mailto:buchhaltung@getucon.de" target="_blank"
               title="Email"><span style='color:#0D385C'>buchhaltung@getucon.de</span></a></td></tr>
                                                                    <tr><td>[Web]</td><td>&nbsp;</td><td>&nbsp;</td><td><a style="color:#000 !important;text-decoration:none" href="https://www.getucon.de" target="_blank" title="getucon Website"><span
                                                                                    style='color:#0D385C'>www.getucon.de</span></a></td></tr>
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
                            <td width=5 style='width:3.75pt;padding:0cm 0cm 0cm 0cm'></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        @include("emails.defines.getucon-tr-footer")
    </table>

</div>

</body>
</html>
