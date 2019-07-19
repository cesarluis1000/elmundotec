<?php
App::uses('AppController', 'Controller');
/**
 * Marcas Controller
 *
 * @property Marca $Marca
 * @property PaginatorComponent $Paginator
 */
class MarcasController extends AppController {

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
		$this->Marca->recursive = 0;
		//Si se busca campo displayField del modelo
		$campo = !empty($this->Marca->displayField)?$this->Marca->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['Marca'][$campo] = $nombre ;
			$conditions = array('conditions' => array('Marca.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$this->set('marcas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Marca->exists($id)) {
			throw new NotFoundException(__('Invalido(a) marca'));
		}
		$options = array('conditions' => array('Marca.' . $this->Marca->primaryKey => $id));
		$this->set('marca', $this->Marca->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Marca->create();
			if ($this->Marca->save($this->request->data)) {
				$this->Flash->success(__('El/la marca se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la marca. Por favor, inténtelo de nuevo.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Marca->exists($id)) {
			throw new NotFoundException(__('Invalido(a) marca'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Marca->save($this->request->data)) {
				$this->Flash->success(__('El/la marca se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la marca. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Marca.' . $this->Marca->primaryKey => $id));
			$this->request->data = $this->Marca->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Marca->id = $id;
		if (!$this->Marca->exists()) {
			throw new NotFoundException(__('Invalid marca'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Marca->delete()) {
			$this->Flash->success(__('El/la marca ha sido borrado.'));
		} else {
			$this->Flash->error(__('El/la marca no ha sido borrado. Por favor inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
