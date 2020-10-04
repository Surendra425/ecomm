<?php

namespace App\Console\Commands;

use App\Helpers\ApiHelper;
use App\Helpers\PDFHelper;
use App\Mail\OrderConfirmationMail;
use App\Mail\VendorOrderMail;
use App\Order;
use App\ProductCart;
use App\ProductStockHistory;
use App\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendOrderSuccessMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendOrderSuccessMail:send {orderId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command send order success mail from background';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        $orderId = $this->argument('orderId');
        $order = Order::find($orderId);
        
        ApiHelper::afterSuccessOrderMail($order);

        DB::commit();
    }
}
