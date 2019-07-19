<?php
App::uses('AppController', 'Controller');
/**
 * Promociones Controller
 *
 * @property Promocion $Promocion
 * @property PaginatorComponent $Paginator
 */
class PromocionesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function migracion_promociones(){
	    ini_set("memory_limit","256M");
	    ini_set('max_execution_time', 1200);
	    
	    $this->loadModel('Producto');
	    
	    $url = "http://ws.deltron.com.pe/xtranet/ecommerce/servicejson/itemRequest.json.php?CodAuthenticate=100011&ServiceName=itemPromo&page=1&sizePag=5500";
	    
	    pr($url);
	    
	    $ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	    $contents = curl_exec($ch);
	    
	    if (curl_errno($ch)) {
	        $contents = curl_error($ch);
	        pr($contents);
	    } else {
	        curl_close($ch);
	    }
	    
	    if (!is_string($contents) || !strlen($contents)) {
	        echo "Failed to get contents.";
	        $contents = '';
	    }
	    
	    $contents = json_decode($contents, true);
	    
	    if(isset($contents[0]['error'])){
	        pr($contents);
	        exit;
	    }
	    
	    $a_Items = $contents[0]['Items'];
	    //pr($a_Items);
	    foreach($a_Items AS $codigoItem => $a_Item){
	       
	        $condicion = array('conditions' => array('Producto.codigo' => $a_Item['Sku']),
	                           'recursive' => -1);
	        $Producto = $this->Producto->find('first',$condicion);
	        
	        
	        if(!empty($Producto)){
	            pr($a_Item);
	            pr($Producto);
	            
	            $condicion1 = array('conditions' => array('Promocion.producto_id' => $Producto['Producto']['id'])
	                               ,'recursive' => -1);
	            $Promocion = $this->Promocion->find('first',$condicion1);
	            
	            if (empty($Promocion)){
	                $Promocion['Promocion']['producto_id'] = $Producto['Producto']['id'];
	                $Promocion['Promocion']['nombre']      = $Producto['Producto']['nombre'];
	                $Promocion['Promocion']['precio']      = ($a_Item['SpecialPrice']/1.15/1.18);
	                $Promocion['Promocion']['descripcion'] = $Producto['Producto']['descripcion'];
	                $Promocion['Promocion']['fecha_inicio'] = $a_Item['FecIni'].' 00:00:00';
	                $Promocion['Promocion']['fecha_fin'] = $a_Item['FecFin'].' 23:59:59';
	                 
	                pr($Promocion);
	                $this->Promocion->create();
	                if (!$this->Promocion->save($Promocion)) {
	                    echo var_dump($this->Promocion->invalidFields());
	                    echo 'error Producto';
	                }
	            }
	        }
	    }
	    //pr($contents);
	    exit;
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Promocion->recursive = 0;
		//Si se busca campo displayField del modelo
		$conditions = array('conditions' => array('Promocion.fecha_fin >='=>date("Y-m-d H:i:s")));
		$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		$campo = !empty($this->Promocion->displayField)?$this->Promocion->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['Promocion'][$campo] = $nombre ;
			$conditions = array('conditions' => array('Promocion.fecha_fin >='=>date("Y-m-d H:i:s"), 'Producto.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$promociones = $this->Paginator->paginate();
		//pr($promociones);
		$this->set('promociones', $promociones);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Promocion->exists($id)) {
			throw new NotFoundException(__('Invalido(a) promocion'));
		}
		$options = array('conditions' => array('Promocion.' . $this->Promocion->primaryKey => $id));
		$this->set('promocion', $this->Promocion->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Promocion->create();
			if ($this->Promocion->save($this->request->data)) {
				$this->Flash->success(__('El/la promocion se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la promocion. Por favor, int�ntelo de nuevo.'));
			}
		}
		$params = array('fields' => array('id','nombre'), 'conditions' => array('Producto.stock >=' => 1), 'order' =>'Producto.nombre', 'recursive' => -1);
		$productos = $this->Promocion->Producto->find('list',$params);
		$this->set(compact('productos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Promocion->exists($id)) {
			throw new NotFoundException(__('Invalido(a) promocion'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Promocion->save($this->request->data)) {
				$this->Flash->success(__('El/la promocion se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la promocion. Por favor, int�ntelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Promocion.' . $this->Promocion->primaryKey => $id));
			$this->request->data = $this->Promocion->find('first', $options);
		}
		$params = array('fields' => array('id','nombre'), 'conditions' => array('Producto.stock >=' => 1), 'order' =>'Producto.nombre', 'recursive' => -1);
		$productos = $this->Promocion->Producto->find('list',$params);
		$this->set(compact('productos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Promocion->id = $id;
		if (!$this->Promocion->exists()) {
			throw new NotFoundException(__('Invalid promocion'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Promocion->delete()) {
			$this->Flash->success(__('El/la promocion ha sido borrado.'));
		} else {
			$this->Flash->error(__('El/la promocion no ha sido borrado. Por favor int�ntelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
