@include('mail.mailHeader')
<tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
             Vendor Transcation Details
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             Dear {{$vendor['first_name'] . ' '. $vendor['last_name']}}
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 17px;">
             Your transcation is paid by I Can Save the world sucessfully.
             <br>
             Paid Amount : {{$vendor['amount']}} KD
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
@include('mail.mailFooter')