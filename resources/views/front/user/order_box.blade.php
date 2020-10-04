@foreach($orders as $order)
    <div class="order-row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group m-form__group">
                        <label class="control-label">Order No : {{ $order->order_no }}</label>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="control-label">Order Datetime : {{ date("d/m/Y H:i:s",strtotime($order->created_at)) }}</label>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="control-label">Grand Total : {{ (float)($order->grand_total) }} KD</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group m-form__group">
                        <label class="control-label">Payment Type : {{ $order->payment_type }}</label>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="control-label">Payment Status : {{ $order->payment_status }}</label>
                    </div>
                    @if(!empty($order->knet_payment_id))
                        <div class="form-group m-form__group">
                            <label class="control-label">Knet Payment ID: {{ $order->knet_payment_id }}</label>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group m-form__group text-right">
                        <a class="btn btn-xs btn-warning" href="{{ url(route('myOrderDetail',["orderNo"=>$order->order_no])) }}" >View Detail</a>
                    </div>
                    @if($order->payment_status != 'Completed')
                        <div class="form-group m-form__group text-right">
                            <a class="btn btn-xs btn-success" href="{{ url(route('knetPay',["orderNo"=>$order->id])) }}" >Checkout</a>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endforeach