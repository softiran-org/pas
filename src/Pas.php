<?php

namespace Pas;

class Pas
{
    /**
     * Pas constructor.
     * @param string $merchantID
     */
    function __construct(string $merchantID) 
	{
        $this->merchantID = $merchantID;
    }

    public string $merchantID;

    /**
     * Request for new payment
     * to get "Accesstoken" if no error occur.
     *
     *
     * @param array $payload
     *
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
			return $res;
		}
		return false;
    }

    /**
     * Verify payment success.
     *
     *
     * @param string $digitalreceipt
     *
     * @return array
     */
    function verify(string $digitalreceipt)
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
