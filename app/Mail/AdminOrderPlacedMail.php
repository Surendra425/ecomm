<?php

namespace App\Mail;

use App\Order;
use App\OrderAddress;
use App\OrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class AdminOrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $order_products;
    public $shipping_address;
    public $invoicePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $order = Order::find($order);
        $order_products = OrderProduct::select("order_products.*", "products.vendor_id", "products.product_slug", "products.product_title", "users.first_name", "users.last_name", "rating", "review_text","product_images.image_url","product_attr_combination.combination_title")
            ->join("products", "products.id", "order_products.product_id")
            ->join("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
            ->join("users", "users.id", "order_products.product_vendor_id")
            ->leftjoin("product_images","product_images.product_id","order_products.product_id")
            ->leftjoin("product_review", "product_review.product_id", "order_products.product_id")
            ->groupBy("order_products.id")
            ->where("order_id", $order->id)
            ->get();
        $shipping_address = OrderAddress::select("order_addresses.*", "city", "state", "country","users.first_name", "users.last_name")
            ->leftjoin("users", "users.id", "order_addresses.customer_id")
            ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
        // dd($order);die;
        $this->order = $order;
        $this->order_products = $order_products;
        $this->shipping_address = $shipping_address;
        $invoiceName =  $order->order_no . '.pdf';
        $this->invoicePath = public_path('doc/invoice/' . $invoiceName);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::debug('admin placed mail');
        return $this->subject('Received new order')
            ->attach($this->invoicePath)->markdown('mail.adminOrderPlaced');

    }
}
