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
                                                                                                                       src="{{ asset('/images/logos/' . $logo) }}"
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




                                    Sehr geehrte Damen und Herren,<br><br>

                                    nach unserer Zahlungserinnerung vom <strong>{{\Carbon\Carbon::parse($invoice->post_mail_1)->format('d.m.Y')}}</strong> und Mahnung vom <strong>{{\Carbon\Carbon::parse($invoice->post_mail_2)->format('d.m.Y')}}</strong> konnten wir bis dato keinen Zahlungseingang unserer offenen Forderung feststellen.<br><br>

                                    Somit müssen wir Ihnen mitteilen, dass wir unseren Service und jegliche Art von Leistungen für Sie einstellen müssen, sollten der Betrag i. H. v. <strong>{{number_format($invoice_amount - $total_payments, 2, ',', '.')}} EUR</strong> nicht bis zum <strong>{{\Carbon\Carbon::now()->addDays(7)->format('d.m.Y')}}</strong> auf unserem Geschätfskonto eingegangen sein.<br><br>

                                    Hinter unseren Leistungen steht ein Team engagierter MitarbeiterInnen, deren Einsatz wir angemessen und pünktlich honorieren möchten. Sicher verstehen Sie, dass wir dazu auf die fristgerechte Bezahlung unserer Rechnungen angewiesen sind.<br><br>

                                    Im Anhang finden Sie nochmals unsere <strong>Rechnung {{$invoice->invoice_no}}.</strong><br><br>

                                    <table  width="100%" class="othertable">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Rechnungssumme:</td>
                                            <td style="padding:2px 5px;text-align: center"><p style=" text-align: right; margin-right:12%;">{{number_format($invoice_amount, 2, ',', '.')}} EUR</p></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Bisher geleistete Zahlung(en):</td>
                                            <td style="padding:2px 5px;text-align: center"><p style=" text-align: right; margin-right: 12%;">{{number_format($total_payments, 2, ',', '.')}} EUR</p></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px  5px;"><strong>Offene Forderung:</strong></td>
                                            <td style="padding:2px  5px;text-align: center"><p style=" text-align: right; margin-right: 12%;"><strong>{{number_format($invoice_amount - $total_payments, 2, ',', '.')}} EUR</strong></p></td>
                                        </tr>
                                        </tbody>
                                    </table><br><br>


                                    <table width="100%" class="othertable">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Rechnungsdatum:</td>
                                            <td style="text-align: center;padding:2px 5px;"><p style=" text-align: right; margin-right: 12%;">{{\Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y')}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;">Ihr Zahlunsgziel zur Rechnung war:</td>
                                            <td style="text-align: center;padding:2px 5px;"><p style=" text-align: right; margin-right: 12%;">{{$invoice->day}} TAGE</p></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding:2px 5px;"><strong>Fälligkeitsdatum war:</strong></td>
                                            <td style="text-align: center;padding:2px 5px;"><p style=" text-align: right; margin-right: 12%;"><strong>{{\Carbon\Carbon::parse($invoice->deadline)->format('d.m.Y')}}</strong></p></td>
                                        </tr>
                                        </tbody>
                                    </table><br><br>

                                    <strong>Unsere Rechnung ist in Verzug seit {{count(\Carbon\CarbonPeriod::create(\Carbon\Carbon::parse($invoice->deadline)->format('d.m.Y'), \Carbon\Carbon::now()))-1}} Tagen.</strong><br><br>

                                    @if($invoice->mail_text_3)
                                        <strong>Sonstige Anmerkung:</strong><br>
                                    @endif
                                    @if($invoice->mail_text_3)
                                        {!! $invoice->mail_text_3 !!}<br><br>
                                    @endif

                                    Sollte die Zahlung inzwischen erfolgt sein, bitten wir Sie, diese E-Mail als gegenstandslos zu betrachten.<br><br>

                                    Sollten Sie die Überweisung nicht fristgemäß vornehmen, sind wir gezwungen die Forderung an unser Inkassounternehmen abzugeben, was mit weiteren Kosten zu Ihren Lasten verbunden sein wird.
                                    Dies ist dıe letzte Zahlungsaufforderung. <br><br>
                                    Für Rückfragen stehen wir Ihnen gerne zur Verfügung.<br><br>

                                    Mit freundlichen Grüßen,<br><br>



                                    <strong>Buchhaltung</strong><br>
                                    [automatische e-mail aus crm.getucon.de]<br><br>

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
        "Tahoma",sans-serif;color:#0D385C'>getucon GmbH</span></strong><span style='font-size:8.5pt;
        font-family:"Tahoma",sans-serif;color:#0D385C'><br><br>
        [T] 069-34866710<br>
        [E] <a style="color:#000 !important;text-decoration:none" href="mailto:buchhaltung@getucon.de" target="_blank"
               title="Email"><span style='color:#0D385C'>buchhaltung@getucon.de</span></a><br>
        [W] <a style="color:#000 !important;text-decoration:none" href="https://www.getucon.de" target="_blank" title="getucon Website"><span
                                                                                    style='color:#0D385C'>www.getucon.de</span></a> <o:p></o:p></span></p>
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
