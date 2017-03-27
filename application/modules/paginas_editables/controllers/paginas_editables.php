<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Paginas_editables extends CI_Controller {
	public $medidas;
	function __construct(){
	
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("modelo_paginas", "objPaginas");
		#current
		$this->layout->current = 8;
	}
	
	public function index(){
		#title
		$this->layout->title('Páginas Editables');
		#metas
		$this->layout->setMeta('title','Páginas Editables');
		$this->layout->setMeta('description','Páginas Editables');
		$this->layout->setMeta('keywords','Páginas Editables');
		
		#nav
		$this->layout->nav(array("Páginas Editables"=>"/"));
		
		$contenido['paginas'] = $this->objPaginas->listar();
		//die(print_array($contenido));
		#La vista siempre debe ir cargada al final de la función
		$this->layout->view('index', $contenido);
	}
	
	public function editar($pagina = FALSE){
		if($this->input->post()){
			#validacion
			$this->form_validation->set_rules('contenido','Contenido','required');
			
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
					$datos = array(
						"pag_contenido" => $this->input->post("contenido")
					);
					$where = array(
						"pag_codigo" => $this->input->post("codigo")
					);
					if($this->objPaginas->actualizar($datos,$where)){
						echo json_encode(array("result"=>true));
					}
					else
						echo json_encode(array("result"=>false,"msg"=>"Los datos ingresados no son validos."));

				}
				catch(Exception $e){
					echo json_encode(array("result"=>false,"msg"=>$e->getMessage()));
					// echo json_encode(array("result"=>false,"msg"=>"Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente."));
				}
			}
		}else{
			if(!$pagina) redirect("/paginas-editables/");
			$contenido["pagina"] = $this->objPaginas->obtener(array("pag_codigo" => $pagina));
			#title
			$this->layout->title("Editar " . $contenido["pagina"]->titulo);
			
			#metas
			$this->layout->setMeta('title',"Editar " . $contenido["pagina"]->titulo);
			$this->layout->setMeta('description',"Editar " . $contenido["pagina"]->titulo);
			$this->layout->setMeta('keywords',"Editar " . $contenido["pagina"]->titulo);
			
			#JS - Formulario
			$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
			$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');
			
			#JS - Editor
			$this->layout->js('/js/jquery/ckeditor-standard/ckeditor.js');
			
			$this->layout->js('/js/sistema/paginas/editar.js');
			
			#nav
			$this->layout->nav(array("Página"=>"paginas-editables", "Editar " . $contenido["pagina"]->titulo =>"/"));
			
			$this->layout->view('editar', $contenido);
		}
	}
	
}