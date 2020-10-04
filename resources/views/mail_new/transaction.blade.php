@extends('mail_new.layout.index')
@section('header')
<tr>
    <td bgcolor="#f3f3f3" style="background: url(images/header-bgtop2.png) center center / cover no-repeat, #f3f3f3;">
        <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
            <tr>
                <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                    <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
                         <tr><td class="w3l-4h user_name" height="84px">Hello, {{ ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection
@section('content')
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td bgcolor="#f3f3f3">
            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                <tr>
                    <td bgcolor="#FFFFFF">
                        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="agile1 scale">
                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 22px; text-align:justify;">
                                    This is an acknowledgement of Payment. Your transaction <span style="color: #1c85c8;">(ID : {{ $monthsale->transaction_id }})</span> has been done successfully.
                                </td>
                            </tr>

                            <tr>
	                            <td class="esd-structure w3-gr esd-block-text" esd-general-paddings-checked="false" style="background-color: #FFFFFF; line-height: 150%; padding: 15px 0 0;" align="center" height="84">
	                                <table class="es-left" width="100%" cellspacing="0" cellpadding="0">
	                                    <tbody>
	                                    	<tr>
				                                <td class="agile-main scale-center-both" style="font-weight: 700; color: #1c85c8; font-size: 22px; height:34px !important; text-align: center;">
												Transaction Details</td>
				                            </tr>

	                                        <tr>
	                                            <td class="es-m-p20b esd-container-frame" width="100%" align="left">
	                                                <table width="100%" cellspacing="0" cellpadding="0">
	                                                    <tbody>
	                                                        <tr>
	                                                            <td class="esd-block-text" align="left" style="font-size: 14px;">
	                                                                <p><strong>Date:</strong>{{ date('d/m/Y', strtotime($monthsale->created_at)) }}</p>
	                                                                <p><strong>Phone Number:</strong> <a href="tel:+96512345678">{{ $vendor->mobile_no}}</a></p>
	                                                                <p><strong>Email Id:</strong> <a href="mailto:{{ $vendor->email }}"> {{ $vendor->email }}</a></p>
	                                                            </td>
	                                                        </tr>
	                                                    </tbody>
	                                                </table>
	                                            </td>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </td>
							</tr>

							<tr>
                            <td class="esd-structure es-p10t es-p10b" style="background-color: #FFFFFF;" esd-general-paddings-checked="false" align="left">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                    	<tr>
              								<td class="esd-block-spacer es-p20t es-p20b" bgcolor="#ffffff" align="center">
                                      			<table width="540" height="100%" cellspacing="0" cellpadding="0" border="0">
                                           			<tbody>
                                                  		<tr>
                                            				<td style="border-bottom: 1px solid rgb(28, 133, 200); background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%; height: 1px; width: 100%; 	margin: 0px;"></td>
                                                    	</tr>
                                               		</tbody>
                                          		</table>
                                     		</td>
                                  		</tr>
                                        <tr>
                                            <td class="esd-container-frame" width="580" valign="top" align="center">
                                                <table width="540" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-block-text es-p15b" width="50%" align="left">
                                                                <table class="cke_show_border" width="100%" height="101" cellspacing="1" cellpadding="1" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><strong>Total Sales Amount: </strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Total Admin Commission: </strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><span style="font-size: 18px; line-height: 200%;"><strong>Total Amount Paid:</strong></span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>

                                                             <td class="esd-block-text es-p15b" width="43%" align="left">
                                                                <table class="cke_show_border" width="100%" height="101" cellspacing="1" cellpadding="1" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: left;">{{ number_format($monthsale->knet_payment + $monthsale->cod_payment, 2) }} KD</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="text-align: left;">{{ number_format($monthsale->total_comission_payment, 2) }} KD</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="text-align: left;"><span style="font-size: 18px; line-height: 200%;"><strong>{{ number_format($monthsale->paid_amount, 2) }}  KD</strong></span><br></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
						</tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td bgcolor="#f3f3f3" style="background: url(images/footer-bgbtm2.png) center center / cover no-repeat, #f3f3f3;">
            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                <tr>
                    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
                        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
                             <tr><td class="w3l-4h" height="120px" style="line-height: 150%;">
                             	Regards,<br>
                                <strong>I Can Save the world</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection