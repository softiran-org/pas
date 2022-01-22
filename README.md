# pas
sepehrpay پرداخت الکترونیک سپهر
# **sepehrpay payment for Laravel Framework**
install it:

```shell
composer require softiran-org/pas
```

laravel service provider should register automatically.

# **Use it**
**request new payment:**
```php
<?php

use Pas\Pas;

require_once 'config/pas.php';

class RestTest extends \PHPUnit\Framework\TestCase
{
    private $pas;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->pas = new Pas('XXXXXXXX');
        parent::__construct($name, $data, $dataName);
    }

    public function testRequest()
    {
		$payload = array(
		'amount'=>1000,//your amount
		'callback_url'=>'http://www.site.com',//your callback_url
		'invoiceID'=>12345,//your invoiceID
		);
        $res= $this->pas->request($payload);
		if($res['Status'] == '0')
		{
			//TODO: store $res['Accesstoken'] in DB
			
			$this->pas->redirect($res['Accesstoken']);
			
		}else
		{
			
			echo $res['Status'];//error description
		}
    }
	public function testVerify()
	{
		$digitalreceipt = $_GET['digitalreceipt'];
		$res = $this->pas->verify($digitalreceipt);
		if (strtoupper($res['Status']) == 'OK') 
		{
			if(floatval($result['ReturnId']) == floatval(1000))
			{
				return true;
			}
		}else
		{
			echo $_GET['respmsg'];//error description
		}
	}
}
```
