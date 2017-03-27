<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Productos extends CI_Controller {
	public $medidas;
	function __construct(){
	
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("modelo_productos", "objProducto");
		$this->load->model("modelo_imagenes", "objImagen");
		$this->load->model("categorias/modelo_categorias", "objCategoria");
		#current
		$this->layout->current = 1;
		#configura las medidas de las imagenes
        $this->medidas = new stdClass();
        $this->medidas->ancho_min = 400*2;
        $this->medidas->alto_min = 370*2;
        
        $this->medidas->ancho_max = $this->medidas->ancho_min*4;
        $this->medidas->alto_max = $this->medidas->alto_min*4;
	}
	
	public function index($pagina = 1){
		#title
		$this->layout->title('Productos');
		#metas
		$this->layout->setMeta('title','Productos');
		$this->layout->setMeta('description','Productos');
		$this->layout->setMeta('keywords','Productos');
		
		#JS - pagination
		$this->layout->js('/js/jquery/rpage-master/responsive-paginate.js');
		$this->layout->js('/js/jquery/rpage-master/paginate-init.js');
		#JS - Estados
		$this->layout->js('/js/sistema/productos/index.js');
		#nav
		$this->layout->nav(array("Productos"=>"/"));
		
		#filtros
		$where = false;
		$contenido["buscar_get"] = '';
		if($this->input->get("buscar")){
			$where = "pr_nombre LIKE '%" . $this->input->get("buscar") . "%'";
			$contenido["buscar_get"] = $this->input->get("buscar");
		}
		
		#Paginación
		$this->load->library('pagination');
		$config['uri_segment'] = 2;
		$config['base_url'] = base_url().'/productos/';
		$config['per_page'] = 15;
		$config['total_rows'] = $this->objProducto->total($where);
		$this->pagination->initialize($config);
		
		$contenido['productos'] = $this->objProducto->listar($config['per_page'], $pagina, $where);
		#La vista siempre debe ir cargada al final de la función
		$this->layout->view('index', $contenido);
	}

	public function agregar(){
		if($this->input->post()){
			#validacion
			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('precio','Precio','required');
			$this->form_validation->set_rules('resumen','Resumen','required');
			$this->form_validation->set_rules('descripcion','Descripción','required');
			$this->form_validation->set_rules('esp_tecnicas','Especificaciones Técnicas','required');
			$this->form_validation->set_rules('categoria','Categoría','required');
			
			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_message('valid_email', '* El email no es válido');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}
			
			$imagenes = array_filter($this->input->post("ruta_interna"));
			if(!$imagenes){
				echo json_encode(array("result"=>false,"msg"=>"<div>Es necesario al menos una imagen.</div>"));
				exit;
			}
			else
			{
				try{
					$insert = array(
						"pr_codigo" => $this->objProducto->getLastId(),
						"pr_estado" => 1,
						"pr_visible" => 1,
						"pr_destacado" => 0,
						"pr_nombre" => $this->input->post("nombre"),
						"pr_url" => slug($this->input->post("nombre")),
						"pr_precio" => $this->input->post("precio"),
						"pr_resumen" => $this->input->post("resumen"),
						"pr_descripcion" => $this->input->post("descripcion"),
						"pr_especificaciones_tecnicas" => $this->input->post("esp_tecnicas"),
						"ca_codigo" => $this->input->post("categoria")
					);
					if($this->objProducto->insertar($insert)){
						foreach($imagenes as $imagen){
							$imagen_d = array(
								"pi_codigo" => $this->objImagen->getLastId(),
								"pr_codigo" => $insert["pr_codigo"],
								"pi_imagen" => $imagen,
							);
							$this->objImagen->insertar($imagen_d);
						}
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
			$this->layout->title('Agregar Producto');
			
			#metas
			$this->layout->setMeta('title','Agregar Producto');
			$this->layout->setMeta('description','Agregar Producto');
			$this->layout->setMeta('keywords','Agregar Producto');
			
			#JS - Editor
			$this->layout->js('/js/jquery/ckeditor-standard/ckeditor.js');
			
			#JS - Formulario
			$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
			$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');
			
			#JS - Multiple select boxes
			$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
			$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');
			
			#croppy
			$this->layout->js('/js/jquery/croppic/croppic.js');
			$this->layout->css('/js/jquery/croppic/croppic.css');
			
			$this->layout->js('/js/sistema/productos/agregar.js');
			
			#nav
			$this->layout->nav(array("Productos"=>"productos", "Agregar producto" =>"/"));
			
			$contenido["categorias"] = $this->objCategoria->listar();
			
			$this->layout->view('agregar', $contenido);
		}
	}
	
	public function editar($producto = FALSE){
		if($this->input->post()){
			#validacion
			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('precio','Precio','required');
			$this->form_validation->set_rules('resumen','Resumen','required');
			$this->form_validation->set_rules('descripcion','Descripción','required');
			$this->form_validation->set_rules('esp_tecnicas','Especificaciones Técnicas','required');
			$this->form_validation->set_rules('categoria','Categoría','required');
			
			$this->form_validation->set_message('required', '* %s es obligatorio');
			$this->form_validation->set_message('valid_email', '* El email no es válido');
			$this->form_validation->set_error_delimiters('<div>','</div>');

			if(!$this->form_validation->run()){
				echo json_encode(array("result"=>false,"msg"=>validation_errors()));
				exit;
			}
			
			$imagenes = array_filter($this->input->post("ruta_interna"));
			if(!$imagenes){
				echo json_encode(array("result"=>false,"msg"=>"<div>Es necesario al menos una imagen.</div>"));
				exit;
			}else
			{
				try{
					$datos = array(
						"pr_nombre" => $this->input->post("nombre"),
						"pr_url" => slug($this->input->post("nombre")),
						"pr_precio" => $this->input->post("precio"),
						"pr_resumen" => $this->input->post("resumen"),
						"pr_descripcion" => $this->input->post("descripcion"),
						"pr_especificaciones_tecnicas" => $this->input->post("esp_tecnicas"),
						"ca_codigo" => $this->input->post("categoria")
					);
					$where = array(
						"pr_codigo" => $this->input->post("codigo")
					);
					if($this->objProducto->actualizar($datos,$where)){
						$this->objImagen->eliminar(array("pr_codigo" => $this->input->post("codigo")));
						foreach($imagenes as $imagen){
							$imagen_d = array(
								"pi_codigo" => $this->objImagen->getLastId(),
								"pr_codigo" => $this->input->post("codigo"),
								"pi_imagen" => $imagen,
							);
							$this->objImagen->insertar($imagen_d);
						}
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
			if(!$producto) redirect("/productos/");
			$contenido["producto"] = $this->objProducto->obtener(array("pr_codigo" => $producto));
			if(!$contenido["producto"]) redirect("/productos/");
			//die(print_array($contenido["producto"]));
			
			#title
			$this->layout->title('Editar producto');
			
			#metas
			$this->layout->setMeta('title','Editar producto');
			$this->layout->setMeta('description','Editar producto');
			$this->layout->setMeta('keywords','Editar producto');
			
			#JS - Editor
			$this->layout->js('/js/jquery/ckeditor-standard/ckeditor.js');
			
			#JS - Formulario
			$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
			$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');
			
			#JS - Multiple select boxes
			$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
			$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');
			
			#croppy
			$this->layout->js('/js/jquery/croppic/croppic.js');
			$this->layout->css('/js/jquery/croppic/croppic.css');
			
			$this->layout->js('/js/sistema/productos/editar.js');
			
			#nav
			$this->layout->nav(array("Productos"=>"productos", "Editar producto" =>"/"));
			
			$contenido["categorias"] = $this->objCategoria->listar();
			$this->layout->view('editar', $contenido);
		}
	}
	
	public function eliminar(){
		if($this->input->post()){
			if($this->objProducto->actualizar(array("pr_visible"=>0), array("pr_codigo" => $this->input->post("codigo")))){
				echo json_encode(array("result"=>true));
			}else{
				echo json_encode(array("result"=>false));
			}
		}else{
			redirect("/productos/");
		}
	}
	
	public function estados(){
        if($codigo = $this->input->post('codigo')){
            list($codigo,$estado) = explode('-',$codigo);
            
            $this->objProducto->actualizar(array("pr_estado"=>$estado),array("pr_codigo"=>$codigo));
            
            echo json_encode(array("result"=>true));
        }
        else
            redirect("/productos/");
    }
	
	public function destacado(){
        if($codigo = $this->input->post('codigo')){
            list($codigo,$estado) = explode('-',$codigo);
            
            $this->objProducto->actualizar(array("pr_destacado"=>$estado),array("pr_codigo"=>$codigo));
            
            echo json_encode(array("result"=>true));
        }
        else
            redirect("/productos/");
    }
	
	###IMAGENES
	public function cargar_imagen(){
	
		if($_FILES['img']['name'] != ''){
			
			require APPPATH."libraries/upload-img/upload.php";
            
			$uploads_dir = '/imagenes/';
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$uploads_dir))
				mkdir($_SERVER['DOCUMENT_ROOT'].$uploads_dir,0777);
            
            $uploads_dir .= 'productos/';
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$uploads_dir))
				mkdir($_SERVER['DOCUMENT_ROOT'].$uploads_dir,0777);
    
            $dir = $uploads_dir;
            $uploads_dir = $_SERVER['DOCUMENT_ROOT'].$uploads_dir;
            
			$foto_name = 'grande_'.time();
			$nombre_foto = $_FILES['img']['name'];
			$extension = strtolower((array_pop(explode(".",$nombre_foto))));
			$permitidas = array("jpg","gif","png","jpeg"); #extensiones permitidas
			list($ancho,$alto)=getimagesize($_FILES['img']['tmp_name']);
			if(in_array($extension,$permitidas))
			{
				if($ancho >= $this->medidas->ancho_min and $alto >= $this->medidas->alto_min){
					$foo = new Upload($_FILES['img']['tmp_name']);
					if ($foo->uploaded) {
						$imagen = $foto_name;
						$foo->file_new_name_body = $imagen;
						$foo->image_resize = true;
						if($ancho > $alto){
							if($ancho > $this->medidas->ancho_max)
								$foo->image_x = $this->medidas->ancho_max;
							else
								$foo->image_x = $ancho;
							$foo->image_ratio_y = true;
						}
						else{
							if($alto > $this->medidas->alto_max)
								$foo->image_y = $this->medidas->alto_max;
							else
								$foo->image_y = $alto;
							$foo->image_ratio_x = true;
						}
						$foo->Process($uploads_dir);
						list($ancho, $alto, $t, $a) = getimagesize($uploads_dir.$foto_name.'.'.$extension);
						$response = array(
							"status" => 'success',
							"url" => $dir.$foto_name.'.'.$extension,
							"width" => $ancho,
							"height" => $alto
						);
						rename($uploads_dir.$foto_name.'.txt', $uploads_dir.$foto_name.'.'.$extension);
					}
					else{
						$response = array("status"=>'error',"message"=>'<b>Problemas al subir la imagen, por favor inténtelo nuevamente. </b>');
					}
				}
				else{
					$response = array("status"=>'error',"message"=>'<b>La imagen debe tener un tamaño minimo de '.$this->medidas->ancho_min.' x '.$this->medidas->alto_min . 'px. </b>');
				}
			}
			else{
				$response = array("status"=>'error',"message"=>'<b>La imagen debe ser jpg, jpeg, png o gif.</b>');
			}
		}
		else
			$response = array("status"=>'error',"message"=>'<b>Debe cargar una imagen. </b>');
		
		echo json_encode($response);
		
	}
	
	public function cortar_imagen(){

		#IMAGEN TAMAÑO INTERNA
		$grande = $imgUrl = $_POST['imgUrl'];
		
        #los tamaños se multiplican por 4
        #ya que a esa proporcion estan las imagenes al cortarla
        
		// original sizes
		$imgInitW = $_POST['imgInitW'];
		$imgInitH = $_POST['imgInitH'];
		
		// resized sizes
		$imgW = $_POST['imgW'];
		$imgH = $_POST['imgH'];

		// offsets
		$imgY1 = $_POST['imgY1'];
		$imgX1 = $_POST['imgX1'];

		// crop box
		$cropW = ($_POST['cropW'] + 2);
		$cropH = ($_POST['cropH'] + 2);

		// rotation angle
		$angle = $_POST['rotation'];

		$jpeg_quality = 100;

		$uploads_dir = '/imagenes/productos/';
		$output_filename = $uploads_dir.'galeria_'.time();
		
		$imgUrl = $_SERVER['DOCUMENT_ROOT'].$imgUrl;
		$what = getimagesize($imgUrl);

		switch(strtolower($what['mime']))
		{
			case 'image/png':
				$img_r = imagecreatefrompng($imgUrl);
				$source_image = imagecreatefrompng($imgUrl);
				$type = '.png';
				break;
			case 'image/jpeg':
				$img_r = imagecreatefromjpeg($imgUrl);
				$source_image = imagecreatefromjpeg($imgUrl);
				error_log("jpg");
				$type = '.jpg';
				break;
			case 'image/gif':
				$img_r = imagecreatefromgif($imgUrl);
				$source_image = imagecreatefromgif($imgUrl);
				$type = '.gif';
				break;
			default: die('Tipo de imagen no soportado');
		}

		$resizedImage = imagecreatetruecolor($imgW, $imgH);
		imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
		$rotated_image = imagerotate($resizedImage, -$angle, 0);
		$rotated_width = imagesx($rotated_image);
		$rotated_height = imagesy($rotated_image);
		$dx = $rotated_width - $imgW;
		$dy = $rotated_height - $imgH;
		$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
		imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
		imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
		$final_image = imagecreatetruecolor($cropW, $cropH);
		imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
		imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
		imagejpeg($final_image, $_SERVER['DOCUMENT_ROOT'].$output_filename.$type, $jpeg_quality);
		$ruta_galeria = $output_filename.$type;
        
        #elimina la imagen original, ya que no se usa
        unlink($_SERVER['DOCUMENT_ROOT'].$grande);
        
		$response = Array("status"=>'success',"url"=>$output_filename.$type, "ruta_grande"=>$grande);
		echo json_encode($response);
	}
    
    public function eliminar_imagen(){
        if($ruta = $this->input->post('ruta_imagen')){
            if(file_exists($_SERVER['DOCUMENT_ROOT'].$ruta))
                unlink($_SERVER['DOCUMENT_ROOT'].$ruta);
        }
        
        if($codigo = $this->input->post('codigo')){
            if($imagen = $this->objImagen->obtener(array("pi_codigo" => $codigo))){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$imagen->imagen))
                    unlink($_SERVER['DOCUMENT_ROOT']. $imagen->imagen);
                    
                $this->objImagen->eliminar(array("pi_codigo" => $codigo));
            }
        }
        
        echo json_encode(array("result"=>true));
    }
	
}