@include('mail.mailHeader')
<tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              {{ $emailData->name }}
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             Dear {{ $emailData->full_name }}
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             {!! $emailData->email_content !!}
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
@include('mail.mailFooter')
