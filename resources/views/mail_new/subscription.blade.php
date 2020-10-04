@extends('mail_new.layout.index')
@section('header')
<tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
             <tr><td class="w3l-4h user_name" height="84px" style="color: #000;">Hi,</td></tr>
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
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">We are so glad that you decided to try out I Can Save the world. Experience the fabulous shopping adventure with us.</td>
                            </tr>
                            <tr><td height="25" style="font-size: 1px;">&nbsp;</td></tr>
                             <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 700; color: #1c85c8; font-size: 22px; line-height: 28px; text-align:center;">Welcome to I Can Save the world<br>
                                <img src="{{ url('assets/frontend/mail/product_banner.jpg') }}" alt="" title="" style="margin:10px 0;" class="product_img">
                                </td>
                            </tr>
                            <tr>
                            	<td class="esd-block-button shop_btn" align="center" style="padding:15px 0;"> <span class="es-button-border" style="border-radius: 20px; border-style: solid; border-width: 0px;"> <a href="{{ url('/') }}" class="es-button" target="_blank" style="padding:5px 20px; border-radius: 20px; font-weight: 400;font-weight: normal; font-size: 18px; border-width: 10px 35px; background: rgb(28, 133, 200) none repeat scroll 0% 0%; color: rgb(255, 255, 255);">Shop Now</a> </span> </td>
                            </tr>
                             <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">Thanks for subscribed! Now you'll be the first to hear about our new products, exclusive offers and more. Stay tuned !
                                </td>
                            </tr>
                            <!-- <tr><td class="w3l-p2 scale-center-both" style="padding:10px 0 0; font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:center;">Stay tuned !</td></tr> -->
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection