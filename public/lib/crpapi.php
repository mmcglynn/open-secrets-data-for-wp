<?php
/**
 * class php-crpapi
 * Simple PHP client library for working with the Center for Responsive Politics' API.
 * Information on CRP's API can be found at http://www.opensecrets.org/action/api_doc.php
 * Information on using this class, including examples at http://github.com/bpilkerton/php-crpapi
 * @author Ben Pilkerton <bpilkerton@gmail.com>
 * @version 0.2
 */

// http://www.opensecrets.org/api/?method=getLegislators&id=NJ&apikey=a5f3d10fc4afedaa161edd6556dced39
// http://www.opensecrets.org/api/?method=memPFDprofile&year=2016&cid=N00035527&output=json&apikey=a5f3d10fc4afedaa161edd6556dced39
class crp_api {

    function __construct($method=NULL,$params=NULL) {

        $this->api_key = "a5f3d10fc4afedaa161edd6556dced39";
        $this->base_url = "https://www.opensecrets.org/api/";
        $this->output = "json";
        
        //Allow output type to be overridden on object instantiation
        $this->output = isset($params['output']) ? $params['output']: $this->output;
        $this->method = $method;
        self::load_params($params);
        
        $this->file_hash = md5($method . "," . implode(",",$params));
        $this->cache_hash = "dataCache/" . $this->file_hash;
        $this->cache_time = 86400; #one day
        
    }

    private function load_params($params) {

        if ( $this->method == 'memPFDprofile') {
            $this->url = $this->base_url . "?method=" . $this->method . "&year=2016&apikey=" . $this->api_key;
        } else {
            $this->url = $this->base_url . "?method=" . $this->method . "&apikey=" . $this->api_key;
        }

        foreach ($params as $key=>$val) {
            $this->url .= "&" . $key . "=" . $val;
            $this->$key = $val;
        }

        return;
    }

    // MM - true now set to flase for testing
    public function get_data($use_cache=false) {
    
        if ($use_cache and file_exists($this->cache_hash) and (time() - filectime($this->cache_hash) < $this->cache_time)) {
        
            $this->cache_hit = true;
            $file = fopen($this->cache_hash,"r");
            $this->data = stream_get_contents($file);
            $this->data = gzuncompress($this->data);
            $this->data = unserialize($this->data);
            fclose($file);
            $this->response_headers = "No http request sent, using cache";

        } else {
            $this->cache_hit = false;
            $this->data = file_get_contents($this->url);
            $this->response_headers = $http_response_header;
            
            switch ($this->output) {
                case "json":
                    //echo 'you picked json';
                    //var_dump($this->data);
                    $this->data = json_decode($this->data,true);
                    break;
                case "xml":
                    //echo 'you picked xml';
                    //var_dump($this->data);
                    $this->data = simplexml_load_string($this->data);
                    break;
                default:
                    die("Unknown output type.  Use 'json' or 'xml'");
            }

            if ($use_cache) {
                $file = fopen($this->cache_hash,"w");
                $store = serialize($this->data);
                $store = gzcompress($store);
                fwrite($file,$store);
                fclose($file);
            }
        }
        
        return $this->data;
    }
    
    function get_cache_status() {
        return $this->cache_hit;
    }

    function get_response_headers() {
        return $this->response_headers;
    }
    
}
?>
