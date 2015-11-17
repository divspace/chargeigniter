<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ChargeIgniter
 *
 * A Chargify API class for CodeIgniter
 *
 * @author Kyle Anderson <kyle@divspace.com>
 */

class Chargify {
    private $CI;                   // CodeIgniter instance
    
    protected $username = null;    // Chargify API key
    protected $domain   = null;    // Chargify subdomain
    protected $password = null;    // Chargify password (do not change)
    
    public $debug = false;         // Display errors
    
    public function __construct() {
        $this->CI =& get_instance();
        
        log_message('debug', 'ChargeIgniter Class Initialized');
    }
    
    /************************************************************************************
     Customers
    *************************************************************************************/
    
    public function getCustomer($customerId, $source = 'remote') {
        switch($source) {
            case 'remote':
                $result = $this->query('/customers/'.$customerId.'.json');
            break;
            case 'local':
                $result = $this->query('/customers/lookup.json?reference='.$customerId);
            break;
        }
        
        if($result->code === 200) {
            $customer = json_decode($result->response);
            
            if(count($customer) == 1) {
                return $customer->customer;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function getCustomers($pageNumber = 1) {
        $result = $this->query('/customers.json?page='.$pageNumber);
        
        if($result->code === 200) {
            $customers = json_decode($result->response);
            
            if(count($customers) > 0) {
                foreach($customers as $customer) {
                    $temp[] = $customer->customer;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function createCustomer($data) {
        $data = array(
            'customer' => $data
        );
        
        $result = $this->query('/customers.json', 'post', $data);
        
        if($result->code === 201) {
            $customer = json_decode($result->response);
            
            if(count($customer) == 1) {
                return $customer->customer;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function editCustomer($customerId, $data) {
        $data = array(
            'customer' => $data
        );
        
        $result = $this->query('/customers/'.$customerId.'.json', 'put', $data);
        
        if($result->code === 200) {
            $customer = json_decode($result->response);
            
            if(count($customer) == 1) {
                return $customer->customer;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function deleteCustomer($customerId) {
        return $this->query('/customers/'.$customerId.'.json', 'delete');
    }
    
    public function getCustomer_subscriptions($customerId) {
        $result = $this->query('/customers/'.$customerId.'/subscriptions.json');
        
        if($result->code === 200) {
            $subscriptions = json_decode($result->response);
            
            if(count($subscriptions) > 0) {
                foreach($subscriptions as $subscription) {
                    $temp[] = $subscription->subscription;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Products
    *************************************************************************************/
    
    public function getProduct($productId, $source = 'remote') {
        switch($source) {
            case 'remote':
                $result = $this->query('/products/'.$productId.'.json');
            break;
            case 'local':
                $result = $this->query('/products/handle/'.$productId.'.json');
            break;
        }
        
        if($result->code === 200) {
            $product = json_decode($result->response);
            
            if(count($product) == 1) {
                return $product->product;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function getProducts() {
        $result = $this->query('/products.json');
        
        if($result->code === 200) {
            $products = json_decode($result->response);
            
            if(count($products) > 0) {
                foreach($products as $product) {
                    $temp[] = $product->product;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************

     Subscriptions
    *************************************************************************************/
    
    public function getSubscription($subscriptionId) {
        $result = $this->query('/subscriptions/'.$subscriptionId.'.json');
        
        if($result->code === 200) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function getSubscriptions($pageNumber = 1, $resultsPerPage = 2000) {
        $result = $this->query('/subscriptions.json?page='.$pageNumber.'&per_page='.$resultsPerPage);
        
        if($result->code === 200) {
            $subscriptions = json_decode($result->response);
            
            if(count($subscriptions) > 0) {
                foreach($subscriptions as $subscription) {
                    $temp[] = $subscription->subscription;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function getSubscriptionTransactions($subscriptionId) {
        $result = $this->query('/subscriptions/'.$subscriptionId.'/transactions.json');
        
        if($result->code === 200) {
            $transactions = json_decode($result->response);
            
            if(count($transactions) > 0) {
                foreach($transactions as $transaction) {
                    $temp[] = $transaction->transaction;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function createSubscription($data) {
        $data = array(
            'subscription' => $data
        );
        
        $result = $this->query('/subscriptions.json', 'post', $data);
        
        if($result->code === 201) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function editSubscription($subscriptionId, $data) {
        $data = array(
            'subscription' => $data
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'.json', 'put', $data);
        
        if($result->code === 200) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function editSubscriptionComponentQuantity($subscriptionId, $componentId, $amount) {
        $data = array(
            'component' => $amount
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'/component/' . $componentId . '.json', 'put', $data);
        
        if($result->code === 200) {
            $component = json_decode($result->response);
            
            if(count($component) == 1) {
                return $component->component;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function upgradeSubscription($subscriptionId, $data) {
        $data = array(
            'migration' => array(
                'productId' => $data
            )
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'/migrations.json', 'post', $data);
        
        if($result->code === 200) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function cancelSubscription($subscriptionId, $message = '') {
        if(!empty($message)) {
            $data = array(
                'subscription' => array(
                    'cancellationMessage' => $message
                )
            );
            
            $result = $this->query('/subscriptions/'.$subscriptionId.'.json', 'delete', $data);
        } else {
            $result = $this->query('/subscriptions/'.$subscriptionId.'.json', 'delete');
        }
        
        if($result->code === 200) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function reactiveSubscription($subscriptionId) {
        $result = $this->query('/subscriptions/'.$subscriptionId.'/reactivate.json', 'put');
        
        if($result->code === 200) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        return $this->error($result->response, $result->code);
    }
    
    public function resetSubscriptionBalance($subscriptionId) {
        $result = $this->query('/subscriptions/'.$subscriptionId.'/reset_balance.json', 'put');
        
        if($result->code === 200) {
            $subscription = json_decode($result->response);
            
            if(count($subscription) == 1) {
                return $subscription->subscription;
            }
            
            return false;
        }
        
        return $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Charges
    *************************************************************************************/
    
    public function createCharge($subscriptionId, $data) {
        $data = array(
            'charge' => $data
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'/charges.json', 'post', $data);
        
        if($result->code === 201) {
            $charge = json_decode($result->response);
            
            if(count($charge) == 1) {
                return $charge->charge;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Coupons
    *************************************************************************************/
    
    public function getCoupon($productFamilyId, $coupon, $findById = true) {
        if(is_int($coupon) && $findById == true) {
            $result = $this->query('/product_families/'.$productFamilyId.'/coupons/'.$coupon.'.json');
        } else {
            $result = $this->query('/product_families/'.$productFamilyId.'/coupons/find.json?code='.urlencode($coupon));
        }
        
        if($result->code === 200) {
            $coupon = json_decode($result->response);
            
            if(count($coupon) == 1) {
                return $coupon->coupon;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Components
    *************************************************************************************/
    
    public function getComponents($productFamilyId) {
        $result = $this->query('/product_families/'.$productFamilyId.'/components.json');
        
        if($result->code === 200) {
            $components = json_decode($result->response);
            
            if(count($components) > 0) {
                foreach($components as $component) {
                    $temp[] = $component->component;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function createComponentUsage($subscriptionId, $componentId, $data) {
        $data = array(
            'usage' => $data
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'/components/'.$componentId.'/usages.json', 'post', $data);
        
        if($result->code === 200) {
            $component = json_decode($result->response);
            
            if(count($component) == 1) {
                return $component->usage;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    public function getComponentUsage($subscriptionId, $componentId) {
        $result = $this->query('/subscriptions/'.$subscriptionId.'/components/'.$componentId.'/usages.json');
        
        if($result->code === 200) {
            $components = json_decode($result->response);
            
            if(count($components) > 0) {
                foreach($components as $component) {
                    $temp[] = $component->usage;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    
    /************************************************************************************
     Transactions
    *************************************************************************************/
    
    public function getTransactions($types = '', $startId = '', $endId = '', $startDate = '', $endDate = '', $pageNumber = 1, $resultsPerPage = 20) {
        $arguments = '';
        
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        if(is_array($types)) {
            foreach($types as $type) {
                $arguments .= '&kinds[]='.urlencode($type);
            }
        }
        
        if(!empty($startId)) {
            $arguments .= '&since_id='.$startId;
        }
        
        if(!empty($endId)) {
            $arguments .= '&max_id='.$endId;
        }
        
        if(preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $startDate)) {
            $arguments .= '&since_date='.urlencode($startDate);
        }
        
        if(preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $endDate)) {
            $arguments .= '&until_date='.urlencode($endDate);
        }
        
        $result = $this->query('/transactions.json?page='.$pageNumber.'&per_page='.$resultsPerPage.$arguments);
        
        if($result->code === 200) {
            $transactions = json_decode($result->response);
            
            if(count($transactions) > 0) {
                foreach($transactions as $transaction) {
                    $temp[] = $transaction->transaction;
                }
                
                return $temp;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Credits
    *************************************************************************************/
    
    public function addCredit($subscriptionId, $data) {
        $data = array(
            'credit' => $data
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'/credits.json', 'post', $data);
        
        if($result->code ==== 201) {
            $credit = json_decode($result->response);
            
            if($credit->credit->success) {
                return $credit->credit;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Refunds
    *************************************************************************************/
    
    public function refund($subscriptionId, $data) {
        $data = array(
            'refund' => $data
        );
        
        $result = $this->query('/subscriptions/'.$subscriptionId.'/refunds.json', 'post', $data);
        
        if($result->code === 201) {
            $refund = json_decode($result->response);
            
            if(count($refund) == 1) {
                return $refund->refund;
            }
            
            return false;
        }
        
        $this->error($result->response, $result->code);
    }
    
    /************************************************************************************
     Connector
    *************************************************************************************/
    
    protected function query($uri, $method = 'get', $data = '') {
        $method = strtoupper($method);
        
        $content_length = ($data == '') ? 'Content-Length: 0' : '';
        
        $curl_handler = curl_init();
        
        $options = array(
            CURLOPT_URL             => 'https://'.$this->domain.'.chargify.com'.$uri,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => 2,
            CURLOPT_FOLLOWLOCATION  => false,
            CURLOPT_MAXREDIRS       => 1,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CONNECTTIMEOUT  => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTPHEADER      => array('Content-Type: application/json', $content_length, 'Accept: application/json'),
            CURLOPT_USERPWD         => $this->username.':'.$this->password
        );
        
        switch($method) {
            case 'POST':
                $options[CURLOPT_POST] = true;
            break;
            case 'PUT':
            case 'DELETE':
                $options[CURLOPT_CUSTOMREQUEST] = $method;
            break;
        }
        
        if($data != '') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        
        curl_setopt_array($curl_handler, $options);
        
        $result = new StdClass();
        
        $result->response = curl_exec($curl_handler);
        $result->code     = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);
        $result->meta     = curl_getinfo($curl_handler);
        
        $curl_error = ($result->code > 0 ? null : curl_error($curl_handler).' ('.curl_errno($curl_handler).')');
        
        curl_close($curl_handler);
        
        if($curl_error) {
            die('An error occurred while connecting to Chargify: '.$curl_error);
        }
        
        return $result;
    }
    
    /************************************************************************************
     Error Handler
    *************************************************************************************/
    
    public function error($errors, $code) {
        if($this->debug) {
            $errors = json_decode($errors);
            
            switch($code) {
                case 401:
                    $header = 'ERROR CODE 401: UNAUTHORIZED';
                    $detail = 'API authentication has failed. Please check your API key and make sure API access is enabled.';
                break;
                case 403:
                    $header = 'ERROR CODE 403: FORBIDDEN';
                    $detail = 'A valid request was made, but the API does not have this feature enabled for use.';
                break;
                case 404:
                    return false;
                break;
                case 405:
                    $header = 'ERROR CODE 405: METHOD NOT ALLOWED';
                    $detail = 'A request was made to a resource that does not support this method.';
                break;
                case 411:
                    $header = 'ERROR CODE 411: LENGTH REQUIRED';
                    $detail = 'The request did not specify the length of its content, which is required by the requested resource.';
                break;
                case 422:
                    $code   = 'ERROR CODE 422: UNPROCESSABLE ENTITY';
                    $detail = 'A POST or PUT request was sent but is invalid or missing data.';
                break;
                case 500:
                    $header = 'ERROR CODE 500: INTERNAL SERVER ERROR';
                    $detail = 'A generic error message, given when no more specific message is suitable.';
                break;
                default:
                    $header = 'ERROR CODE UNKNOWN';
                    $detail = 'An error code was thrown that is not defined in the application.';
                break;
            }
            
            print '<pre>'."\n";
            print '============================================================'."\n";
            print $header."\n";
            print '============================================================'."\n";
            
            if(isset($detail)) {
                $this->CI =& get_instance();
                
                log_message('error', 'ChargeIgniter: '.$detail);
                
                print "\n".wordwrap($detail, 60)."\n\n";
            }
            
            if(isset($errors->errors)) {
                foreach($errors->errors as $error) {
                    print wordwrap($error, 60)."\n";
                }
            }
            
            print '</pre>'."\n\n";
        }
    }
    
    /************************************************************************************
     NON-API FUNCTIONS: Customer Information
    *************************************************************************************/
    
    public function getChargifyId($customerId) {
        $customer = $this->getCustomer($customerId, 'local');
        
        if($customer) {
            return $customer->id;
        }
        
        return false;
    }
    
    public function getReferenceId($customerId) {
        $customer = $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->reference;
        }
        
        return false;
    }
    
    public function getFirstName($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->first_name;
        }
        
        return false;
    }
    
    public function getLastName($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->last_name;
        }
        
        return false;
    }
    
    public function getOrganization($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->organization;
        }
        
        return false;
    }
    
    public function getAddress($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->address;
        }
        
        return false;
    }
    
    public function getAddress2($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->address_2;
        }
        
        return false;
    }
    
    public function getCity($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->city;
        }
        
        return false;
    }
    
    public function getState($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->state;
        }
        
        return false;
    }
    
    public function getZip($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->zip;
        }
        
        return false;
    }
    
    public function getCountry($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->country;
        }
        
        return false;
    }
    
    public function getEmail($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->email;
        }
        
        return false;
    }
    
    public function getPhone($customerId, $source = 'remote') {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            return $customer->phone;
        }
        
        return false;
    }
    
    public function getTimestamp($customerId, $source = 'remote', $timestamp = array('created', 'updated')) {
        $customer = ($source == 'local') ? $this->getCustomer($customerId, 'local') : $this->getCustomer($customerId);
        
        if($customer) {
            switch($timestamp) {
                case 'created':
                    return strtotime($customer->createdAt);
                break;
                case 'updated':
                    return strtotime($customer->updatedAt);
                break;
            }
        }
        
        return false;
    }
    
    /************************************************************************************
     NON-API FUNCTIONS: Product Information
    *************************************************************************************/
    
    public function getProductName($productId, $source = 'remote') {
        $product = ($source == 'local') ? $this->getProduct($productId, 'local') : $this->getProduct($productId);
        
        if($product) {
            return $product->name;
        }
        
        return false;
    }
    
    public function getProductHandle($productId, $source = 'remote') {
        $product = ($source == 'local') ? $this->getProduct($productId, 'local') : $this->getProduct($productId);
        
        if($product) {
            return $product->handle;
        }
        
        return false;
    }
    
    public function getProductPrice($productId, $source = 'remote', $convertToDollars = true) {
        $product = ($source == 'local') ? $this->getProduct($productId, 'local') : $this->getProduct($productId);
        
        if($product) {
            if($convertToDollars) {
                return ($product->price_in_cents / 100);
            } else {
                return $product->price_in_cents;
            }
        }
        
        return false;
    }
    
    public function getProductDescription($productId, $source = 'remote') {
        $product = ($source == 'local') ? $this->getProduct($productId, 'local') : $this->getProduct($productId);
        
        if($product) {
            return $product->description;
        }
        
        return false;
    }
    
    public function getProductTimestamp($productId, $timestamp = array('created', 'updated', 'archived')) {
        $product = $this->getProduct($productId);
        
        if($product) {
            switch($timestamp) {
                case 'created':
                    return strtotime($product->createdAt);
                break;
                case 'updated':
                    return strtotime($product->updatedAt);
                break;
                case 'archived':
                    return strtotime($product->expiresAt);
                break;
            }
        }
        
        return false;
    }
    
    /************************************************************************************
     NON-API FUNCTIONS: Subscription Information
    *************************************************************************************/
    
    public function getSubscriptionStatus($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            return $subscription->state;
        }
        
        return false;
    }
    
    public function getSubscriptionBalance($subscriptionId, $convertToDollars = true) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            if($convertToDollars) {
                return ($subscription->balance_in_cents / 100);
            } else {
                return $subscription->balance_in_cents;
            }
        }
        
        return false;
    }
    
    public function getSubscriptionTimestamp($subscriptionId, $timestamp = array('created', 'activated', 'updated', 'expiration')) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            switch($timestamp) {
                case 'created':
                    return strtotime($subscription->createdAt);
                break;
                case 'activated':
                    return strtotime($subscription->activated_at);
                break;
                case 'updated':
                    return strtotime($subscription->updatedAt);
                break;
                case 'expiration':
                    return strtotime($subscription->expiresAt);
                break;
            }
        }
        
        return false;
    }
    
    public function getSubscriptionCancellationMesage($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            return $subscription->cancellationMessage;
        }
        
        return false;
    }
    
    /************************************************************************************
     NON-API FUNCTIONS: Credit Card Information
    *************************************************************************************/
    
    public function getCardNumber($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            return $subscription->creditCard->maskedCardNumber;
        }
        
        return false;
    }
    
    public function getCardType($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            return $subscription->creditCard->cardType;
        }
        
        return false;
    }
    
    public function getCardExpirationMonth($subscriptionId, $addZero = true) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            $expirationMonth = $subscription->creditCard->expirationMonth;
            
            if($addZero) {
                return (strlen($expirationMonth) == 1) ? '0'.$expirationMonth : $expirationMonth;
            } else {
                return $expirationMonth;
            }
        }
        
        return false;
    }
    
    public function getCardExpirationYear($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            return $subscription->creditCard->expirationYear;
        }
        
        return false;
    }
    
    public function getCardDetails($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        
        if($subscription) {
            $expirationMonth = $subscription->creditCard->expirationMonth;
            
            $card_array = (object) array(
                'number'          => $subscription->creditCard->maskedCardNumber,
                'type'            => ucfirst($subscription->creditCard->cardType),
                'expirationMonth' => (strlen($expirationMonth) == 1) ? '0'.$expirationMonth : $expirationMonth,
                'expirationYear'  => $subscription->creditCard->expirationYear
            );
        } else {
            $card_array = (object) array(
                'number'          => '',
                'type'            => '',
                'expirationMonth' => '',
                'expirationYear'  => ''
            );
        }
        
        return $card_array;
    }
    
    /************************************************************************************
     NON-API FUNCTIONS: Transaction Information
    *************************************************************************************/
    
    public function getTransaction($transactionId) {
        return $this->getTransactions('', $transactionId, $transactionId, '', '', '', '');
    }
    
    public function getCharges($pageNumber = 1, $resultsPerPage = 20) {
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        return $this->getTransactions(array('charge'), '', '', '', '', $pageNumber, $resultsPerPage);
    }
    
    public function getRefunds($pageNumber = 1, $resultsPerPage = 20) {
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        return $this->getTransactions(array('refund'), '', '', '', '', $pageNumber, $resultsPerPage);
    }
    
    public function getPayments($pageNumber = 1, $resultsPerPage = 20) {
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        return $this->getTransactions(array('payments'), '', '', '', '', $pageNumber, $resultsPerPage);
    }
    
    public function getCredits($pageNumber = 1, $resultsPerPage = 20) {
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        return $this->getTransactions(array('credits'), '', '', '', '', $pageNumber, $resultsPerPage);
    }
    
    public function getPaymentAuthorizations($pageNumber = 1, $resultsPerPage = 20) {
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        return $this->getTransactions(array('payment_authorization'), '', '', '', '', $pageNumber, $resultsPerPage);
    }
    
    public function getAdjustments($pageNumber = 1, $resultsPerPage = 20) {
        $pageNumber     = (empty($pageNumber)) ? '1' : $pageNumber;
        $resultsPerPage = (empty($resultsPerPage)) ? '20' : $resultsPerPage;
        
        return $this->getTransactions(array('adjustment'), '', '', '', '', $pageNumber, $resultsPerPage);
    }
}