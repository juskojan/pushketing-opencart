<?php
class ControllerModulePushketing extends Controller {
    /**
     *  RESPONSE TO UNAUTHORIZED API REQUEST
     */
    private function unauthorizedRequest() {
        header('Content-Type: application/json');
        header('HTTP/1.1 401 Unauthorized');
        $response   =   array(
            'code'      =>  401,
            'message'   =>  'Unauthorized access',
        );
        exit(json_encode($response));
    }

    private function forbiddenRequest() {
        header('Content-Type: application/json');
        header('HTTP/1.1 403 Forbidden');
        $response   =   array(
            'code'      =>  403,
            'message'   =>  'Forbidden. Please enable your module in OpenCart.',
        );
        exit(json_encode($response));
    }


    /**
     *  IMPLEMENTATION OF /connectivityTest ENDPOINT
     */
    public function connectivityTest() {
        error_reporting(0);
        // CHECK THE AUTH HEADER IF MATCHES THE TOKEN
        if ($type == "Basic" && $_SERVER['PHP_AUTH_USER'] === "Token" && $_SERVER['PHP_AUTH_PW'] === $this->config->get('module_pushketing_token')) {
            if($this->config->get('module_pushketing_status')){
                $response   =   array(
                    'code'      =>  200,
                    'message'   =>  "OK",
                    'timestamp' =>  time()
                );

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                exit(json_encode($response));
            } else {
                $this->forbiddenRequest();
            }
        } else {
            $this->unauthorizedRequest();
        }
    }


    /**
     *  IMPLEMENTATION OF /getOrderStatuses ENDPOINT
     */
    public function getOrderStatuses() {
        error_reporting(0);
        // CHECK THE AUTH HEADER IF MATCHES THE TOKEN
        if ($_SERVER['PHP_AUTH_USER'] === "Token" && $_SERVER['PHP_AUTH_PW'] === $this->config->get('module_pushketing_token')) {
            if($this->config->get('module_pushketing_status')) {
                $this->load->model('module/pushketing');
                $response = $this->model_module_pushketing->getOrderStatuses();

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                exit(json_encode($response));
            } else {
                $this->forbiddenRequest();
            }
        } else {
            $this->unauthorizedRequest();
        }

    }


    /**
     *  IMPLEMENTATION OF /getProducts ENDPOINT
     */
    public function getProducts() {
        error_reporting(0);
        if ($_SERVER['PHP_AUTH_USER'] === "Token" && $_SERVER['PHP_AUTH_PW'] === $this->config->get('module_pushketing_token')) {
            if($this->config->get('module_pushketing_status')) {
                if(isset($_GET['page'])) {
                    $page   = $_GET['page'];
                } else {
                    $page   = 0;
                }

                if(isset($_GET['limit'])) {
                    $limit  = $_GET['limit'];
                } else {
                    $limit  = 100;
                }

                $this->load->model('module/pushketing');
                $response = $this->model_module_pushketing->getProducts($page, $limit);

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                exit(json_encode($response));
            } else {
                $this->forbiddenRequest();
            }
        } else {
            $this->unauthorizedRequest();
        }
    }
}
