<?php
class ControllerExtensionModulePushketing extends Controller {
	private $error = array();
	
	public function index() {
        $this->load->language('extension/module/pushketing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pushketing', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_token'] = $this->language->get('entry_token');
        $data['entry_endpoint'] = $this->language->get('entry_endpoint');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

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

		$data['action'] = $this->url->link('extension/module/pushketing', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['pushketing_status'])) {
            $data['pushketing_status'] = $this->request->post['pushketing_status'];
        } else {
            $data['pushketing_status'] = $this->config->get('pushketing_status');
        }

        if (isset($this->request->post['pushketing_token'])) {
            $data['pushketing_token'] = $this->request->post['pushketing_token'];
        } else {
            $data['pushketing_token'] = $this->config->get('pushketing_token');
        }

        if (isset($this->request->post['pushketing_endpoint'])) {
            $data['pushketing_endpoint'] = $this->request->post['pushketing_endpoint'];
        } else {
            $data['pushketing_endpoint'] = $this->config->get('pushketing_endpoint');
        }
		
		$data['user_token'] = $this->session->data['user_token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/pushketing', $data));
	}

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/pushketing')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}