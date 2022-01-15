<?php

namespace Pas;

use GuzzleHttp\Exception\RequestException;

class Pas
{
    /**
     * Pas constructor.
     * @param string $merchantID
     * @param bool $laravel
     */
    function __construct(string $merchantID, bool $laravel = false) 
	{
        $this->merchantID = $merchantID;
        $this->laravel = $laravel;
    }

    public string $merchantID;
    public bool $laravel;

    /**
     * Request for new payment
     * to get "Authority" if no error occur.
     *
     * @see http://bit.ly/3sVkMU9
     *
     * @param array $payload
     *
     * @throws RequestException
     * @return array
     */
    function request(array $payload)
    {
		
		$payload=array('cms'=>'laravel','cms_ver'=>'2');
		$payload=json_encode($payload);
		$params ='terminalID='.$this->merchantID.'&Amount='.$payload['amount'].'&callbackURL='.urlencode($payload['callback_url']).'&invoiceID='.$payload['invoiceID'].'&Payload='.urlencode($payload);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://sepehr.shaparak.ir:8081/V1/PeymentApi/GetToken');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);
		if($res)
		{
			$res = json_decode($res,true);
			if($res['Status'] == '0')
			{
				return $res;
			}else
			{
				
				return $res['Status'];
			}
		}
    }

    /**
     * Verify payment success.
     *
     * @see http://bit.ly/3a75K54
     *
     * @param array $payload
     * @param string $digitalreceipt
     *
     * @throws RequestException
     * @return array
     */
    function verify(array $payload,string $digitalreceipt)
    {
	
        $params ='digitalreceipt='.$digitalreceipt.'&Tid='.$this->merchantID;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://sepehr.shaparak.ir:8081/V1/PeymentApi/Advice');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);
		return	$result = json_decode($res,true);
				
    }


    /**
     * Redirect to payment page.
     *
     * @param string $Accesstoken
     *
     * @return mixed
     */
    function redirect(string $Accesstoken)
    {
        echo '<form id="paymentUTLfrm" action="https://sepehr.shaparak.ir:8080" method="POST">
			<input type="hidden" id="TerminalID" name="TerminalID" value="'.$this->merchantID.'">
			<input type="hidden" id="token" name="token" value="'.$Accesstoken.'">
			<input type="hidden" id="getMethod" name="getMethod" value="1">
			</form><script>
			function submit2() {
			document.getElementById("paymentUTLfrm").submit();
			}
			window.onload=submit2; </script>';
        exit;
    }
}
