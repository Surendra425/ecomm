<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Order;
use App\OrderProduct;
use App\OrderAddress;
class VendorOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $vendor;
    public $order_products;
    public $shipping_address;
    public $invoicePath;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$vendor)
    {
        
        $order_products = OrderProduct::selectRaw("order_products.*,products.vendor_id,products.product_slug,products.product_title,rating,review_text,product_images.image_url,product_attr_combination.combination_title")
                ->join("products", "products.id", "order_products.product_id")
                ->join("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
                ->leftjoin("product_images","product_images.product_id","order_products.product_id")
                ->leftjoin("product_review", "product_review.product_id", "order_products.product_id")
                ->groupBy("order_products.id")
                ->where("order_id", $order->id)
                ->where("product_vendor_id", $vendor->id)
                ->get();
                
        $total = $order_products->pluck('sub_total')->toArray();
        //$shipping_charges = $order_products->pluck('shipping_charges')->toArray();
        $order->sub_total = $subTotal = array_sum($total);  
        //$order->shippingTotal = $hipping = $order_products[0];
        //$order->grandTotal = $subTotal + $hipping; 
        $shipping_address = OrderAddress::select("order_addresses.*", "city", "state", "country","users.first_name", "users.last_name")
        ->join("users", "users.id", "order_addresses.customer_id")
        ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
        $this->vendor = $vendor;
        $this->order = $order;
        $this->order_products = $order_products;
        $this->shipping_address = $shipping_address;
        $invoiceName =  $order->order_no . '_' . $vendor->id . '.pdf';
        $this->invoicePath = public_path('doc/invoice/' . $invoiceName);
        $order->shipping_total = array_sum(array_column($order_products->toArray(), 'shipping_charges'));
        $order->order_total = array_sum(array_column($order_products->toArray(), 'grand_total')) + $order->shipping_total;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::debug('vendor mail send');
        return $this->markdown('mail.vendorOrderMail')
        ->attach($this->invoicePath);

    }
}
