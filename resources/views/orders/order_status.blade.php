<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">
</head>
<style>
.btn-I Can Save the world
{
    color: #fff !important;
    background-color: #639a4e;
    border-color: #639a4e;
    
}
.btn-shopzz:hover
{
    color: #fff;
}
</style>
<body>
<div class="text-xs-center">
  <br><br><br>
  @php
   if($userLang != "ar")
   {
       $browseProduct = 'Browse Products';
       $imageUrl = $order->payment_status=='Completed' ?  url('assets/frontend/images/thankyou_graphic.png') : url('assets/frontend/images/failed_graphic.png');
   }
   else
   {
       $browseProduct = 'تصفح المنتجات';
       $imageUrl = $order->payment_status=='Completed' ?  url('assets/frontend/images/thank_you_arabic.png') : url('assets/frontend/images/failed_arabic_page.png');
   }

  @endphp
  <img class="text-xs-center" alt="" style="margin-top:18%;" src="{{$imageUrl}}" height="50%">
  <br>

  @if($order->payment_status=='Completed')
   <a id="success" name="success" href="#" onClick="btnClick();" class="btn btn-shopzz" style="clear:both;margin-top:40px;padding:10px 10px;font-size:20px;font-weight:bold;">{{$browseProduct}}</a>
  @else
   <a id="failed" name="failed" href="#" onClick="btnFailedClick();" class="btn btn-shopzz" style="clear:both;margin-top:40px;padding:10px 10px;font-size:20px;font-weight:bold;">{{$browseProduct}}</a>
  @endif
</div>
<div class="text-xs-center">
</div>  

<script type="text/javascript" src="{{ url('assets/frontend/js/jquery-3.2.1.js') }}"></script>
<script type="text/javascript">


function btnClick()
{
	window.webkit.messageHandlers.success.postMessage('Success');	
}

function btnFailedClick()
{
	window.webkit.messageHandlers.failed.postMessage('Failed');	
}




</script>
</body>

</html>