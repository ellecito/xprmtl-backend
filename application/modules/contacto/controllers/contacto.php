<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Contacto extends CI_Controller {
	
	function __construct(){
	
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("modelo_contacto", "objContacto");
		#current
		$this->layout->current = 5;
	}
	
	public function index(){
		
		#Title
		$this->layout->title('Contacto');
		
		#Metas
		$this->layout->setMeta('title','Contacto');
		$this->layout->setMeta('description','Contacto');
		$this->layout->setMeta('keywords','Contacto');
		
		$where = $and = $contenido['q_f'] = '';
		if($this->input->get('q')){
			$contenido['q_f'] = $q = $this->input->get('q');
			$where .= "CONCAT(cn_nombres, ' ', cn_apellidos) like '%$q%' or cn_email like '%$q%'  ";
		}

		#url
		$url = explode('?',$_SERVER['REQUEST_URI']);
		if(isset($url[1]))
		$contenido['url'] = $url = '/?'.$url[1];
		else
		$contenido['url'] = $url = '/';

		#paginacion
		$config['base_url'] = '/contacto/';
		$config['total_rows'] = count($this->objContacto->listar($where));
		$config['per_page'] = 15;
		$config['uri_segment'] = $segment = 2;
		$config['suffix'] = $url;
		$config['first_url'] = '/contacto'.$url;

		$this->pagination->initialize($config);
		$page = ($this->uri->segment($segment))?$this->uri->segment($segment)-1:0;

		#contenido
		$contenido['contactos'] = $this->objContacto->listar($where);
		$contenido['pagination'] = $this->pagination->create_links();
		
		#La vista siempre,  debe ir cargada al final de la función
		$this->layout->view('index', $contenido);
	}

	public function ver_contacto($codigo)
	{

		$contacto = $this->objContacto->obtener(array("cn_codigo" => $codigo));
		if(!$contacto) redirect('/contacto/');

		#title
		$this->layout->title('Respaldo Contactos');

		#metas
		$this->layout->setMeta('title','Respaldo Contactos');
		$this->layout->setMeta('description','Respaldo Contactos');
		$this->layout->setMeta('keywords','Respaldo Contactos');

		#JS - Datepicker
		$this->layout->css('/js/jquery/ui/1.10.4/ui-lightness/jquery-ui-1.10.4.custom.min.css');
		$this->layout->js('/js/jquery/ui/1.10.4/jquery-ui-1.10.4.custom.min.js');
		$this->layout->js('/js/jquery/ui/1.10.4/jquery.ui.datepicker-es.js');

		#JS - Multiple select boxes
		$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
		$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');

		#JS - Editor
		$this->layout->js('/js/jquery/ckeditor-standard/ckeditor.js');

		#JS - Formulario
		$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
		$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');

		#nav
		$this->layout->nav(array("Contacto"=>"contacto", "Ver Contacto" =>"/"));

		$contenido['contacto'] = $contacto;

		$this->layout->view('ver_contacto', $contenido);

	}
	
	public function exportar_excel(){
		

		#libreria PHPExcel en libraries
		require APPPATH."libraries/PHPExcel/PHPExcel.php";
		
		$and = $where = '';
		if($this->input->get('q')){
			$q = $this->input->get('q');
			$where .= "CONCAT(cn_nombres, ' ', cn_apellidos) like '%$q%' or cn_email = '$q'";
			$and = ' and ';
		}
		
		$contactos = $this->objContacto->listar($where);
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->
			getProperties()
				->setCreator("XRPMTL.cl")
				->setLastModifiedBy("XRPMTL.cl")
				->setTitle("Excel Respaldo Contactos")
				->setSubject("Excel Respaldo Contactos")
				->setDescription("Excel Respaldo Contactos")
				->setKeywords("Excel Respaldo Contactos")
				->setCategory("reportes");


		$styleArray = array(
			   'borders' => array(
					 'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => '000000'),
					 ),
			   ),
			    'font'    => array(
				 'bold'      => true,
				 'italic'    => false,
				 'strike'    => false,
			 ),
			'alignment' => array(
					'wrap'       => true,
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'BABDBB')
			),
		);
		
		$styleArraInfo = array(
				'font'    => array(
				 'bold'      => false,
				 'italic'    => false,
				 'strike'    => false,
				 'size' => 10
				 ),
				 'borders' => array(
					 'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => '000000'),
					 ),
			   ),
			   'alignment' => array(
					'wrap'       => true,
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		);
		
		
		$styleFont = array(
			 'font'    => array(
				 'bold'      => true,
				 'italic'    => false,
				 'strike'    => false,
			 ),
			'alignment' => array(
					'wrap'       => true,
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
		);

		$objPHPExcel->getActiveSheet()->getStyle('1:3')->applyFromArray($styleFont);
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Respaldo Contactos');
		
		$i=2;
		$letra = 'A';
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Nombre');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Apellidos');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Email');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Teléfono');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Fecha');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Hora');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(50);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Mensaje');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$i++;
		foreach($contactos as $aux){

			$letra = "A";
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $aux->nombres);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $aux->apellidos);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $aux->email);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $aux->telefono);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, formatearFecha(substr($aux->fecha,0,10)));
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, substr($aux->fecha,11,5));
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $aux->mensaje);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$i++;

		}

		$objPHPExcel->getActiveSheet()->setTitle("Respaldo Contactos ".date("d-m-Y"));

		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Respaldo Contactos - '.date('d/m/Y').'.xls"');
		header('Cache-Control: max-age=0');
		 
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$objWriter->save('php://output');
		exit;
		
	}
	
}