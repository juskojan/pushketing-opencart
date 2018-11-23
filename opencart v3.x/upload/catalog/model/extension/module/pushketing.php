<?php
class ModelExtensionModulePushketing extends Model {

    /**
     * @param $keyword
     * @param $value
     * @param $customer
     *
     * Client POST API request sending tag (keyword=value) about specific customer.
     */
    public function postTag($keyword, $value, $customer) {
        if (!is_array($value)) {
            $value = array($value);
        }

        $tag = array(
            'keyword' => $keyword,
            'value' => $value
        );

        $request = array(
            'timestamp' => time(),
            'token' => $this->config->get('module_pushketing_token'),
            'subscriber_id' => $customer,
            'tag' => array($tag)
        );

        $ch = curl_init($this->config->get('module_pushketing_endpoint'));
        $payload = json_encode($request);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $headers = array(
            'Content-Type: application/json'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }


    /**
     * @return array containing products
     *
     * Model function for fetching products from the database
     */
    public function getProducts() {
        $this->load->model('tool/image');
        $this->load->model('setting/setting');

        $currency = $this->model_setting_setting->getSetting('config');

        $data = array();
        $query = $this->db->query("SELECT pd.language_id, pd.name, pd.meta_title, p.price, p.image, p.product_id FROM " . DB_PREFIX . "product_description pd LEFT JOIN " . DB_PREFIX . "product p ON (pd.product_id = p.product_id)");

        foreach ($query->rows as $row){
            $data[$row['product_id']]['title'][$row['language_id']] = $row['name'];
            $data[$row['product_id']]['subtitle'][$row['language_id']] = $row['meta_title'];
            if(!isset($data[$row['product_id']]['image_url']))
                $data[$row['product_id']]['image_url'] = $this->model_tool_image->resize($row['image'], 200, 200);

            if(!isset($data[$row['product_id']]['product_url']))
                $data[$row['product_id']]['product_url'] = $this->url->link('product/product', 'product_id=' . $row['product_id']);

            if(!isset($data[$row['product_id']]['price']))
                $data[$row['product_id']]['price'] = round($row['price']);
        }

        return $data;
    }


    /**
     * @return array containing order statuses
     *
     * Model function for fetching order statuses from the database
     */
    public function getOrderStatuses(){
        $this->load->model('setting/setting');
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status`");

        $data = array();
        if($query->num_rows){
            foreach ($query->rows as $row){
                $data['statuses'][$row['order_status_id']][$row['language_id']] = $row['name'];
                $data['languages'][$row['language_id']] = $this->getLanguageCode($row['language_id']);
            }
        }

        $defaults = $this->model_setting_setting->getSetting('config');
        $data['default']['language'] = isset($defaults['config_language']) ? $defaults['config_language'] : '';
        $data['default']['currency'] = isset($defaults['config_currency']) ? $defaults['config_currency'] : '';

        return $data;
    }

    /**
     * @param $language_id
     * @return string
     *
     * Helper function
     * Returns locale code from language id
     */
    public function getLanguageCode($language_id){
        $query = $this->db->query("SELECT code,name FROM `" . DB_PREFIX . "language` WHERE language_id = '". (int)$language_id ."'");

        if($query->num_rows){
            return $query->rows[0]['code'];
        }else{
            return '';
        }
    }
}
