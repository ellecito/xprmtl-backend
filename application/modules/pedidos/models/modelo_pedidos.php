<?php
class Modelo_pedidos extends CI_Model {
	private $tabla;
	private $prefijo;
	
	function __construct(){
		$this->tabla = "pedidos";
		$this->prefijo = substr($this->tabla, 0, 2) . "_";
		$this->load->model("modelo_estados", "objEstado");
		$this->load->model("modelo_bitacora", "objBitacora");
		$this->load->model("usuarios/modelo_usuarios", "objUsuario");
		parent::__construct();
	}
	
	public function getLastId(){
		$this->db->select_max("{$this->prefijo}codigo","maximo");
		$sql = $this->db->get($this->tabla);
		return $sql->row()->maximo+1;
	}
	
	public function insertar($datos){
		return $this->db->insert($this->tabla, $datos);
	}
	
	public function actualizar($datos, $where){
		$this->db->where($where);
		return $this->db->update($this->tabla, $datos);
	}
	
	public function total($where = false){
		if($where) $this->db->where($where);
		return $this->db->count_all_results($this->tabla);
	}
	
	public function obtener($where){
		$sql = $this->db->select('*')
				->from($this->tabla)
				->where($where)
				->limit(1)
				->get();
				
        $resultado = $sql->row();
		
        if($resultado){
			$obj = new stdClass();
			foreach(get_object_vars($resultado) as $key => $val){
				$obj->{str_replace($this->prefijo, "", $key)} = $resultado->{$key};
			}
			$obj->estado = $this->objEstado->obtener(array("ps_codigo" => $obj->ps_codigo));
			unset($obj->ps_codigo);
			$obj->usuario = $this->objUsuario->obtener(array("us_codigo" => $obj->us_codigo));
			unset($obj->us_codigo);
			$obj->bitacora = $this->objBitacora->obtener(array("bw_orden_de_compra" => $obj->codigo));
			return $obj;
        }else{
			return false;
        }
	}
	
	public function listar($cantidad = false, $pagina = false, $where = false){
		
		if($where) $this->db->where($where);
		if($cantidad and !$pagina) $this->db->limit($cantidad);
		if($cantidad and $pagina){
			$desde = ($pagina - 1) * $cantidad;
			$this->db->limit($cantidad, $desde);
		} 
		
		$sql = $this->db->select('*')
				->from($this->tabla)
				->order_by("{$this->prefijo}fecha", "DESC")
				->order_by("{$this->prefijo}hora", "DESC")
				->get();
				
        $result = $sql->result();
        if($result){
			$listado = array();
			foreach($result as $resultado){
				$obj = new stdClass();
				foreach(get_object_vars($resultado) as $key => $val){
					$obj->{str_replace($this->prefijo, "", $key)} = $resultado->{$key};
				}
				$obj->estado = $this->objEstado->obtener(array("ps_codigo" => $obj->ps_codigo));
				unset($obj->ps_codigo);
				$obj->usuario = $this->objUsuario->obtener(array("us_codigo" => $obj->us_codigo));
				unset($obj->us_codigo);
				$listado[] = $obj;
			}
			return $listado;
        }else {
			return false;
        }
    }
}