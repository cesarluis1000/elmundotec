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

    public function migracion(){
        $this->layout = false;
	    $this->autoRender = false;
	    ini_set("memory_limit","256M");
	    ini_set('max_execution_time', 0);
	    
	    $this->loadModel('Producto');
	    
	    $url = "http://ws.deltron.com.pe/xtranet/ecommerce/servicejson/itemRequest.json.php?CodAuthenticate=100011&ServiceName=itemPromoByCustomer&page=1&sizePag=6000";
	    //pr($url);
	    
	    $ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	    $json = curl_exec($ch);
	    $this->response->body($json);
    }
    
    public function migracion_promociones($s_codigoItem = null){
        $this->layout = false;
	    $this->autoRender = false;
	    ini_set("memory_limit","256M");
	    ini_set('max_execution_time', 0);
	    
	    $this->loadModel('Producto');
	    
	    //$url = "https://www.elmundotec.com/Promociones/migracion";
	    $url = "http://ws.deltron.com.pe/xtranet/ecommerce/servicejson/itemRequest.json.php?CodAuthenticate=100011&ServiceName=itemPromoByCustomer&page=1&sizePag=6000";
	    //pr($url);
	    
	    $ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	    $contents = curl_exec($ch);
	    //$this->response->body($contents);
	    
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
	    pr(count($a_Items));
	    //pr($a_Items);
	    //exit;
	    if (isset($s_codigoItem) && !empty($s_codigoItem)){
	        $i = 1;
	        pr($s_codigoItem);
	        //pr($a_Items);
	        foreach($a_Items AS $codigoItem => $a_Item){
	            // pr($a_Item['Sku']);
	            if($s_codigoItem == $a_Item['IdProducto']  && !empty($a_Item['Name'])){
	                pr($i);
	                pr($a_Item);
	                $condicion = array('conditions' => array('Producto.codigo' => $a_Item['IdProducto']),
	                    'recursive' => -1);
	                $Producto = $this->Producto->find('first',$condicion);
	                
	                $condicion1 = array('conditions' => array('Promocion.producto_id' => $Producto['Producto']['id'])
	                    ,'recursive' => -1);
	                $Promocion = $this->Promocion->find('first',$condicion1);
	                pr($Promocion);
	                pr($Producto);
	                break;
	            }
	            $i++;
	        }
	        exit;
	    }	    
	    
	    $hoy = date("Y-m-d H:i:s");
	    $a_codigoProducto = array();
	    foreach($a_Items AS $codigoItem => $a_Item){
	        $a_codigoProducto[] = $a_Item['IdProducto'];
	        $condicion = array('conditions' => array('Producto.codigo' => $a_Item['IdProducto']),'recursive' => -1);
	        $Producto = $this->Producto->find('first',$condicion);
	        
	        pr($a_Item);
            //pr($Producto);	 
	        if(!empty($Producto) && !empty($a_Item['Name']) && $Producto['Producto']['stock'] > 0 && $hoy < $a_Item['FecFin']){
	            $condicion1 = array('conditions' => array('Promocion.producto_id' => $Producto['Producto']['id'], 'Promocion.estado' => 'A'),'recursive' => -1);
	            $Promocion = $this->Promocion->find('first',$condicion1);	            
	            //pr($Promocion);
	            if (empty($Promocion) && !empty($a_Item['Name'])){
	                //exit;
	                $Promocion['Promocion']['producto_id']  = $Producto['Producto']['id'];
	                $Promocion['Promocion']['nombre']       = $Producto['Producto']['nombre'];
	                $Promocion['Promocion']['precio']       = $a_Item['PriceCostNew'];
	                $Promocion['Promocion']['descripcion']  = $Producto['Producto']['descripcion'];
	                $Promocion['Promocion']['fecha_inicio'] = $a_Item['FecIni'];
	                $Promocion['Promocion']['fecha_fin']    = $a_Item['FecFin'];	                
	                
	                //pr($a_Item);
	                pr($Producto);
	                pr($Promocion);	                
    	            $this->Promocion->create();
    	            if (!$this->Promocion->save($Promocion)) {    	             
    	                echo 'error Promocion';
    	                pr($this->Promocion->validationErrors);
    	                $logs = $this->Promocion->getDataSource()->getLog(false, false);
    	                $lastLog = end($logs['log']);    	                
    	            }    	            
	            }
	        }
	    }
	    //pr($a_codigoProducto);
	    $condicion = array('fields' => array('id'),
	                       'conditions' => array('Producto.codigo' => $a_codigoProducto),
	                       'recursive' => -1);
	    $a_productos = $this->Producto->find('all',$condicion);
	    $a_productoId = Set::classicExtract($a_productos, '{n}.Producto.id');
	    //pr($a_productoId);	    
	    
	    $condicion2 = array('conditions' => array('Promocion.producto_id !=' => $a_productoId, 'Promocion.estado' => 'A', 'Promocion.fecha_fin >=' => $hoy),'recursive' => -1);
	    $a_promociones = $this->Promocion->find('all',$condicion2);
	    $a_promocionId = Set::classicExtract($a_promociones, '{n}.Promocion.id');
	    
	    if (!empty($a_promocionId)){
	        pr($a_promocionId);
	        if (!$this->Promocion->updateAll(array('Promocion.estado' => "'D'"), array('Promocion.id' =>$a_promocionId))) {
	            echo 'error Promocion estado desactivo';
	            pr($this->Promocion->validationErrors);
	        }
	    }
	    //pr($a_Items);
	    $this->response->body();	    
	    
    }
    
	public function migracion2($s_codigoItem = null){
	    $this->layout = false;
	    $this->autoRender = false;
	    ini_set("memory_limit","256M");
	    ini_set('max_execution_time', 0);
	    
	    $this->loadModel('Producto');
	    
	    $url = "http://ws.deltron.com.pe/xtranet/ecommerce/servicejson/itemRequest.json.php?CodAuthenticate=100011&ServiceName=itemPromoByCustomer&page=1&sizePag=6000";
	    
	    //pr($url);
	    
	    $ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	    $contents = curl_exec($ch);
	    
        pr($contents);exit;
        
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
	    if (isset($s_codigoItem) && !empty($s_codigoItem)){
    	    $i = 1;
    	    pr($s_codigoItem);
    	    //pr($a_Items);
    	    foreach($a_Items AS $codigoItem => $a_Item){
    	       // pr($a_Item['Sku']);
    	       if($s_codigoItem == $a_Item['IdProducto']  && !empty($a_Item['Name'])){
    	           pr($i);
    	           pr($a_Item);
    	           $condicion = array('conditions' => array('Producto.codigo' => $a_Item['IdProducto']),
	                                                        'recursive' => -1);
	               $Producto = $this->Producto->find('first',$condicion);
	               
	               $condicion1 = array('conditions' => array('Promocion.producto_id' => $Producto['Producto']['id'])
	                               ,'recursive' => -1);
	               $Promocion = $this->Promocion->find('first',$condicion1);
	               pr($Promocion);
    	           pr($Producto);
    	           break;
    	       }
    	       $i++;
    	    }	
    	    exit;
	    }
        exit;
	    //pr($a_Items);
	    $hoy = date("Y-m-d H:i:s");
	    foreach($a_Items AS $codigoItem => $a_Item){
	       
	        $condicion = array('conditions' => array('Producto.codigo' => $a_Item['IdProducto']),
	                           'recursive' => -1);
	        $Producto = $this->Producto->find('first',$condicion);
	        
	        //
	        //&& $s_codigoItem == $a_Item['IdProducto'] 
	        if(!empty($Producto) && !empty($a_Item['Name']) && $a_Item['Status'] == 'A' && $hoy < $a_Item['FecFin']){
	            $condicion1 = array('conditions' => array('Promocion.producto_id' => $Producto['Producto']['id'], 'Promocion.estado' => 'A')
	                               ,'recursive' => -1);
	            $Promocion = $this->Promocion->find('first',$condicion1);
	            
	            pr($a_Item);
	            pr($Promocion);
	            
	            if (empty($Promocion) && !empty($a_Item['Name'])){
	                //exit;
	                $Promocion['Promocion']['producto_id']  = $Producto['Producto']['id'];
	                $Promocion['Promocion']['nombre']       = $Producto['Producto']['nombre'];
	                $Promocion['Promocion']['precio']       = ($a_Item['SpecialPrice']/1.13/1.18);
	                $Promocion['Promocion']['descripcion']  = $Producto['Producto']['descripcion'];
	                $Promocion['Promocion']['fecha_inicio'] = $a_Item['FecIni'].' 00:00:00';
	                $Promocion['Promocion']['fecha_fin']    = $a_Item['FecFin'].' 23:59:59';
	                
	                
	                $this->Promocion->create();
	                if (!$this->Promocion->save($Promocion)) {
	                    //echo var_dump($this->Promocion->invalidFields());
	                    //pr($this->Promocion->invalidFields());
	                    echo 'error Producto';
	                    pr($this->Promocion->validationErrors);
	                    //pr($this->Promocion->getDataSource()->getLog(false, false));
	                }
	            }else{
    	            $SpecialPrice = $a_Item['SpecialPrice']/1.13/1.18;
    	            if (!empty($Promocion) && !empty($a_Item['Name']) && $SpecialPrice < $Promocion['Promocion']['precio']){
    	                $Promocion['Promocion']['precio']       = $SpecialPrice; 
    	                $Promocion['Promocion']['fecha_fin']    = $a_Item['FecFin'].' 23:59:59';
    	                if (!$this->Promocion->save($Promocion)) {
    	                    echo 'error Producto actualización de fecha de fin';
    	                }
    	            }
    	            
    	            if(($a_Item['Status'] == 'I') && !empty($Promocion) && $Promocion['Promocion']['estado'] == 'A'){
        	            //pr($Promocion);
        	            echo 'Desactivo Producto';
        	            $Promocion['Promocion']['estado']    = 'D';
        	            pr($Promocion);
        	            if (!$this->Promocion->save($Promocion)) {
	                        echo 'error Producto';
	                        pr($this->Promocion->validationErrors);
	                    }
    	            }
	            }
	            //exit;
	        }
	    }
	    //pr($contents);
	    //exit;
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
