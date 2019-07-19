<?php
App::uses('AppController', 'Controller');
/**
 * Precios Controller
 *
 * @property Precio $Precio
 * @property PaginatorComponent $Paginator
 */
class PreciosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Precio->recursive = 0;
		//Si se busca campo displayField del modelo
		$campo = !empty($this->Precio->displayField)?$this->Precio->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['Precio'][$campo] = $nombre ;
			$conditions = array('conditions' => array('Precio.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$this->set('precios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Precio->exists($id)) {
			throw new NotFoundException(__('Invalido(a) precio'));
		}
		$options = array('conditions' => array('Precio.' . $this->Precio->primaryKey => $id));
		$this->set('precio', $this->Precio->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Precio->create();
			if ($this->Precio->save($this->request->data)) {
				$this->Flash->success(__('El/la precio se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la precio. Por favor, inténtelo de nuevo.'));
			}
		}
		$productos = $this->Precio->Producto->find('list');
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
		if (!$this->Precio->exists($id)) {
			throw new NotFoundException(__('Invalido(a) precio'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Precio->save($this->request->data)) {
				$this->Flash->success(__('El/la precio se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la precio. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Precio.' . $this->Precio->primaryKey => $id));
			$this->request->data = $this->Precio->find('first', $options);
		}
		$productos = $this->Precio->Producto->find('list');
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
		$this->Precio->id = $id;
		if (!$this->Precio->exists()) {
			throw new NotFoundException(__('Invalid precio'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Precio->delete()) {
			$this->Flash->success(__('El/la precio ha sido borrado.'));
		} else {
			$this->Flash->error(__('El/la precio no ha sido borrado. Por favor inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
