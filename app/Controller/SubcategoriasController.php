<?php
App::uses('AppController', 'Controller');
/**
 * Subcategorias Controller
 *
 * @property Subcategoria $Subcategoria
 * @property PaginatorComponent $Paginator
 */
class SubcategoriasController extends AppController {

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
		$this->Subcategoria->recursive = 0;
		//Si se busca campo displayField del modelo
		$campo = !empty($this->Subcategoria->displayField)?$this->Subcategoria->displayField:'id';
		$this->set('campo',$campo);
		if (!empty($this->request->query[$campo])){	    
		    $nombre = $this->request->query[$campo];
			$this->request->data['Subcategoria'][$campo] = $nombre ;
			$conditions = array('conditions' => array('Subcategoria.'.$campo.' LIKE' => '%'.$nombre.'%'));
			$this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
		}
		$this->set('subcategorias', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Subcategoria->exists($id)) {
			throw new NotFoundException(__('Invalido(a) subcategoria'));
		}
		$options = array('conditions' => array('Subcategoria.' . $this->Subcategoria->primaryKey => $id));
		$this->set('subcategoria', $this->Subcategoria->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Subcategoria->create();
			if ($this->Subcategoria->save($this->request->data)) {
				$this->Flash->success(__('El/la subcategoria se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la subcategoria. Por favor, inténtelo de nuevo.'));
			}
		}
		$categorias = $this->Subcategoria->Categoria->find('list');
		$this->set(compact('categorias'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Subcategoria->exists($id)) {
			throw new NotFoundException(__('Invalido(a) subcategoria'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Subcategoria->save($this->request->data)) {
				$this->Flash->success(__('El/la subcategoria se ha guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('No se pudo guardar el/la subcategoria. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Subcategoria.' . $this->Subcategoria->primaryKey => $id));
			$this->request->data = $this->Subcategoria->find('first', $options);
		}
		$categorias = $this->Subcategoria->Categoria->find('list');
		$this->set(compact('categorias'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Subcategoria->id = $id;
		if (!$this->Subcategoria->exists()) {
			throw new NotFoundException(__('Invalid subcategoria'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Subcategoria->delete()) {
			$this->Flash->success(__('El/la subcategoria ha sido borrado.'));
		} else {
			$this->Flash->error(__('El/la subcategoria no ha sido borrado. Por favor inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
