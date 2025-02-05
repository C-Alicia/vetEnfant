<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/payment", name="payment_form")
     */
    public function showPaymentForm(): Response
    {
        return $this->render('payment/payment_form.html.twig', [
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
        ]);
    }

   // Add this method inside PaymentController class
public function handlePayment(Request $request): Response
{
    Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

    $token = $request->request->get('stripeToken');

    try {
        // Use Stripe's library to make requests...
        $charge = \Stripe\Charge::create([
            'amount' => 999, // Amount in cents
            'currency' => 'usd',
            'description' => 'Example charge',
            'source' => $token,
        ]);

        // Check charge status
        if($charge->status == 'succeeded') {
            // Payment was successful
            return new Response('Payment successful.');
        } else {
            // Payment failed
            return new Response('Payment failed.');
        }
    } catch(\Exception $e) {
        // Catch any errors for debugging
        return new Response($e->getMessage());
    }
}
}
