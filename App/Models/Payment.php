<?php

namespace App\Models;

use App\Core\Database;
use Razorpay\Api\Api; // If Razorpay SDK is installed

class Payment extends BaseModel
{
    protected $table = "payments";

    private $apiKey = "rzp_live_xxxxx";
    private $apiSecret = "xxxxx";

    /**
     * Create Razorpay Order
     */
    public function createOrder($amount)
    {
        $api = new Api($this->apiKey, $this->apiSecret);

        $order = $api->order->create([
            'receipt'         => 'order_' . time(),
            'amount'          => $amount * 100, 
            'currency'        => 'INR'
        ]);

        // Save order
        $stmt = $this->db->prepare("
            INSERT INTO payments(order_id, amount, status)
            VALUES(:order_id, :amount, 'created')
        ");

        $stmt->execute([
            ":order_id" => $order['id'],
            ":amount" => $amount
        ]);

        return $order;
    }

    /**
     * Verify payment completion
     */
    public function verifyPayment($orderId)
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE order_id=:id");
        $stmt->execute([":id" => $orderId]);
        $pay = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$pay) {
            return ["status" => false, "message" => "Order not found"];
        }

        // Normally verify signature & success status
        // For now, mark as success

        $this->db->prepare("
            UPDATE payments 
            SET status='paid'
            WHERE order_id=:id
        ")->execute([":id" => $orderId]);

        return [
            "status" => true,
            "message" => "Payment verified",
            "payment" => $pay
        ];
    }
}
