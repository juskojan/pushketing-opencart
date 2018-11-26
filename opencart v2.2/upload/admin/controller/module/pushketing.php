<?php

define("PUSHKETING_API_ENDPOINT", "https://app.pushketing.com/api/tag");

class ControllerModulePushketing extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/pushketing');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_pushketing', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
        }

        /* Language variables */
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_token'] = $this->language->get('entry_token');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        /* Errors */
        if (isset($this->error['module_pushketing_token'])) {
            $data['error_module_pushketing_token'] = $this->error['module_pushketing_token'];
        } else {
            $data['error_module_pushketing_token'] = '';
        }

        if (isset($this->error['module_pushketing_endpoint'])) {
            $data['error_module_pushketing_endpoint'] = $this->error['module_pushketing_endpoint'];
        } else {
            $data['error_module_pushketing_endpoint'] = '';
        }

        /* Warnings */
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        /* Breadcrubms navigation */
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/pushketing', 'token=' . $this->session->data['token'], true)
        );


        /* Action URLs */
        $data['action'] = $this->url->link('module/pushketing', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

        /* Form variables */
        if (isset($this->request->post['module_pushketing_status'])) {
            $data['module_pushketing_status'] = $this->request->post['module_pushketing_status'];
        } else {
            $data['module_pushketing_status'] = $this->config->get('module_pushketing_status');
        }

        if (isset($this->request->post['module_pushketing_token'])) {
            $data['module_pushketing_token'] = $this->request->post['module_pushketing_token'];
        } else {
            $data['module_pushketing_token'] = $this->config->get('module_pushketing_token');
        }

        $data['module_pushketing_endpoint'] = PUSHKETING_API_ENDPOINT;

        /* Environment */
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        /* GO! */
        $this->response->setOutput($this->load->view('module/pushketing', $data));
    }

    /* Function runs at uninstall */
    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('module_pushketing');
    }

    /* Form submit validation function */
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/pushketing')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // if module enabled, check the token and endpoint
        if (isset($this->request->post['module_pushketing_status']) && $this->request->post['module_pushketing_status']) {
            if (!$this->request->post['module_pushketing_token']) {
                $this->error['module_pushketing_token'] = $this->language->get('error_module_pushketing_token');
            }

            if (!$this->request->post['module_pushketing_endpoint']) {
                $this->error['module_pushketing_endpoint'] = $this->language->get('error_module_pushketing_endpoint');
            }
        }

        return !$this->error;
    }
}
