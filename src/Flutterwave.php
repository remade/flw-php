<?php

namespace Remade\Flutterwave;

use GuzzleHttp\Client;
use Valitron\Validator;

class Flutterwave {

    /**
     * Flutterwave's requests environment
     * @var string
     */
    protected $environment;

    /**
     * Current version
     * @var string
     */
    protected $version;

    /**
     * Your Flutterwave Merchant Key
     * @var string
     */
    protected $merchantKey;

    /**
     * Your Flutterwave API Key
     * @var string
     */
    protected $apiKey;

    /**
     * Set to one of the available module
     * @var string
     */
    protected $module = '';

    /**
     * Base URLs according to the versions
     * @var array
     */
    protected $baseUrls = [
        'v1' => [
            'live' => 'https://prod1flutterwave.co:8181/pwc/rest/',
            'staging' => 'http://staging1flutterwave.co:8080/pwc/rest/'
        ],
        'v2' => [
            'live' => 'https://flutterwaveprodv2.com/pwc/rest/',
            'staging' => 'https://flutterwavestagingv2.com/pwc/rest/'
        ]
    ];

    /**
     * Available Flutterwave endpoints
     * @var array
     */
    protected $modules = ['account'];

    /**
     * Flutterwave constructor.
     * @param array $options
     */
    public function __construct($options = []){

        $defaultOptions = [
            'version' => 'v1',
            'environment' => 'staging',
            'merchant_key' => '',
            'api_key' => ''
        ];

        $options = array_merge($defaultOptions, $options);

        $this->version = $options['version'] == 'v1' ||  $options['version'] == 'v2'?  $options['version'] : 'v1';
        $this->environment = $options['environment'] == 'live' ||  $options['environment'] == 'staging'?  $options['environment'] : 'staging';
        $this->merchantKey = $options['merchant_key'];
        $this->apiKey = $options['api_key'];

        foreach ($this->modules as $module){
            $moduleFile = 'flw.'.$module.'.php';
            $this->modules[$module] = require( __DIR__ .'/endpoints/'.$moduleFile);
        }
    }

    /**
     * Set desired version
     *
     * @param $version
     * @return $this
     */
    public function setVersion($version){
        $this->version = $version == 'v1' || $version == 'v2'? $version : 'v1';
        return $this;
    }

    /**
     * Set environment
     *
     * @param $environment
     * @return $this
     */
    public function setEnvironment($environment){
        $this->environment = $environment == 'live' || $environment == 'staging'? $environment : 'staging';
        return $this;
    }

    /**
     * Set Merchant Key
     *
     * @param $merchantKey
     * @return $this
     */
    public function setMerchantKey($merchantKey){
        $this->merchantKey = $merchantKey;
        return $this;
    }

    /**
     * Set API key
     *
     * @param $apiKey
     * @return $this
     */
    public function setAPIKey($apiKey){
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Return Validated and Pruned data
     *
     * @param $data
     * @param $params
     * @param $rules
     * @param $prepare
     * @return array
     */
    protected function validate($data, $params, $rules, $prepare){
        //Create Validator instance
        $validator = new Validator($data);

        //prepare rules
        $validator->mapFieldsRules($rules);

        //validate
        if(!$validator->validate()){
            throw new \InvalidArgumentException('validation failed'); //todo: return errors with a custom exception class
        }

        //Prepare. Check the prepare array and modify the value as indicated in the array
        foreach ($prepare as $key=>$value){
            if(in_array($key,array_keys($data))){
                //Parameter to be prepared exists in the $data array

                //Preparations
                $preparations = $value;
                foreach ($preparations as $preparation){
                    //Iterate through the preparation

                    if(strtolower($preparation) == '3des'){
                        $data[$key] = Encryption::encrypt3Des($data[$key], $this->merchantKey);
                    }

                }
            }
        }

        //Return Data. Replace parameter alias with FLW required Parameters
        $return = [];
        foreach ($params as $key=>$value){
            if(in_array($key, array_keys($data))){
                $return[$value] = $data[$key];
            }
        }

        return $return;

    }

    /**
     * Get module instance
     *
     * @param $name
     * @return $this
     */
    public function __get($name){
        if(in_array($name, array_keys($this->modules))){
            $this->module = $name;
            return $this;
        }
        else{
            throw new \BadMethodCallException($name." does not exists");
        }
    }


    public function __call($method, $parameters){

        if(empty($this->module) || !in_array($method, array_keys($this->modules[$this->module])))
        {
            throw new \BadMethodCallException($method." does not exists");
        }

        //Validate
        if(empty($this->merchantKey)){
            throw new \InvalidArgumentException("Merchant Key not set");
        }

        if(empty($this->apiKey)){
            throw new \InvalidArgumentException("API key not set");
        }

        //Now we are sure that Module and Method exists
        $endpoint = $this->modules[$this->module][$method];

        //Validate
        $data = $this->validate($parameters[0], $endpoint['params'], $endpoint['validate'], $endpoint['prepare']);
        $data['merchantid'] = $this->merchantKey;

        //Make Request
        $client = new Client([
            'base_uri' => $this->baseUrls[$this->version][$this->environment]
        ]);

        $requestOptions = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json' => $data,
            'http_errors' => false,
        ];


        $response = $client->request($endpoint['method'], $endpoint['url'], $requestOptions);
        return json_decode($response->getBody());
    }


    
}
