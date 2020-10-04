@include('mail.mailHeader')
<tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
             Reset Password
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             hello
            </td>
          </tr>
          
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 17px;">
               You are receiving this email because we received a password reset request for your account.
            </td>
          </tr>
          <tr>
             <br>
        	 <td class="esd-block-button shop_btn" align="center" style="padding:15px 0;"> <span class="es-button-border" style="border-radius: 20px; border-style: solid; border-width: 0px;"> <a href="{{ $actionUrl }}" class="es-button" target="_blank" style="padding:5px 20px; border-radius: 20px; font-weight: 400;font-weight: normal; font-size: 18px; border-width: 10px 35px; background: rgb(28, 133, 200) none repeat scroll 0% 0%; color: rgb(255, 255, 255);">Reset Password</a> </span> </td>
          </tr>
          <tr>
                <br><br>
                <td class="agile-main scale-center-both" style="color: #000; font-size: 10px; text-align:left; height:34px !important;">If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <br>
                <a target="_blank" href="{{ $actionUrl }}">{{ $actionUrl }}</a>
            </td>
            </tr>
          <tr>
            <br>
            <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">If you did not request a password reset, no further action is required.</td>
        </tr>
        </table>
      </center>
    </td>
  </tr>
@include('mail.mailFooter')