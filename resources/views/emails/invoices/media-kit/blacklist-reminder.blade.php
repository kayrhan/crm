<html>
<head>
    <style>
        .othertable, .othertable td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#fff 0 -3% no-repeat">
<div align="center">
    <table class="MsoNormalTable" style="width:100.0%; background:white; border:none; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0">
        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; height:3.75pt">
            <td style="width:100.0%; background:#ecb042; padding:0; height:3.75pt"></td>
        </tr>
        <tr style="mso-yfti-irow:1">
            <td style="width:100.0%; padding:0">
                <div align="center">
                    <table class="MsoNormalTable" style="width:450.0pt; border:none; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                            <td style="width:210.0pt;padding:26.25pt 0 22.5pt 0">
                                <p class="MsoNormal">
                                    <span style="font-size:10.0pt; font-family:'Tahoma', sans-serif"><a href="https://www.mediakitproduction.com" target="_blank"><img width="300" src="{{ asset("/images/logos/MediaKit-light.png") }}" style="width:3.125in" alt="MediaKit Production"></a></span>
                                </p>
                            </td>
                            <td style="width:15.0pt; padding:0"></td>
                            <td style="width:210.0pt; padding:26.25pt 0 22.5pt 0"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="mso-yfti-irow:3">
            <td style="width:100.0%; background:#E5E5E5; padding:0">
                <div align="center">
                    <table class="MsoNormalTable" style="width:450.0pt; border: none; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes; height:.75pt">
                            <td style="padding:0; height:.75pt"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="mso-yfti-irow:3">
            <td style="width:100.0%; background: white; padding:0">
                <div align="center">
                    <table class="MsoNormalTable" style="width:450.0pt; border:none; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                            <td style="width:3.75pt; padding:0"></td>
                            <td style="width:210.0pt; padding:0"><br>
                                <div style="font-size:10.0pt; font-family:'Tahoma', sans-serif">
                                    Dear MediaKit Production,<br><br>
                                    We have a blacklisting for customer <strong>{{ $customer->org_name ?? "" }}</strong>.<br><br>
                                    Please take action.<br><br>
                                    <table class="othertable">
                                        <tbody>
                                            <tr>
                                                <td style="padding:2px 5px">Invoice Amount:</td>
                                                <td style="padding:2px 5px; text-align:center"><p style="text-align:right; margin-right:12%">{{ number_format($invoice_amount, 2, ',', '.') }} EUR</p></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:2px 5px">Received Payments:</td>
                                                <td style="padding:2px 5px; text-align:center"><p style="text-align:right; margin-right:12%">{{ number_format($total_payments, 2, ',', '.') }} EUR</p></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:2px 5px"><strong>Open Payment:</strong></td>
                                                <td style="padding:2px 5px; text-align:center"><p style="text-align:right; margin-right:12%"><strong>{{ number_format($invoice_amount - $total_payments, 2, ',', '.') }} EUR</strong></p></td>
                                            </tr>
                                        </tbody>
                                    </table><br><br>
                                    <table class="othertable">
                                        <tbody>
                                            <tr>
                                                <td style="padding:2px 5px">Date of Invoice:</td>
                                                <td style="text-align:center; padding:2px 5px"><p style="text-align:right; margin-right:12%">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format("d.m.Y") }}</p></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:2px 5px">Payment term was:</td>
                                                <td style="text-align:center; padding:2px 5px"><p style="text-align:right; margin-right:12%">{{ $invoice->day }} DAYS</p></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:2px 5px"><strong>Deadline for payment was:</strong></td>
                                                <td style="text-align:center; padding:2px 5px"><p style="text-align:right; margin-right:12%"><strong>{{ \Carbon\Carbon::parse($invoice->deadline)->format("d.m.Y") }}</strong></p></td>
                                            </tr>
                                        </tbody>
                                    </table><br><br>
                                    <strong>Delay with payment since: {{ count(\Carbon\CarbonPeriod::create(\Carbon\Carbon::parse($invoice->deadline)->format("d.m.Y"), \Carbon\Carbon::now())) - 1 }} days.</strong><br><br>
                                    If you have already transferred the payment, please ignore this email.<br><br>
                                    If you have any questions, please do not hesitate to contact us.<br><br><br>
                                    Best Regards,<br><br>
                                    <strong>Accounting</strong><br>
                                    [automatic e-mail from accounting@mediakitproduction.com]<br><br>
                                    <div><br>
                                        <div align="center">
                                            <table class="MsoNormalTable" style="width:100.0%; background: white; border:none; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0">
                                                <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                                                    <td style="width:100.0%; padding:0">
                                                        <table class="MsoNormalTable" style="width:450.0pt; border:none; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0">
                                                            <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                                                                <td style="width:100.0%; padding:0">
                                                                    <p class="MsoNormal">
                                                                        <strong><span style="font-size:7.0pt; font-family:'Tahoma', sans-serif; color:#0D385C">MediaKit Production</span></strong>
                                                                    </p>
                                                                </td>
                                                                <tr><td>&nbsp;</td></tr>
                                                                <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                                                                <td style="width:100.0%; padding:0">
                                                                <table style="border:none; border-collapse:collapse; font-size:8.5pt; font-family:'Tahoma', sans-serif; color:#0D385C">
                                                                    <tr><td>[Türkiye]</td><td>&nbsp;</td><td>&nbsp;</td><td>+90 232 935 11 05</td></tr>
                                                                    <tr><td>&nbsp;</td></tr>
                                                                    <tr><td>[E-mail]</td><td>&nbsp;</td><td>&nbsp;</td><td><a style="color:#000 !important; text-decoration:none" href="mailto:accounting@mediakitproduction.com" target="_blank" title="Email"><span style="color:#0D385C">accounting@mediakitproduction.com</span></a></td></tr>
                                                                    <tr><td>[Web]</td><td>&nbsp;</td><td>&nbsp;</td><td><a style="color:#000 !important; text-decoration:none" href="https://www.mediakitproduction.com" target="_blank" title="MediaKit Production Website"><span style="color:#0D385C">www.mediakitproduction.com</span></a></td></tr>
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
                            <td style="width:3.75pt; padding:0"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        @include("emails.defines.MediaKit-footer")
    </table>
</div>
</body>
</html>