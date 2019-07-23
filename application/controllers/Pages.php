<?php
class Pages extends CI_Controller{

    /**
     * Lädt Seiten, wenn vorhanden
     * Stellt 404 dar, wenn nicht
     *
     * @param string $page
     * @return void
     */
    public function view($page= 'home')  {
        if(!file_exists(APPPATH.'views/pages/'.$page.'.php')) {
            show_404();
        }
        $this->User_model->resetValues($this->session->userdata('user_id'));
        $data['title'] = ucfirst($page);
        $this->load->view('templates/header');
        $this->load->view('pages/'.$page,$data);
        $this->load->view('templates/footer');
    }
}