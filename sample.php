<?
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
class Rayvarz {
    
    // Properties
    public $baseUrl = 'http://XX.XX.XX.XX/rayweb-internet';
    public $params;
    public $method = 'POST';
    public $result;
    public $headerResult;
    public $entity;
    public $header;
    public $token;
    
    // Methods auth
     function auth() {
            $this->entity  = '/RayvarzApi/Core/Sso/Authenticate';
            $this->params  = '["USER_NAME","PASSWORD_HASH"]';
            $this->header  = ['Content-Type: application/json'];
            $this->method  = 'POST';
            $this->request();

    }

    // Methods addUser
    function addUser(){
        $this->entity  = '/RayvarzApi/Sales/Customer';
        $this->header  = [
            'access_token: '.$this->token,
            'BranchId: 3',
            'Content-Type: application/json'
        ];
        $this->method  = 'POST';
        $this->request();
    }

    
    // Methods addUser
    function addProforma(){
        $this->entity  = '/RayvarzApi/Sales/Proforma';
        
        $this->header  = [
            'access_token: '.$this->token,
            'BranchId: 1',
            'FiscalYearId:1400',//req
            'Content-Type: application/json'
        ];
        $this->method  = 'POST';
        $this->request();
        $rayvarz->result['data'] = json_decode($rayvarz->result['data'],True);
    }

    // Methods SalesFiscalYear
    function salesFiscalYear(){
        $this->entity  = '/RayvarzApi/Sales/SalesFiscalYear/List';
        
        $this->header  = [
            'access_token: '.$this->token,
            'Content-Type: application/json'
        ];
        $this->method  = 'POST';
        $this->request();
        $rayvarz->result['data'] = json_decode($rayvarz->result['data'],True);
    }


    // Methods request
     function request() {
        $curl = curl_init();
        $data = [
            CURLOPT_URL => $this->baseUrl.$this->entity,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->params,
            CURLOPT_HTTPHEADER => $this->header,
        ];

        curl_setopt_array($curl,$data);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $header = $this->get_headers_from_curl_response($response);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);
        curl_close($curl);
         $this->headerResult = $header;
         $this->result['data']= $body;
         $this->result['hCode' ] = $this->headerResult['http_code'];
         if($this->headerResult['http_code']=='HTTP/1.1 200 OK'){
                 $this->result['status'] = true;
                 $this->token  = rtrim(ltrim($this->result['data'],'"'),'"');
         }else{
                 $this->result['status'] = false;
                 //$this->token  = null;
         }
    }

    // Methods get_headers_from_curl_response
    function get_headers_from_curl_response($response)
    {
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0)
                $headers['http_code'] = $line;
            else
            {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        return $headers;
    }

  }


 $rayvarz = new Rayvarz();  
 $rayvarz->auth();


//---------------
$rayvarz->params ='{
    "CustomerId": "----",
    "Name": "----",
    "NameEn": "",
    "SalesTypeId": "1",
    "RegionId": "1",
    "FirstContactDate": "",
    "StatusId": "1",
    "CustomerGroupId": "1",
    "CenterId": "32000000",
    "HasDiscount": "",
    "DiscountTypeId": "",
    "BuyerId": "3000000",
    "CountyId": "1",
    "Address1": "تست تست",
    "Address2": "",
    "ZipCode": "1234567890",
    "PostBoxNo": "1234567890",
    "Tel1": "----",
    "Tel2": "",
    "Fax": "",
    "Email": "----",
    "SalesPathId": null,
    "Distance": null,
    "RemainingCredit": null,
    "MinimumPurchase": null,
    "MaximumPurchase": null,
    "CreditDiscountAmount": "0",
    "CreditDiscountDate": "0",
    "ShippingAgentId": null,
    "ManagerName": "----",
    "TaxCityId": null,
    "TaxMainId": null,
    "TaxSubId": null,
    "FirstName": "----",
    "LastName": "----",
    "FatherName": "----",
    "BirthCertificateNo": "",
    "NationalId": "",
    "BirthCertificateSerialNo": "",
    "BirthCertificateIssuePlaceId": "",
    "EconomicalCode": "1",
    "CashId": "1",
    "AttachmentId": ""
}'; 
$rayvarz->addUser();
if($rayvarz->result['status'] == True ){
   echo 'OK';
 }else{
   echo  $rayvarz->result['data'];
}
