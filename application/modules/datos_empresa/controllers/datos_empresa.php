<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Datos_empresa extends CI_Controller {

	function __construct(){

		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		#current
		$this->layout->current = 7;
		$this->load->model("modelo_empresa", "objEmpresa");
	}

	public function index()	{

		if($this->input->post()){
			$this->form_validation->set_rules('nombre', 'Nombre', 'required');
			$this->form_validation->set_rules('direccion', 'Dirección', 'required');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}else{
				$where['de_codigo'] = 1;
				$datos['de_nombre'] = $this->input->post('nombre');
				$datos['de_slogan'] = $this->input->post('slogan');
				$datos['de_direccion'] = $this->input->post('direccion');
				$datos['de_telefono'] = $this->input->post('telefono');
				$datos['de_telefono_2'] = $this->input->post('telefono2');
				$datos['de_email'] = $this->input->post('email');
				$datos['de_email_cc'] = $this->input->post('email2');
				$datos['de_email_bcc'] = $this->input->post('email3');
				$datos['de_keywords'] = $this->input->post('keywords');
				$datos['de_descripcion'] = $this->input->post('descripcion');
				$datos['de_facebook'] = $this->input->post('facebook');
				$datos['de_twitter'] = $this->input->post('twitter');
				$datos['de_instagram'] = $this->input->post('instagram');
				$datos['de_youtube'] = $this->input->post('youtube');
				
				$this->objEmpresa->actualizar($datos, $where);
				echo json_encode(array("result"=>true));
			}
		}else{
			#Title
			$this->layout->title('Datos Empresa');

			#Metas
			$this->layout->setMeta('title','Datos Empresa');
			$this->layout->setMeta('description','Datos Empresa');
			$this->layout->setMeta('keywords','Datos Empresa');

			#current
			$this->layout->subCurrent = 1;

			#JS - Datepicker
			$this->layout->css('/js/jquery/ui/1.10.4/ui-lightness/jquery-ui-1.10.4.custom.min.css');
			$this->layout->js('/js/jquery/ui/1.10.4/jquery-ui-1.10.4.custom.min.js');
			$this->layout->js('/js/jquery/ui/1.10.4/jquery.ui.datepicker-es.js');

			#JS - Multiple select boxes
			$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
			$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');
			#mapa
			$this->layout->js('https://maps.google.com/maps/api/js?key=AIzaSyCqRJnqQKe7NJfuXjyJ00pi-SfQipzkZDM');

			$this->layout->js('/js/sistema/datos_empresas/index.js');

			#contenido
			$contenido["empresa"] = $this->objEmpresa->obtener(array("de_codigo" => 1));
			
			$this->layout->view('index',$contenido);
		}
	}
}
