# yandexmoney

## Installation

Install this package through Composer. To your `composer.json` file, add:
```js
{
    "require": {
        "wdwp/yandexmoney": "^1.0"
    }
}
```

## Examples

Create payment:
```php
use wdwp\yandexmoney\Payment;

$payment = new Payment(
    '4100163332366', 'Payment', 100.0, 'shop', 'AC'
);

$form = $payment->setFormcomment('Shop name')
    ->setDest('Payment for some goods')
    ->setLabel($order->id)
    ->setComment($order->comment)
    ->setSuccessUrl('http://yoursite.com/success.php')
    ->needFio(true)
    ->needEmail(true)
    ->needPhone(true)
    ->needAddress(true)
    ->getForm();

echo $form;

// redirect to payment url
$payment->send();

// get payment url
$url = $payment->getUrl();
```
Check payment result:
```php
// somewere in result url handler...
...
use wdwp\yandexmoney\Payment;

$result = Payment::validate($_POST, 'RX29rXHxOsR0exsBs6Hvi'); //secret word

if ($result) {
    // payment is valid
    $order = Orders::find($result['label']);      
   
}
...
```
Success page:
```php
...
echo "Thank you for your payment!";
...
```
