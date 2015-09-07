<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Form_collection
{
    private $CI = 0;
    
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    //moodit ovat: application, edit, admin
    public function get_stable_form($mode)
    {
        if($mode == 'application')
        {
            $this->CI->load->library('form_builder', array('submit_value' => 'Rekisteröi talli', 'required_text' => '*Pakollinen kenttä'));
            $this->CI->load->model('tallit_model');
    
            $fields['nimi'] = array('type' => 'text', 'required' => TRUE, 'class'=>'form-control');
            $fields['kategoria'] = array('type' => 'select', 'required' => TRUE, 'options' => $this->CI->tallit_model->get_category_option_list(), 'after_html' => '<span class="form_comment">Valitse tallin pääkategoria. Voit lisätä kategorioita lisää myöhemmin.</span>', 'class'=>'form-control');
            $fields['kuvaus'] = array('type' => 'textarea', 'cols' => 40, 'rows' => 3, 'class'=>'form-control');
            $fields['osoite'] = array('type' => 'text', 'required' => TRUE, 'value' => 'http://', 'class'=>'form-control');
            $fields['lyhehd'] = array('type' => 'text', 'label' => 'Lyhenne ehdotus', 'after_html' => '<span class="form_comment">Voit ehdottaa 2-4 merkkistä lyhenteen kirjainosaa tallillesi. Ylläpito ottaa sen huomioon tallitunnusta päätettäessä.</span>', 'class'=>'form-control');
            
            $this->CI->form_builder->form_attrs = array('method' => 'post', 'action' => site_url('/profiili/omat-tallit/rekisteroi'));
        }

        return $this->CI->form_builder->render_template('_layouts/basic_form_template', $fields);
    }
    
    public function validate_stable_form($mode)
    {
        $this->CI->load->library('form_validation');
        
        if($mode == 'application')
        {
            $this->CI->form_validation->set_rules('nimi', 'Nimi', "required|min_length[1]|max_length[128]|regex_match[/^[A-Za-z0-9_\-.:,; *~#&'@()]*$/]");
            $this->CI->form_validation->set_rules('kuvaus', 'Kuvaus', "max_length[1024]|regex_match[/^[A-Za-z0-9_\-.:,; *~#&'@()]*$/]");
            $this->CI->form_validation->set_rules('osoite', 'Osoite', "required|min_length[4]|max_length[1024]|regex_match[/^[A-Za-z0-9_\-.:,; \/*~#&'@()]*$/]");
            $this->CI->form_validation->set_rules('kategoria', 'Kategoria', 'required|min_length[1]|max_length[2]|numeric');
            $this->CI->form_validation->set_rules('lyhehd', 'Lyhenne ehdotus', "min_length[2]|max_length[4]|alpha");
        }
        
        return $this->CI->form_validation->run();
    }
}


