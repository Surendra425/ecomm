@php

        $json = [
            'session' => [
                'id' => $sessionId
            ],
            'merchant' => '0057',
            'interaction' =>  [
                'merchant' => [
                    'name' => !empty($locale) ? trans('api.static_content.project_delivery') : 'Project Delivery'
                ],
                'displayControl' => [
                    'billingAddress' => 'HIDE'
                ],
                'locale' => $locale
            ],
            'order' => [
                'amount' => $order['amount'],
                'currency' => $order['currency'],
                'description' => 'ORDER FROM I Can Save the world',
                'id' => $order['id']
            ],
        ];

        $notificationRoute = route('CardPaymentNotification', ['orderNumber' => $order['id'],
                                                               'isMobile' => $isMobile,
                                                              ]);
@endphp
<html>
    <head>
        <style type="text/css">
        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            background-color: #000;
            height:100%;
        }
       
        
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        </style>
        <script src="https://ap-gateway.mastercard.com/checkout/version/49/checkout.js"
                data-error="{{ $notificationRoute }}"
                data-cancel="{{ $notificationRoute }}"
                data-complete="{{ $notificationRoute }}">
        </script>
        <script>
        
            var configure = JSON.parse('<?php echo json_encode($json);?>');

            Checkout.configure(configure);

            Checkout.showLightbox();
            
        </script>
    </head>
    <body>
       <div id="loader" class="modal-backdrop fade out">
          <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><img class="center fade in" src="{{ url('assets/loader.gif')}}" width="200px">
       </div>
       
        <!-- <input type="button" value="Pay with Lightbox" onclick="Checkout.showLightbox();" />
        <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" /> -->
        ...
        
        <script type="text/javascript">

        setTimeout(function(){ document.getElementById("loader").innerHTML = ""; }, 10000);

        </script>
    </body>
</html>