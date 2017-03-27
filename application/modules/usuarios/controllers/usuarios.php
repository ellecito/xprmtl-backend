<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Usuarios extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("modelo_usuarios", "objUsuario");
		$this->load->model("modelo_pais", "objPais");
		$this->load->model("modelo_region", "objRegion");
		$this->load->model("modelo_comuna", "objComuna");
		#current
		$this->layout->current = 9;
	}

	public function index(){

		#Title
		$this->layout->title('Usuarios');

		#Metas
		$this->layout->setMeta('title','Usuarios');
		$this->layout->setMeta('description','Usuarios');
		$this->layout->setMeta('keywords','Usuarios');

		#js
		$this->layout->js('/js/sistema/usuarios/index.js');

		#filtros
		$where = $and = $contenido['q_f'] = '';
		if($this->input->get('q')){
			$contenido['q_f'] = $q = $this->input->get('q');
			$where = "us_nombres like '%$q%' or us_email like '%$q%'";
		}

		#url
		$url = explode('?',$_SERVER['REQUEST_URI']);
		if(isset($url[1]))
			$contenido['url'] = $url = '/?'.$url[1];
		else
			$contenido['url'] = $url = '/';

		#paginacion
		$config['base_url'] = '/usuarios/';
		$config['total_rows'] = count($this->objUsuario->listar($where));
		$config['per_page'] = 15;
		$config['uri_segment'] = $segment = 3;
		$config['suffix'] = $url;
		$config['first_url'] = '/usuarios'.$url;

		$this->pagination->initialize($config);
		$page = ($this->uri->segment($segment))?$this->uri->segment($segment)-1:0;

		$contenido['datos'] = $this->objUsuario->listar($where);
		$contenido['pagination'] = $this->pagination->create_links();

		#JS - pagination
		#$this->layout->js('/js/jquery/rpage-master/responsive-paginate.js');
		#$this->layout->js('/js/jquery/rpage-master/paginate-init.js');

		#Nav
		#$this->layout->nav(array("Colaboradores"=>"/"));

		#La vista siempre,  debe ir cargada al final de la función
		$this->layout->view('index', $contenido);
	}

	public function agregar(){

		if($this->input->post()){

			#validaciones
			$this->form_validation->set_rules('nombres', 'Nombres', 'required');
			$this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'required');
			$this->form_validation->set_rules('direccion', 'Dirección', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('contrasena', 'Contraseña', 'required');
			$this->form_validation->set_rules('comuna', 'Comuna', 'required');

			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}
			else{
				$actualizar = false;
				
				if($usu = $this->objUsuario->obtener(array("us_email" => $this->input->post('email')))){
					if($usu->visible == 1){
						echo json_encode(array("result"=>false,"msg"=>"El e-mail ya existe"));
						exit;
					}else{
						$datos['us_visible'] = 1;
						$actualizar = true;
						$codigo = $usu->codigo;
					}
				}
				
				$datos['us_codigo'] = $this->objUsuario->getLastId();
				$datos['us_nombres'] = $this->input->post('nombres');
				$datos['us_apellidos'] = $this->input->post('apellidos');
				$datos['us_direccion'] = $this->input->post('direccion');
				$datos['us_telefono'] = $this->input->post('telefono');
				$datos['us_email'] = $this->input->post('email');
				$datos['us_contrasena'] = md5($this->input->post('contrasena'));
				$datos['co_codigo'] = $this->input->post('comuna');
				$datos['us_estado'] = 1;
				$datos['us_visible'] = 1;

				if($actualizar){
					unset($datos['us_codigo']);
					$this->objUsuario->actualizar($datos,array("us_codigo"=>$codigo));
				}else{
					$usuario = $this->objUsuario->insertar($datos);
				}

				echo json_encode(array("result"=>true));
			}
		}
		else{
			#title
			$this->layout->title('Agregar Usuario');

			#metas
			$this->layout->setMeta('title','Agregar Usuario');
			$this->layout->setMeta('description','Agregar Usuario');
			$this->layout->setMeta('keywords','Agregar Usuario');

			#js
			$this->layout->js('/js/sistema/usuarios/agregar.js');

			#JS - Multiple select boxes
			$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
			$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');

			#nav
			$this->layout->nav(array("Usuarios "=>"usuarios", "Agregar Usuarios" =>"/"));
			$contenido["comunas"] = $this->objComuna->listar();
			$this->layout->view('agregar', $contenido);
		}
	}

	public function editar($codigo = false){

		if($this->input->post()){

			#validaciones
			$this->form_validation->set_rules('nombres', 'Nombres', 'required');
			$this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'required');
			$this->form_validation->set_rules('direccion', 'Dirección', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			//$this->form_validation->set_rules('contrasena', 'Contraseña', 'required');
			$this->form_validation->set_rules('comuna', 'Comuna', 'required');

			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}
			else{
				if($this->input->post('contrasena')) $datos['us_contrasena'] = md5($this->input->post('contrasena'));
				$datos['us_nombres'] = $this->input->post('nombres');
				$datos['us_apellidos'] = $this->input->post('apellidos');
				$datos['us_direccion'] = $this->input->post('direccion');
				$datos['us_telefono'] = $this->input->post('telefono');
				$datos['us_email'] = $this->input->post('email');
				$datos['co_codigo'] = $this->input->post('comuna');

				$this->objUsuario->actualizar($datos,array("us_codigo"=>$this->input->post('codigo')));

				echo json_encode(array("result"=>true));

			}
		}
		else{
			if(!$codigo) redirect("/usuarios/");
			#js
			$this->layout->js('/js/sistema/usuarios/editar.js');

			#title
			$this->layout->title('Editar Administrador');

			#metas
			$this->layout->setMeta('title','Editar Administrador');
			$this->layout->setMeta('description','Editar Administrador');
			$this->layout->setMeta('keywords','Editar Administrador');

			#JS - Multiple select boxes
			$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
			$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');

			#contenido
			if($contenido['usuario'] = $this->objUsuario->obtener(array("us_codigo" => $codigo)));
			else show_error('');
			$contenido["comunas"] = $this->objComuna->listar();
			#nav
			$this->layout->nav(array("Usuarios "=>"usuarios", "Editar Usuarios" =>"/"));

			$this->layout->view('editar',$contenido);

		}
	}

	public function eliminar(){

		try{
			$this->objUsuario->actualizar(array("us_visible"=>0),array("us_codigo"=>$this->input->post('codigo')));
			echo json_encode(array("result"=>true));
		}
		catch(Exception $e){
			echo json_encode(array("result"=>false,"msg"=>"Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente."));
		}
	}

	public function estados(){

		try{
			list($codigo,$estado) = explode('-',$this->input->post('codigo'));
			$this->objUsuario->actualizar(array("us_estado"=>$estado),array("us_codigo"=>$codigo));
			echo json_encode(array("result"=>true));
		}
		catch(Exception $e){
			echo json_encode(array("result"=>false,"msg"=>"Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente."));
		}
	}
	
	/*public function regiones(){
		if($this->input->post()){
			if($regiones = $this->objRegion->listar(array("pa_codigo" => $this->input->post("pais")))){
				$html = "<option disabled selected>Seleccione</option>";
				foreach($regiones as $region){
					$html.= "<option value='" . $region->codigo . "'>" . $region->nombre . "</option>";
				}
				echo json_encode(array("result"=>true,"html"=>$html));
			}
		}else{
			redirect("/usuarios/");
		}
	}*/
}
