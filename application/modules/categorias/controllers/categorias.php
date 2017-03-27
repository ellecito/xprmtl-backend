<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Categorias extends CI_Controller {
	public $medidas;
	function __construct(){
	
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("categorias/modelo_categorias", "objCategoria");
		#current
		$this->layout->current = 2;
	}
	
	public function index($pagina = 1){
		#title
		$this->layout->title('Categorías');
		#metas
		$this->layout->setMeta('title','Categorías');
		$this->layout->setMeta('description','Categorías');
		$this->layout->setMeta('keywords','Categorías');
		
		#JS - pagination
		$this->layout->js('/js/jquery/rpage-master/responsive-paginate.js');
		$this->layout->js('/js/jquery/rpage-master/paginate-init.js');
		#JS - Estados
		$this->layout->js('/js/sistema/categorias/index.js');
		#nav
		$this->layout->nav(array("Categorías"=>"/"));
		
		#filtros
		$where = false;
		$contenido["buscar_get"] = '';
		if($this->input->get("buscar")){
			$where = "ca_nombre LIKE '%" . $this->input->get("buscar") . "%'";
			$contenido["buscar_get"] = $this->input->get("buscar");
		}
		
		#Paginación
		$this->load->library('pagination');
		$config['uri_segment'] = 2;
		$config['base_url'] = base_url().'/categorias/';
		$config['per_page'] = 15;
		$config['total_rows'] = $this->objCategoria->total($where);
		$this->pagination->initialize($config);
		
		$contenido['categorias'] = $this->objCategoria->listar($config['per_page'], $pagina, $where);
		#La vista siempre debe ir cargada al final de la función
		$this->layout->view('index', $contenido);
	}

	public function agregar(){
		if($this->input->post()){
			#validacion
			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('orden','Orden','required');
			
			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}else{
				try{
					$insert = array(
						"ca_codigo" => $this->objCategoria->getLastId(),
						"ca_estado" => 1,
						"ca_visible" => 1,
						"ca_nombre" => $this->input->post("nombre"),
						"ca_orden" => $this->input->post("orden")
					);
					if($this->objCategoria->insertar($insert)){
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
			#title
			$this->layout->title('Agregar Categoría');
			
			#metas
			$this->layout->setMeta('title','Agregar Categoría');
			$this->layout->setMeta('description','Agregar Categoría');
			$this->layout->setMeta('keywords','Agregar Categoría');
			
			#JS - Formulario
			$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
			$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');
			
			$this->layout->js('/js/sistema/categorias/agregar.js');
			
			#nav
			$this->layout->nav(array("Categoría"=>"categorias", "Agregar categoría" =>"/"));
			
			$this->layout->view('agregar');
		}
	}
	
	public function editar($categoria = FALSE){
		if($this->input->post()){
			#validacion
			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('orden','Orden','required');
			
			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}else{
				try{
					$datos = array(
						"ca_nombre" => $this->input->post("nombre"),
						"ca_orden" => $this->input->post("orden")
					);
					$where = array(
						"ca_codigo" => $this->input->post("codigo")
					);
					if($this->objCategoria->actualizar($datos,$where)){
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
			if(!$categoria) redirect("/categorias/");
			$contenido["categoria"] = $this->objCategoria->obtener(array("ca_codigo" => $categoria));
			if(!$contenido["categoria"]) redirect("/categorias/");
			
			#title
			$this->layout->title('Editar Categoría');
			
			#metas
			$this->layout->setMeta('title','Editar Categoría');
			$this->layout->setMeta('description','Editar Categoría');
			$this->layout->setMeta('keywords','Editar Categoría');
			
			#JS - Formulario
			$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
			$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');
			
			$this->layout->js('/js/sistema/categorias/editar.js');
			
			#nav
			$this->layout->nav(array("Categorías"=>"categorias", "Editar categoría" =>"/"));
			
			$this->layout->view('editar', $contenido);
		}
	}
	
	public function eliminar(){
		if($this->input->post()){
			if($this->objCategoria->actualizar(array("ca_visible"=>0), array("ca_codigo" => $this->input->post("codigo")))){
				echo json_encode(array("result"=>true));
			}else{
				echo json_encode(array("result"=>false));
			}
		}else{
			redirect("/categorias/");
		}
	}
	
	public function estados(){
        if($codigo = $this->input->post('codigo')){
            list($codigo,$estado) = explode('-',$codigo);
            
            $this->objCategoria->actualizar(array("ca_estado"=>$estado),array("ca_codigo"=>$codigo));
            
            echo json_encode(array("result"=>true));
        }
        else
            redirect("/categorias/");
    }
}