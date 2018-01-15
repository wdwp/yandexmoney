<?php
/**
 * Yandex money forms an buttons api
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Yandexmoney
 * @author    Yuri Haperski <wdwp@bk.ru>
 * @copyright 2018 wdwp (https://madesimple.su)
 * @license   http://opensource.org/licenses/MIT MIT License
 * @link      http://github.com/wdwp/yandexmoney
 */
namespace wdwp\yandexmoney;

use Exception;

/**
 * Class Payment
 *
 * @category  PHP
 * @package   Yandexmoney
 * @author    Yuri Haperski <wdwp@bk.ru>
 * @copyright 2018 wdwp (https://madesimple.su)
 * @license   MIT https://madesimple.su
 * @link      https://madesimple.su
 */
class Payment
{

    private $__url = 'https://money.yandex.ru/quickpay/confirm.xml';
    private $__params;

    /**
     * Class constructor
     *
     * @param string $receiver purse number
     * @param string $targets  invoice description
     * @param float  $sum      invoice sum
     * @param string $form     form type (shop, small, donate)
     * @param string $type     payment type (AC - bank card, PC - Yandex money,
     *                         MC - mobile payment)
     */
    public function __construct(
        $receiver, $targets, $sum, $form = 'shop', $type = 'AC'
    ) {
        try {
            if (!preg_match('/^[0-9]{12,16}$/', $receiver)) {
                throw new Exception('Purse number is required.');
            }

            if (empty($targets) || strlen($targets) > 150) {
                throw new Exception(
                    'Invoice description is required and cannot be empty.'
                );
            }

            if (!is_numeric($sum) || $sum <= 0) {
                throw new Exception(
                    'Invoice sum is required and cannot be less or equals zero.'
                );
            }

            if (!in_array($form, ['shop', 'small', 'donate'])) {
                throw new Exception(
                    'Possible values for the form type: shop, small, donate.'
                );
            }

            if (!in_array($type, ['AC', 'PC', 'MC'])) {
                throw new Exception(
                    'Possible values for the payment type: AC - bank card,
                     PC - Yandex money, MC - mobile payment.'
                );
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        $this->__params = [
            'receiver' => $receiver,
            'targets' => $targets,
            'sum' => number_format($sum, 2, '.', ''),
            'quickpay-form' => $form,
            'paymentType' => $type,
            'formcomment' => '',
            'short-dest' => '',
            'label' => '',
            'comment' => '',
            'successURL' => '',
            'need-fio' => false,
            'need-email' => false,
            'need-phone' => false,
            'need-address' => false,
        ];
    }
    /**
     * Adds payment name for payment history (optional).
     *
     * @param string $str payment name
     *
     * @return Payment
     */
    public function setFormcomment($str)
    {
        $this->__params['formcomment'] = (string) $str;
        return $this;
    }
    /**
     * Adds payment name for confirmation page (optional).
     *
     * @param string $str payment name
     *
     * @return Payment
     */
    public function setDest($str)
    {
        $this->__params['short-dest'] = (string) $str;
        return $this;
    }
    /**
     * Adds invoice number (optional).
     *
     * @param string $str invoice or order number
     *
     * @return Payment
     */
    public function setLabel($str)
    {
        $this->__params['label'] = (string) $str;
        return $this;
    }
    /**
     * Adds user comment (optional).
     *
     * @param string $str user comment
     *
     * @return Payment
     */
    public function setComment($str)
    {
        $this->__params['comment'] = (string) $str;
        return $this;
    }
    /**
     * Adds success url (optional).
     *
     * @param string $url success url
     *
     * @return Payment
     */
    public function setSuccessUrl($url)
    {
        $this->__params['successURL'] = (string) $url;
        return $this;
    }
    /**
     * Adds username request (optional).
     *
     * @param bool $bool true or false
     *
     * @return Payment
     */
    public function needFio($bool)
    {
        $this->__params['need-fio'] = (bool) $bool;
        return $this;
    }
    /**
     * Adds user email request (optional).
     *
     * @param bool $bool true or false
     *
     * @return Payment
     */
    public function needEmail($bool)
    {
        $this->__params['need-email'] = (bool) $bool;
        return $this;
    }
    /**
     * Adds user phone number request (optional).
     *
     * @param bool $bool true or false
     *
     * @return Payment
     */
    public function needPhone($bool)
    {
        $this->__params['need-phone'] = (bool) $bool;
        return $this;
    }
    /**
     * Adds user address request (optional).
     *
     * @param bool $bool true or false
     *
     * @return Payment
     */
    public function needAddress($bool)
    {
        $this->__params['need-address'] = (bool) $bool;
        return $this;
    }
    /**
     * Create payment url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->__url . '?' . http_build_query($this->__params);
    }
    /**
     * Redirect to payment url.
     *
     * @return null
     */
    public function send()
    {
        header('Location: ' . $this->getUrl());
    }
    /**
     * Create payment form.
     *
     * @param string $text  button text
     * @param string $class button class name
     *
     * @return string
     */
    public function getForm($text = 'Оплатить', $class = 'button')
    {
        return '<form method="POST" action="' . $this->__url . '">
             <input type="hidden" name="receiver" value="' . $this->__params['receiver'] . '">
             <input type="hidden" name="targets" value="' . $this->__params['targets'] . '">
             <input type="hidden" name="sum" value="' . $this->__params['sum'] . '">
             <input type="hidden" name="quickpay-form" value="' . $this->__params['quickpay-form'] . '">
             <input type="hidden" name="paymentType" value="' . $this->__params['paymentType'] . '">
             <input type="hidden" name="formcomment" value="' . $this->__params['formcomment'] . '">
             <input type="hidden" name="short-dest" value="' . $this->__params['short-dest'] . '">
             <input type="hidden" name="label" value="' . $this->__params['label'] . '">
             <input type="hidden" name="comment" value="' . $this->__params['comment'] . '">
             <input type="hidden" name="successURL" value="' . $this->__params['successURL'] . '">
             <input type="hidden" name="need-fio" value="' . $this->__params['need-fio'] . '">
             <input type="hidden" name="need-email" value="' . $this->__params['need-email'] . '">
             <input type="hidden" name="need-phone" value="' . $this->__params['need-phone'] . '">
             <input type="hidden" name="need-address" value="' . $this->__params['need-address'] . '">
             <input type="submit" class="' . $class . '" value="' . $text . '">
             </form>';
    }
    /**
     * Validate payment.
     *
     * @param array  $data post data
     * @param string $key  secret word
     *
     * @return mixed
     */
    public static function validate($data, $key)
    {
        $str = $data['notification_type'] . '&' . $data['operation_id'] . '&' . $data['amount'] . '&' . $data['currency'] . '&' . $data['datetime'] . '&' . $data['sender'] . '&' . $data['codepro'] . '&' . $key . '&' . $data['label'];
        $hash = hash("sha1", $str);

        if ($data["sha1_hash"] == $hash) {
            return $data;
        } else {
            return false;
        }
    }
}
