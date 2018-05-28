<?php
class ControllerExtensionModulePushketing extends Controller {
    private function badRequest($description) {
        header('Content-Type: application/json');
        header('HTTP/1.1 400 Bad Request');
        $response   =   array(
            'code'          =>  400,
            'message'       =>  'Bad Request',
            'description'   =>  $description
        );
        exit(json_encode($response));
    }

    private function unauthorizedRequest() {
        header('Content-Type: application/json');
        header('HTTP/1.1 401 Unauthorized');
        $response   =   array(
            'code'      =>  401,
            'message'   =>  'Unauthorized access',
        );
        exit(json_encode($response));
    }

    public function connectivityTest() {
        error_reporting(0);
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $space_explode  = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
            $type           = $space_explode[0];

            if ($type == "Basic" && $_SERVER['PHP_AUTH_USER'] === "Token" && $_SERVER['PHP_AUTH_PW'] === $this->config->get('pushketing_token')) {
                $response   =   array(
                    'code'      =>  200,
                    'message'   =>  "OK",
                    'timestamp' =>  time()
                );

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');

                exit(json_encode($response));
            } else {
                $this->unauthorizedRequest();
            }
        }else {
            $this->unauthorizedRequest();
        }
    }

    public function getOrderStatuses() {
        error_reporting(0);
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $space_explode  = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
            $type           = $space_explode[0];

            if ($type == "Basic" && $_SERVER['PHP_AUTH_USER'] === "Token" && $_SERVER['PHP_AUTH_PW'] === $this->config->get('pushketing_token')) {
                $this->load->model('extension/pushketing');
                $response = $this->model_extension_pushketing->getOrderStatuses();

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                exit(json_encode($response));
            } else {
                $this->unauthorizedRequest();
            }
        } else {
            $this->unauthorizedRequest();
        }
    }

    public function getProducts() {
        error_reporting(0);
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $space_explode  = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
            $type           = $space_explode[0];

            if ($type == "Basic" && $_SERVER['PHP_AUTH_USER'] === "Token" && $_SERVER['PHP_AUTH_PW'] === $this->config->get('pushketing_token')) {
                $this->load->model('extension/pushketing');
                $response = $this->model_extension_pushketing->getProducts();

                header('Content-Type: application/json');
                header('HTTP/1.1 200 OK');
                exit(json_encode($response));
            } else {
                $this->unauthorizedRequest();
            }
        } else {
            $this->unauthorizedRequest();
        }
    }
}