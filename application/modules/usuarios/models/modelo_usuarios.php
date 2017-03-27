<?php
class Modelo_usuarios extends CI_Model {
	private $tabla;
	private $prefijo;
	
	function __construct(){
		$this->tabla = "usuario";
		$this->prefijo = substr($this->tabla, 0, 2) . "_";
		$this->load->model("usuarios/modelo_comuna", "objComuna");
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
	
	public function obtener($where){
		$sql = $this->db->select('*')
				->from($this->tabla)
				->where("{$this->prefijo}visible", 1)
				->where($where)
				->limit(1)
				->get();
				
        $resultado = $sql->row();
		
        if($resultado){
			$obj = new stdClass();
			foreach(get_object_vars($resultado) as $key => $val){
				if($key == "co_codigo") $obj->comuna = $resultado->{$key};
				else $obj->{str_replace($this->prefijo, "", $key)} = $resultado->{$key};
			}
			$obj->comuna = $this->objComuna->obtener(array("co_codigo" => $obj->comuna));
			return $obj;
        }else{
			return false;
        }
	}
	
	public function listar($where = false){
		if($where) $this->db->where($where);
		$sql = $this->db->select('*')
				->from($this->tabla)
				->where("{$this->prefijo}visible", 1)
				->get();
				
        $result = $sql->result();
        if($result){
			$listado = array();
			foreach($result as $resultado){
				$obj = new stdClass();
				foreach(get_object_vars($resultado) as $key => $val){
					if($key == "co_codigo") $obj->comuna = $resultado->{$key};
					else $obj->{str_replace($this->prefijo, "", $key)} = $resultado->{$key};
				}
				$listado[] = $obj;
			}
			return $listado;
        }else {
			return false;
        }
    }
}