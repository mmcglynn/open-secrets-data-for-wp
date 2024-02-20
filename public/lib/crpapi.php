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

    function __construct( $params = NULL ) {

        // echo '<h4>$params</h4>';
        // var_dump($params);

        $this->method   = $params['method'];
        $this->cid      = $params['cid'];
        $this->cycle    = $params['cycle'];
        $this->api_key  = $params['api_key'];
        $this->base_url = $params['base_url'];
        $this->output   = "json";
        
        // echo '[' . $this->api_key . ']<br>';
        // echo '[' . $this->base_url . ']<br>';

        //Allow output type to be overridden on object instantiation
        //$this->output = isset($params['output']) ? $params['output']: $this->output;
        //$this->method = $method;
        self::load_params($params);
        
        // echo '[' . $this->method . ']<br>';

        //$this->file_hash = md5($method . "," . implode(",",$params));
        //$this->cache_hash = "dataCache/" . $this->file_hash;
        //$this->cache_time = 86400; #one day
        
    }

    private function load_params( $params ) {
        //echo 'Called load_params()' . '<br>';
        if ( $this->method == 'memPFDprofile') {
            $this->url = $this->base_url . "?method=" . $this->method . "&year=2016&apikey=" . $this->api_key;
        } else {
            $this->url = $this->base_url 
                        . "?method=" . $this->method 
                        . "&apikey=" . $this->api_key 
                        . "&cid=" . $this->cid 
                        . "&cycle=" . $this->cycle 
                        . "&output=" . $this->output;
        }

        // Not yet until above is resolved
        // foreach ( $params as $key=>$val ) {
        //     //$this->url .= "&" . $key . "=" . $val;
        //     //$this->$key = $val;
        //     echo $key . "=" . $val;
        // }

        //echo $this->url;

        return;
    }

    // We'll want to/ dupllicate this function with
    //         // wp_remote_request( string $url, array $args = array() )

    public function get_data() {

        //echo 'Called get_data()' . '<br>';

        $ch = curl_init( $this->url );

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        //echo curl_exec($ch);

        $this->data = curl_exec($ch);

        // Debug
        /*
        $info = curl_getinfo($ch);
        $message = '<p>gettype($this->data): ' . gettype($this->data) . '</p>';
        $message .= '<p>$this->url: ' . $this->url . '</p>';
        $message .= '<ul>';
        $message .= '<li>total_time: ' . $info['total_time'] . '</li>';
        $message .= '<li>size_download: ' . $info['size_download'] . '</li>';
        $message .= '<li>speed_download: ' . $info['speed_download'] . '</li>';
        $message .= '<li>download_content_length: ' . $info['download_content_length'] . '</li>';
        $message .= '<li>total_time_us: ' . $info['total_time_us'] . '</li>';
        $message .= '</ul>';
        $message .= '<p>$http_response_header: ' . get_response_headers() . '</p>';
        echo $message;
        */
        //var_dump($http_response_header);

        curl_close( $ch );
        switch ( $this->output ) {
            case "json":
                $this->data = json_decode( $this->data, true );
                break;
            case "xml":
                $this->data = simplexml_load_string( $this->data );
                break;
            default:
                die( "Unknown output type.  Use 'json' or 'xml'" );
        }

        return $this->data;
    }

//    function get_cache_status() {
//        return $this->cache_hit;
//    }

//    function get_response_headers() {
//        return $this->response_headers;
//    }
    
}