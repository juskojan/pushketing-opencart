<?php
class ControllerExtensionModulePushketing extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/pushketing');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_pushketing', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        /* Language variables */
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_token'] = $this->language->get('entry_token');
        $data['entry_endpoint'] = $this->language->get('entry_endpoint');
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
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/pushketing', 'user_token=' . $this->session->data['user_token'], true)
        );

        /* Action URLs */
        $data['action'] = $this->url->link('extension/module/pushketing', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

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

        if (isset($this->request->post['module_pushketing_endpoint'])) {
            $data['module_pushketing_endpoint'] = $this->request->post['module_pushketing_endpoint'];
        } else {
            $data['module_pushketing_endpoint'] = $this->config->get('module_pushketing_endpoint');
        }

        /* Environment */
        $data['user_token'] = $this->session->data['user_token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        /* GO! */
        $this->response->setOutput($this->load->view('extension/module/pushketing', $data));
    }


    /* Function runs at installation */
    public function install() {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` ADD `pk_customer` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT ''");
    }

    /* Function runs at uninstall */
    public function uninstall() {
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` DROP `pk_customer`");
    }

    /* Form submit validation function */
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/pushketing')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['module_pushketing_token']) {
            $this->error['module_pushketing_token'] = $this->language->get('error_module_pushketing_token');
        }

        if (!$this->request->post['module_pushketing_endpoint']) {
            $this->error['module_pushketing_endpoint'] = $this->language->get('error_module_pushketing_endpoint');
        }

        return !$this->error;
    }
}