# yandexmoney


## Installation

Install this package through Composer. To your `composer.json` file, add:
```js
{
    "require": {
        "wdwp/yandexmoney": "dev-master"
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

$form = $payment->setFormcomment('Payment for same goods')
    ->setDest('Payment for same goods')
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
```
```php
// get payment url
$url = $payment->getUrl();
```

Check payment result:
```php
// somewere in result url handler...
...


if ($payment->validateResult($_GET) {
    $order = Orders::find($payment->getInvoiceId());

    if ($payment->getSum() == $order->sum) {

    }

    // send answer
    echo $payment->getSuccessAnswer(); // "OK1254487\n"
}
...
```

Check payment on Success page:
```php
...
use wdwp\yandexmoney\Payment;

$result = Payment::validate($_POST, 'RX29rXHxOsR0exsBs6Hvi'); //secret word

if ($result) {
    // payment is valid
    $order = Orders::find($result['label']);      
   
}
...
```
