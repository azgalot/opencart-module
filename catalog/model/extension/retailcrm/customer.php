<?php

class ModelExtensionRetailcrmCustomer extends Model {
    public function sendToCrm($customer) {
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('retailcrm');

        if(empty($customer))
            return false;
        if(empty($settings['retailcrm_url']) || empty($settings['retailcrm_apikey']))
            return false;

        require_once DIR_SYSTEM . 'library/retailcrm/bootstrap.php';

        $this->retailcrmApi = new RetailcrmProxy(
            $settings['retailcrm_url'],
            $settings['retailcrm_apikey'],
            DIR_SYSTEM . 'storage/logs/retailcrm.log'
        );

        $customerToCrm = $this->process($customer);

        $this->retailcrmApi->customersCreate($customerToCrm);
    }

    public function changeInCrm($customer) {
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('retailcrm');

        if(empty($customer))
            return false;
        if(empty($settings['retailcrm_url']) || empty($settings['retailcrm_apikey']))
            return false;

        require_once DIR_SYSTEM . 'library/retailcrm/bootstrap.php';

        $this->retailcrmApi = new RetailcrmProxy(
            $settings['retailcrm_url'],
            $settings['retailcrm_apikey'],
            DIR_SYSTEM . 'storage/logs/retailcrm.log'
        );

        $customerToCrm = $this->process($customer);
        
        $this->retailcrmApi->customersEdit($customerToCrm);
    }
    
    private function process($customer) {
        $customerToCrm = array(
            'externalId' => $customer['customer_id'],
            'firstName' => $customer['firstname'],
            'lastName' => $customer['lastname'],
            'email' => $customer['email'],
            'phones' => array(
                array(
                    'number' => $customer['telephone']
                )
            ),
            'createdAt' => $customer['date_added']
        );

        return $customerToCrm;
    }
}
