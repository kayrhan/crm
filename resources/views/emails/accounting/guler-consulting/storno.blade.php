<html>
<head></head>
<body style="margin:0; padding:0; background:#fff 0 -3% no-repeat">
<div align=center>
    <table class="MsoNormalTable" style="width:100.0%; background:white; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0; border: none">
        <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:3.75pt">
            <td style="width:100.0%; background:#1d1868; padding:0; height:3.75pt"></td>
        </tr>
        <tr style="mso-yfti-irow:1">
            <td style="width:100%; padding:0">
                <div align=center>
                    <table class="MsoNormalTable" style="width:450.0pt; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0; border: none">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                            <td style="width:210.0pt; padding:26.25pt 0 22.5pt 0">
                                <p class="MsoNormal"><span style="font-size:10.0pt; font-family:'Tahoma',sans-serif"><a href="https://www.gulcons.com" target="_blank"><img width=300 src="{{ asset('/images/guler-consulting.png') }}" style="width:3.125in; border: none" alt="Guler Consulting" v:shapes="_x0000_i1026"></a><o:p></o:p></span></p>
                            </td>
                            <td style="width:15.0pt; padding:0"></td>
                            <td style="width:210.0pt; padding:26.25pt 0 22.5pt 0"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="mso-yfti-irow:3">
            <td style="width:100%; background:#E5E5E5; padding:0">
                <div align=center>
                    <table class="MsoNormalTable" style="width:450.0pt; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0; border: none">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes; height:.75pt">
                            <td style="padding:0; height:.75pt"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr style="mso-yfti-irow:3">
            <td style="width:100.0%; background:#F9F9F9; padding:0">
                <div align=center>
                    <table class="MsoNormalTable" style="width:450.0pt; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0; border: none">
                        <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                            <td style="width:3.75pt; padding:0"></td>
                            <td style="width:210.0pt; padding:0"><br>
                                <div style="font-size:10.0pt; font-family:'Tahoma',sans-serif">
                                    Dear Customer,<br><br>
                                    Your invoice has been cancelled.<br><br>
                                    Attached you can find the PDF of the Cancellation Invoice.<br><br>
                                    If you have any questions, please do not hesitate to contact us.<br><br><br>
                                    @if($additional_text)
                                    <strong>Additional Information:</strong><br>
                                    {!! $additional_text !!}<br><br>
                                    @endif
                                    Best Regards,<br><br>
                                    <strong>Accounting</strong><br>
                                    [automatic e-mail from accounting@gulcons.com]<br><br>
                                    <div><br>
                                        <div align=center>
                                            <table class="MsoNormalTable" style="width:100.0%; background:#F9F9F9; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0; border: none">
                                                <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                                                    <td style="width:100.0%; padding:0">
                                                        <table class="MsoNormalTable" style="width:450.0pt; border-collapse:collapse; mso-yfti-tbllook:1184; mso-padding-alt:0; border: none">
                                                            <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                                                                <td style="width:100.0%; padding:0">
                                                                    <p class="MsoNormal"><strong><span style="font-size:7.0pt; font-family:'Tahoma',sans-serif; color:#0D385C">Guler Consulting</span></strong></p>
                                                                </td>
                                                            <tr><td>&nbsp;</td></tr>
                                                            <tr style="mso-yfti-irow:0; mso-yfti-firstrow:yes; mso-yfti-lastrow:yes">
                                                                <td style="width:100.0%; padding:0">
                                                                    <table style="font-size:8.5pt; font-family:'Tahoma',sans-serif; color:#0D385C; border: none">
                                                                        <tr><td>[Türkiye]</td><td>&nbsp;</td><td>&nbsp;</td><td>+90 850 804 0585</td></tr>
                                                                        <tr><td>[Germany]</td><td>&nbsp;</td><td>&nbsp;</td><td>+49 69-34866710</td></tr>
                                                                        <tr><td>&nbsp;</td></tr>
                                                                        <tr><td>[E-mail]</td><td>&nbsp;</td><td>&nbsp;</td><td><a style="color:#000 !important; text-decoration:none" href="mailto:accounting@gulcons.com" target="_blank" title="Email"><span style="color:#0D385C">accounting@gulcons.com</span></a></td></tr>
                                                                        <tr><td>[Web]</td><td>&nbsp;</td><td>&nbsp;</td><td><a style="color:#000 !important; text-decoration:none" href="https://www.gulcons.com" target="_blank" title="Guler Consulting Website"><span style="color:#0D385C">www.gulcons.com</span></a></td></tr>
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
        @include("emails.defines.guler-consulting-footer")
    </table>
</div>
</body>
</html>