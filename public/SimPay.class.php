<?php

class SimPay
{
    protected $auth = array();
    protected $api = '1';
    
    protected $response = array();
    protected $call = array();
    
    public function __construct($key = '', $secret = '', $api = false)
    {
        if(isset($api) and !empty($api)) {
            $this->api = $api;
        } 
        
        $this->auth = array(
            "auth" => array(
                "key" => $key,
                "secret" => $secret, 
            )
        );
    }
    
    public function url($value, $params = array())
    {
        $data = json_encode(array('params'=>array_merge($this->auth, $params)));
        $this->call = $this->request($data, "https://simpay.pl/api/".$this->api."/".$value);
        return $this->call;
    }
    
    public function getStatus($params) 
    {
        $this->response = $this->url('status', $params);
        return $this->response;
    }
    
    public function check() 
    {
        if(isset($this->response) and is_array($this->response)) {
            if(isset($this->response['respond']['status']) and $this->response['respond']['status']=='OK') {
                return true;                
            } else if(isset($this->response['error']) and is_array($this->response['error'])) {
                return false;
            } 
        } else {
            throw new Exception('Brak informacji na temat ostatniego zapytania');
        }
    }
    
    public function error() 
    {
        if(isset($this->response['error']) and is_array($this->response['error'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function showError() 
    {
        if(isset($this->response['error']) and is_array($this->response['error'])) {
            return $this->response['error'];
        } else {
            throw new Exception('Brak bledu do pokazania');
        }        
    }
    
    private function request($data, $url)
    {       
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // developer only
        $call = curl_exec($curl);
        $response = json_decode($call, true);
        $error = curl_errno($curl);
        curl_close($curl);
        
        if ($error > 0) {
            throw new RuntimeException('CURL ERROR Code:'.$error);
        }
        return $response;
    }
    
    public function response() {
        return $this->response;
    }
}