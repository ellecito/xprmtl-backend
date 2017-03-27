<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Perfil extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("inicio/modelo_admin", "objAdmin");
	}

	public function index(){

		if($this->input->post()){

			#validaciones
			$this->form_validation->set_rules('nombres', 'Nombres', 'required');
			$this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');


			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}
			else{
				$datos['ad_nombres'] = $this->input->post('nombres');
				$datos['ad_apellidos'] = $this->input->post('apellidos');
				$datos['ad_email'] = $this->input->post('email');
				$this->objAdmin->actualizar($datos,array("ad_codigo"=>$this->session->userdata("usuario")->codigo));
				$usuario = $this->objAdmin->obtener(array("ad_codigo"=>$this->session->userdata("usuario")->codigo));
				$this->session->set_userdata('usuario',$usuario);
				echo json_encode(array("result"=>true));
			}
		}else{

			#js
			$this->layout->js('/js/sistema/perfil/index.js');

			#title
			$this->layout->title('Perfil');

			#metas
			$this->layout->setMeta('title','Perfil');
			$this->layout->setMeta('description','Perfil');
			$this->layout->setMeta('keywords','Perfil');
			
			#nav
			$this->layout->nav(array("Perfil " . $this->session->userdata("usuario")->nombres . " " . $this->session->userdata("usuario")->apellidos=>"/"));

			$this->layout->view('index');

		}
	}
	
	public function password(){

		if($this->input->post()){
			#validaciones
			$this->form_validation->set_rules('passactual', 'Contraseña Actual', 'required');
			$this->form_validation->set_rules('passnueva', 'Contraseña Nueva', 'required');
			$this->form_validation->set_rules('repetirpass', 'Repetir Contraseña', 'required');


			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}
			
			if(!$this->objAdmin->obtener(array("ad_contrasena" => md5($this->input->post('passactual'))))){
				echo json_encode(array("result"=>false,"msg"=>"<div>Contraseña Incorrecta.</div>"));
				exit;
			}
			
			if($this->input->post('passnueva') != $this->input->post('repetirpass')){
				echo json_encode(array("result"=>false,"msg"=>"<div>Contraseña Actual con Repetir Contraseña no coinciden.</div>"));
				exit;
			}
			
			$datos['ad_contrasena'] = md5($this->input->post('passnueva'));
			$this->objAdmin->actualizar($datos,array("ad_codigo"=>$this->session->userdata("usuario")->codigo));

			echo json_encode(array("result"=>true));
		}else{
			redirect("/");
		}
	}
}
