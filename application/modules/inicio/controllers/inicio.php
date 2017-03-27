<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Inicio extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model("modelo_admin", "objAdmin");
		$this->load->model("modelo_email", "objEmail");
	}

	public function index(){
		if($this->session->userdata("usuario")) redirect("/productos/");
		#title
		$this->layout->title('XPRMTL');
		
		#metas
		$this->layout->setMeta('title','XPRMTL');
		$this->layout->setMeta('description','XPRMTL');
		$this->layout->setMeta('keywords','XPRMTL');
		
		$contenido['home_indicador'] = true;
		
		$this->layout->js('/js/sistema/index/login.js');
		
		$this->layout->view('inicio',$contenido);
	}
	
	public function recuperar(){
		if($this->input->post("email")){
			$newpass = md5(rand());
			$this->objEmail->recuperar($newpass);
		}else{
			redirect('/');
		}
	}
	
	public function login(){
		
		if($this->input->post()){
			#validacion
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('contrasena', 'Password', 'required');
			
			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_message('valid_email', '* El email no es válido');
			$this->form_validation->set_error_delimiters('<div>','</div>');
			
			if(!$this->form_validation->run()){
				$error = validation_errors();
				echo json_encode(array("result"=>false,"msg"=>$error));
			}
			else
			{
				try{
					$where = array(
						"ad_email" => $this->input->post('email'),
						"ad_contrasena" => md5($this->input->post('contrasena'))
					);
					if($usuario = $this->objAdmin->obtener($where)){
						$this->session->set_userdata('usuario',$usuario);
						echo json_encode(array("result"=>true));
					}else{
						echo json_encode(array("result"=>false,"msg"=>"Los datos ingresados no son validos."));
					}
				}
				catch(Exception $e){
					echo json_encode(array("result"=>false,"msg"=>$e->getMessage()));
				}
			}
		}else{
			redirect('/');
		}
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect('/');
	}
	
}