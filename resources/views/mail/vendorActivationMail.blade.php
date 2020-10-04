@include('mail.mailHeader')
<tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
             Vendor Account Activation
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             Dear {{$user['first_name'] . ' '. $user['last_name']}}
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 17px;">
             Welcome to the site {{$user['first_name'] . ' '. $user['last_name']}}
             Your registered email-id is {{$user['email']}}.
             Your account is Activate.
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
@include('mail.mailFooter')