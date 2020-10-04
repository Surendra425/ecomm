@include('mail.mailHeader')
<tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              WELCOME TO I Can Save the world.COM
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             Dear Subscriber
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 17px;">
             Thank you for subscribing <a href="{{url('/')}}" target="_blank">I Can Save the world.com</a>.
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
@include('mail.mailFooter')
