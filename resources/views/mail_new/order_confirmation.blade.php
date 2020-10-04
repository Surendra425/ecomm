@extends('mail_new.layout.index')
@section('header')
<tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
             <tr>
             	<td class="w3l-4h order_date" height="84px">Date: <span>{{ date('d/m/Y', strtotime($order->created_at)) }}</span></td>
             	<td class="w3l-4h order_id" height="84px">Order Id: <span>#{{ $order->order_no }}<span></td>
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
                                <td class="agile-main scale-center-both" style="font-weight: 700; color: #1c85c8; font-size: 21px; height:34px !important;">
								Your payment of {{ number_format((float)($order->grand_total), 2) }} KD for the below order has been made successfully.</td>
                            </tr>
                            <tr><td height="12" style="font-size: 1px;">&nbsp;</td></tr>
                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:justify;">
                                    Thank you for shopping with us on <span style="color:#1c85c8; font-weight: 700;">I Can Save the world !</span>
                                </td>
                            </tr>	
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
	<tbody>
		<tr>
			<td bgcolor="#f3f3f3" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
				<table width="620" cellspacing="0" cellpadding="0" align="center">
					<tbody>
						<tr>
							<td class="esd-container-frame" width="100%" valign="top" align="center" bgcolor="#ffffff">
								<table width="100%" cellspacing="0" cellpadding="0">
									<tbody>
										<tr>
											<td class="esd-block-text" align="center">
												<h2 style="color: rgb(25, 25, 25); padding:20px 0 0; font-weight:600;">Order Details<br></h2>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						@foreach($orderProducts as $product)
                        <tr>
							<td class="esd-structure es-p25t es-p5b es-p20r es-p20l" esd-general-paddings-checked="false" style="background-color: #FFFFFF;" bgcolor="#f8f8f8" align="left">
								<table class="es-left" cellspacing="0" cellpadding="0" align="left">
									<tbody>
                                          <tr>
                                              <td class="es-m-p20b esd-container-frame" width="270" align="left">
                                                  <table width="100%" cellspacing="0" cellpadding="0">
                                                      <tbody>
                                                          <tr>
                                                              <td class="esd-block-image" align="center">
                                                                  <a target="_blank"> <img class="adapt-img" src="{{ url('doc/product_image/'.(isset($product->image_url)? $product->image_url:'no-images.jpeg')) }}" alt="" width="150"> </a>
                                                              </td>
                                                          </tr>
                                                      </tbody>
                                                  </table>
                                              </td>
                                          </tr>
									</tbody>
								</table>
                                <table class="es-right" cellspacing="0" cellpadding="0" align="right">
                                    <tbody>
                                        <tr>
                                            <td class="esd-container-frame" width="270" align="left">
                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-block-text" align="left">
                                                                <p><span style="font-size:14px;"><strong style="line-height: 150%;">{{ $product->product_title }} {{ !empty($product->combination_title) ? '('.$product->combination_title.')' : '' }}</strong></span></p>
                                                                
                                                                <p style="font-size:13px;color:grey;">Sold by :<a href="{{ url($product->store_slug) }}" target="_blank"><span style="color: #1c85c8;"> {{ $product->store_name }} ({{ $product->city }}, {{ $product->country }})</span></a></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="esd-block-text es-p20t" align="left">
                                                                <p><span style="font-size:15px;line-height: 150%;color:grey;">Item Price: {{ number_format($product->sub_total, 2) }}</span></p>
                                                                <p><span style="font-size:15px;color:grey;">Qty:{{ $product->quantity }}</span></p>
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
						@endforeach
                        <tr>
                            <td class="esd-structure es-p10t es-p10b" style="background-color: #FFFFFF;" esd-general-paddings-checked="false" align="left">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                    	<tr>
              								<td class="esd-block-spacer es-p20t es-p20b sp_line" bgcolor="#ffffff" align="center">
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
                                                            <td class="esd-block-text es-p15b left_area" width="50%" align="left">
                                                                <table class="cke_show_border" width="100%" height="101" cellspacing="1" cellpadding="1" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><strong>Subtotal:</strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Shipping:</strong></td>
                                                                        </tr>
                                                                        @if(!empty($order->discount_amount))
                                                                        
                                                                        <tr>
                                                                            <td><strong>Promocode:</strong></td>
                                                                        </tr>
                                                                        @endif
                                                                        
                                                                        <tr>
                                                                            <td><span style="font-size: 18px; line-height: 200%;"><strong>Order Total:</strong></span></td>
                                                                        </tr> 
                                                                    </tbody>
                                                                </table>
                                                            </td>

                                                             <td class="esd-block-text es-p15b right_area" width="43%" align="left">
                                                                <table class="cke_show_border" width="100%" height="101" cellspacing="1" cellpadding="1" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align: left;">{{ number_format($order->sub_total, 2) }} KD</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="text-align: left;">{{ number_format($order->shipping_total, 2) }} KD</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="text-align: left;"><span style="font-size: 18px; line-height: 200%;"><strong>{{ number_format($order->grand_total, 2) }} KD</strong></span><br></td>
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
					</tbody>
				</table>
			</td>
		</tr>
    </tbody>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td bgcolor="#f3f3f3" style="background: url(images/footer-bgbtm2.png) center center / cover no-repeat, #f3f3f3;">
            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                <tr>
                    <td bgcolor="#FFFFFF" style="border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
                        	    <tr>
                            <td class="esd-structure es-p15t es-p10b es-p10r es-p10l" style="background-color: #ebebeb;" esd-general-paddings-checked="false" bgcolor="#ebebeb" align="left">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td class="esd-container-frame" width="580" valign="top" align="center">
                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-block-text" align="center">
                                                                <h2 style="color: rgb(25, 25, 25); font-weight:700">Shipping Information</h2>
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
                            <td class="esd-structure es-p30b es-p20r es-p20l w3-gr esd-block-text" esd-general-paddings-checked="false" style="background-color: #ebebeb;" align="center" height="84">
                                <table class="es-left" cellspacing="0" cellpadding="0" align="left">
                                    <tbody>
                                        <tr>
                                            <td class="es-m-p20b esd-container-frame" width="100%" align="left">
                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="esd-block-text" align="left">
                                                                <p><strong>Name:</strong> {{ $shippingAddress->first_name.' '.$shippingAddress->last_name }}</p>
                                                                <p><strong>Phone:</strong> <a href="tel:{{ $shippingAddress->mobile_no }}">{{ $shippingAddress->mobile_no }}</a></p>
                                                                <p><strong>Address:</strong>{{ $shippingAddress->building.','.$shippingAddress->apartment.','.$shippingAddress->avenue.','.$shippingAddress->city.','.$shippingAddress->country }}</p>
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
@endsection