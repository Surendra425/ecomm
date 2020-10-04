@include('mail.mailHeader')
<tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
             Welcome to I Can Save the world !
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 18px;">
             Dear {{$user['first_name'] . ' '. $user['last_name']}}
            </td>
          </tr>
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 17px;">
             Check out our Help & Customer Service page to learn about the services and features which will let you get the most out of your I Can Save the world.com experience.
            </td>
          </tr>
          
          <tr>
            <td class="free-text" style="color: #000000; text-align: left; font-size: 17px;">
             
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
@include('mail.mailFooter')


