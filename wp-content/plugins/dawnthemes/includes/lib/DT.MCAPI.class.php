<?php
class DawnThemes_Mailchimp_Api {
    
    protected $version = '3.0';
    protected $data_center = 'us1';
    protected $api_key = null;
    protected $auth_type = 'key';
    
    public function __construct($api_key)
    {
        if (!empty($api_key)) {
            $this->_set_api_key($api_key);
        }
    }
    
    private function _set_api_key($key)
    {
        $this->api_key = $key;
        if (strstr($key, "-")){
            list($key, $dc) = explode("-", $this->api_key, 2);
            if (!$dc) {
                $dc = "us1";
            }
            $this->data_center = $dc;
        }
        
        return $this;
    }
    
    public function get_lists($as_list = true, $count = 100)
    {
        $result = $this->_http_get('lists', array('count' => $count));
        if ($as_list) {
            $lists = array();
            if (!is_wp_error($result)) {
                $result = (object)$result;
                if (isset($result->lists) && is_array($result->lists)) {
                    foreach ($result->lists as $list) {
                        $list = (object)$list;
                        $lists[$list->id] = $list->name;
                    }
                }
            }
    
            return $lists;
        }
    
        return $result;
    }
    
    public function get_interest_categories($list_id,$as_list = true){
        $results = $this->_http_get("lists/$list_id/interest-categories");
        if($as_list){
            $lists = array();
            if (!is_wp_error($results)) {
                $categories = $results['categories'];
                foreach ($categories as $category) {
                    $lists[] = array(
                        'list_id'=>$category['list_id'],
                        'id'=>$category['id'],
                        'title'=>$category['title'],
                        'type'=>$category['type']
                    );
                }
            }
            
            return $lists;
        }
        return $results;
    }
    
    public function get_interests($list_id,$category_id,$as_list=true){
        $results = $this->_http_get("/lists/{$list_id}/interest-categories/{$category_id}/interests");
        if($as_list){
            $lists = array();
            if (!is_wp_error($results)) {
                $list_interests = $results['interests'];
                foreach ($list_interests as $interests) {
                    $lists[] = array(
                        'list_id'=>$interests['list_id'],
                        'category_id'=>$interests['category_id'],
                        'id'=>$interests['id'],
                        'name'=>$interests['name']
                    );
                }
            }
                
            return $lists;
        }
        return $results;
    }
    
    public function subscribe($list_id, $email, $merge_fields=array(),$double_optin = false, $interests = array())
    {
        $hash = md5(strtolower($email));
        $exists_response = $this->_http_get("lists/$list_id/members/$hash");
        if(!is_wp_error($exists_response)){
            //Member exits
            return true;
        }
        $data = array(
            'email_type' => 'html',
            'email_address' => $email,
            'status' => $double_optin ? 'pending': 'subscribed',
            'merge_fields' => $merge_fields,
            'interests'=>$interests
        );
        
        $data = apply_filters('dawnthemes_mailchimp_api_subscribe_data', $data);
        
        if (empty($data['merge_fields'])) {
            unset($data['merge_fields']);
        }
    
        if (empty($data['interests'])) {
            unset($data['interests']);
        }
        
        return $this->_http_post("lists/$list_id/members", $data);
    }
    
    private function _get_url($extra = '', $params = null)
    {
        $url = "https://{$this->data_center}.api.mailchimp.com/{$this->version}/";
    
        if (!empty($extra)) {
            $url .= $extra;
        }
    
        if (!empty($params)) {
            $url .= '?'.(is_array($params) ? http_build_query($params) : $params);
        }
    
        return $url;
    }
    
    private function _parse_args($agrs=array()){
        $defaults = array(
            'timeout'     => 30,
            'redirection' => 10,
            'httpversion' => '1.1',
            'user-agent'  => 'MailChimp WordPress/' . home_url('/'),
            'headers'     => array("Authorization" => 'apikey ' . $this->api_key)
        );
        
        return apply_filters('dawnthemes_mailchimp_api_parse_args', array_merge( $defaults, $agrs ));
    }

    private function _http_get( $endpoint, $params = array(), $process = true){
        $url = $this->_get_url($endpoint,$params);
        $args = $this->_parse_args();
        $response = wp_remote_get($url, $args);
        if(!$process)
            return $response;
        return $this->_process_http_response($response);
    }
    
    private function _http_post( $endpoint, $body, $process =  true){
        $url = $this->_get_url($endpoint);
        $args = $this->_parse_args(array(
            'body' => json_encode($body)
        ));
        $response = wp_remote_post($url, $args);
        if(!$process)
            return $response;
        return $this->_process_http_response($response);
    }
    
    private function _process_http_response($response){
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response),true);
        if($response_code >= 200 && $response_code <= 400) {
            if (is_array($response_body)) {
                $errors = $this->_check_for_errors($response_body);
                if(is_wp_error($errors))
                    return $errors;
            }
            return $response_body;
        } else {
            if(is_wp_error($response)) {
                return new WP_Error($response_body['status'], $response->get_error_message());
            }
            if ($response_code >= 400 && $response_code <= 500) {
                return new WP_Error($response_body['status'],$response_body['detail']);
            }
                
            if ($response_code >= 500) {
                return new WP_Error( $response_body['status'], $response_body['detail']);
            }
        }
        return new WP_Error('unknow','Unknow Error');
    }
    
    private function _check_for_errors($data)
    {
        // if we have an array of error data push it into a message
        if (isset($data['errors'])) {
            return new WP_Error($data['status'],$data['detail'], $data['errors']);
        }
        // make sure the response is correct from the data in the response array
        if (isset($data['status']) && $data['status'] >= 400) {
            return new WP_Error($data['status'],$data['detail']);
        }
    
        return false;
    }
}
