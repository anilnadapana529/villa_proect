<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Payment;
use App\Core\Auth;

class PaymentController
{
    /**
     * Create Razorpay order
     * Endpoint: POST /create-order
     */
    public function createOrder()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["amount"])) {
            return Response::json([
                "status" => false,
                "message" => "Amount required"
            ]);
        }

        $payment = new Payment();
        $order = $payment->createOrder($data["amount"]);

        return Response::json([
            "status" => true,
            "order" => $order
        ]);
    }

    /**
     * Verify payment
     * Endpoint: POST /verify-payment
     */
    public function verifyPayment()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["order_id"])) {
            return Response::json([
                "status" => false,
                "message" => "Order ID required"
            ]);
        }

        $payment = new Payment();
        $result = $payment->verifyPayment($data["order_id"]);

        return Response::json($result);
    }
}
