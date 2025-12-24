require 'vendor/autoload.php'; // or include 'lib/Stripe.php' if manual

\Stripe\Stripe::setApiKey('sk_test_your_secret_key');

$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => 'myr',
      'product_data' => [
        'name' => 'Sate Famili Order #'.$_GET['order_id'],
      ],
      'unit_amount' => $_GET['amount'] * 100, // convert to sen
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'https://chocolate-dove-926162.hostinger.com/order_status.php?order_id='.$_GET['order_id'],
  'cancel_url' => 'https://chocolate-dove-926162.hostinger.com/menu.php',
]);

header("Location: " . $session->url);
