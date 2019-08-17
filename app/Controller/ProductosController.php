<?php
App::uses('AppController', 'Controller');
/**
 * Productos Controller
 *
 * @property Producto $Producto
 * @property PaginatorComponent $Paginator
 */
class ProductosController extends AppController {
    
    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    
    public function migracion_productos($s_codigoItem = null){
        $this->layout = false;
        $this->autoRender = false;
        ini_set("memory_limit","2048M");
        ini_set('max_execution_time', 0);
        
        $this->loadModel('Parametro');
        $this->loadModel('Categoria');
        $this->loadModel('Subcategoria');
        $this->loadModel('Marca');
        
        $condicion = array('conditions' => array('Parametro.modulo' => 'migracion_productos',
            'Parametro.variable' => 'sizePag'));
        $parametros = $this->Parametro->find('first',$condicion);
        $sizePag = $parametros['Parametro']['valor'];
        
        $condicion = array('conditions' => array('Parametro.modulo' => 'migracion_productos',
            'Parametro.variable' => 'page'));
        $parametros = $this->Parametro->find('first',$condicion);
        $page = $parametros['Parametro']['valor'];
        
        //	    $page = 1;
        //	    $sizePag = 10;
        
        if (isset($s_codigoItem) && !empty($s_codigoItem)){
            $page = 1;
            $sizePag = 6000;
        }
        
        $ip_server = $_SERVER['SERVER_ADDR'];
        pr($ip_server);
        $url = "http://ws.deltron.com.pe/xtranet/ecommerce/servicejson/itemRequest.json.php?CodAuthenticate=100011&ServiceName=itemData&page=$page&sizePag=$sizePag";
        $primer = date("Y-m-d H:i:s");
        pr($url);
        //exit;
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
        array_walk_recursive($contents, function(&$v) { $v = strip_tags($v); });
        
        if(isset($contents[0]['error'])){
            pr($contents);
            exit;
        }
        
        
        $a_Items = $contents[0]['Items'];
        if (isset($s_codigoItem) && !empty($s_codigoItem)){
            $i = 1;
            foreach($a_Items AS $codigoItem => $a_Item){
                if($codigoItem == $s_codigoItem ){
                    pr($i);
                    pr($a_Item);
                    break;
                }
                $i++;
            }
            exit;
        }
        //pr($a_Items);
        //Migracion de Categorias, Subcategorias, Marcas
        foreach($a_Items AS $codigoItem => $a_Item){
            pr($codigoItem);
            pr($a_Item['TechInfoSmall']);
            //pr($a_Item);
            //pr($a_Item['Stock']);
            
            //Actualizacion de Categoria
            $condicion = array('conditions' => array('Categoria.codigo' => $a_Item['ItemTypeName']),
                'recursive' => -1);
            $Categoria = $this->Categoria->find('first',$condicion);
            if(empty($Categoria)){
                $Categoria['Categoria']['codigo'] = $a_Item['ItemTypeName'];
                $Categoria['Categoria']['nombre'] = $a_Item['ItemTypeName'];
                $this->Categoria->create();
                if (!$this->Categoria->save($Categoria)) {
                    echo 'error categoria';
                }
                $Categoria['Categoria']['id'] = $this->Categoria->getLastInsertId();
            }
            //pr($Categoria);
            
            //Actualizacion de Subcategoria
            $condicion = array('conditions' => array('Subcategoria.codigo' => $a_Item['SalesLineName']),
                'recursive' => -1);
            $Subcategoria = $this->Subcategoria->find('first',$condicion);
            
            if(empty($Subcategoria)){
                $Subcategoria['Subcategoria']['codigo'] 		= $a_Item['SalesLineName'];
                $Subcategoria['Subcategoria']['nombre'] 		= $a_Item['SalesLineName'];
                $Subcategoria['Subcategoria']['categoria_id'] 	= $Categoria['Categoria']['id'];
                //pr($Subcategoria);
                $this->Subcategoria->create();
                if (!$this->Subcategoria->save($Subcategoria)) {
                    echo 'error Subcategoria';
                }
                $Subcategoria['Subcategoria']['id'] = $this->Subcategoria->getLastInsertId();
            }
            //pr($Subcategoria);
            
            //Actualizacion de Marcas
            $condicion = array('conditions' => array('Marca.codigo' => $a_Item['BrandName']),
                'recursive' => -1);
            $Marca = $this->Marca->find('first',$condicion);
            if(empty($Marca)){
                $Marca['Marca']['codigo'] 		= $a_Item['BrandName'];
                $Marca['Marca']['nombre'] 		= $a_Item['BrandName'];
                $this->Marca->create();
                if (!$this->Marca->save($Marca)) {
                    echo 'error Marca';
                }
                $Marca['Marca']['id'] = $this->Marca->getLastInsertId();
            }
            //pr($Marca);
            
            //Actualizacion de Productos
            $condicion = array('conditions' => array('Producto.codigo' => $codigoItem),
                'recursive' => -1);
            $Producto = $this->Producto->find('first',$condicion);
            
            //pr($codigoItem);
            //pr($a_Item);
            //pr($Producto);
            
            if( empty($Producto) || (!empty($Producto) && trim($Producto['Producto']['caracteristicas']) == '')){
                $url = "http://www.deltron.com.pe/modulos/productos/items/postsql_prueba.php?item_number={$codigoItem}&webservis=1";
                //$url = "http://www.deltron.com.pe/modulos/productos/items/postsql_prueba.php?item_number=AC128GKTDT50&webservis=1";
                //pr($Producto);
                pr($url);
                
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                $caracteristicas = curl_exec($ch);
                $caracteristicas = strstr(trim($caracteristicas), 'bgcolor="#EAF4FF"');
                $caracteristicas = strstr(trim($caracteristicas), '</table>',true);
                
                $caracteristicas = str_replace(array('class="StyleFont"','nowrap="nowrap"','class="StyleTD"','bgcolor="#EAF4FF">',"\r\n", "\n", "\r","   "), '', $caracteristicas);
                $caracteristicas = str_replace('width="100%" border="0" cellpadding="1" cellspacing="1"', 'class="table table-condensed"', $caracteristicas);
                $caracteristicas = str_replace('  ', ' ', $caracteristicas);
                $caracteristicas = str_replace('> <', '><', $caracteristicas);
                $caracteristicas = str_replace('>', '> ', $caracteristicas);
                $caracteristicas = utf8_encode($caracteristicas);
                $caracteristicas = ucwords(strtolower($caracteristicas));
                
                if (!is_string($caracteristicas) || !strlen($caracteristicas)) {
                    $caracteristicas = '';
                }else {
                    $caracteristicas .='</table>';
                }
                
            }else{
                $caracteristicas = $Producto['Producto']['caracteristicas'];
            }
            
            //pr($caracteristicas);
            
            if(empty($Producto) || (!empty($Producto) && $Producto['Producto']['imagen'] == '3') || (!empty($Producto) && $Producto['Producto']['imagen'] == '') ) {
                //if(empty($Producto)) {
                $url = $a_Item['ImageMedium'];
                //pr($Producto);
                pr(date("Y-m-d H:i:s"));
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_NOBODY, 1);
                curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                if(curl_exec($ch)!==FALSE){
                    $imagen = 1;
                }
                else{
                    $url = str_replace("on-line/","",$url);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_NOBODY, 1);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    if(curl_exec($ch)!==FALSE){
                        $imagen = 2;
                    }
                    else{
                        $imagen = 3;
                    }
                }
                
                if ($imagen != 3){
                    $ch = curl_init ($url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                    $raw=curl_exec($ch);
                    curl_close ($ch);
                    
                    $codigo = strtolower($codigoItem);
                    $saveto=  WWW_ROOT."img/productos/".$codigo.".jpg";
                    //pr($saveto);
                    
                    $fp = fopen($saveto,'w');
                    fwrite($fp, $raw);
                    fclose($fp);
                }
                
                pr($url);
                pr($imagen);
                pr(date("Y-m-d H:i:s"));
            }else{
                $imagen = $Producto['Producto']['imagen'];
            }
            
            if(empty($Producto)){
                $Producto['Producto']['codigo'] 			= $codigoItem;
                $Producto['Producto']['nombre'] 			= $a_Item['TechInfoSmall'];
                $Producto['Producto']['descripcion'] 		= $a_Item['TechInfoLarge'];
                $Producto['Producto']['stock'] 				= $a_Item['Stock'];
                $Producto['Producto']['precio'] 			= $a_Item['Price'];
                $Producto['Producto']['subcategoria_id'] 	= $Subcategoria['Subcategoria']['id'];
                $Producto['Producto']['marca_id'] 			= $Marca['Marca']['id'];
                $Producto['Producto']['imagen'] 			= $imagen;
                $Producto['Producto']['caracteristicas']    = $caracteristicas;
                $Producto['Producto']['creado'] 			= date("Y-m-d H:i:s");
                //pr($Producto);
                $this->Producto->create();
                if (!$this->Producto->save($Producto)) {
                    echo 'error Producto';
                }
                $Producto['Producto']['id'] = $this->Producto->getLastInsertId();
            }else{
                //$Producto['Producto']['nombre'] 		= $a_Item['Name'];
                $Producto['Producto']['nombre'] 		= $a_Item['TechInfoSmall'];
                $Producto['Producto']['descripcion'] 	= $a_Item['TechInfoLarge'];
                $Producto['Producto']['precio'] 		= $a_Item['Price'];
                $Producto['Producto']['stock'] 			= $a_Item['Stock'];
                $Producto['Producto']['imagen'] 		= $imagen;
                $Producto['Producto']['caracteristicas']= $caracteristicas;
                //pr($Producto);
                if (!$this->Producto->save($Producto)) {
                    echo 'error edicion Producto';
                }
            }
            //pr($Producto);
        }
        
        
        $NumRows = $contents[0]['NumRows'];
        if($page*$sizePag < $NumRows){
            $parametros['Parametro']['valor'] += 1;
        }else{
            $parametros['Parametro']['valor'] = 1;
        }
        
        if (!$this->Parametro->save($parametros)) {
            echo 'error edicion Parametro';
        }
        $segundo = date("Y-m-d H:i:s");
        pr(date($primer));
        pr(date($segundo));
        Exit;
        
    }
    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Producto->recursive = 0;
        //Si se busca campo displayField del modelo
        $campo = !empty($this->Producto->displayField)?$this->Producto->displayField:'id';
        $this->set('campo',$campo);
        if (!empty($this->request->query[$campo])){
            $nombre = $this->request->query[$campo];
            $this->request->data['Producto'][$campo] = $nombre ;
            $conditions = array('conditions' => array('Producto.'.$campo.' LIKE' => '%'.$nombre.'%'));
            $this->Paginator->settings = array_merge($this->Paginator->settings,$conditions);
        }
        $this->set('productos', $this->Paginator->paginate());
    }
    
    public function generar_seo_url(){
        $this->loadModel('Categoria');
        $this->loadModel('Subcategoria');
        $this->loadModel('Marca');
        App::uses('AppHelper', 'View/Helper');
        //Productos de la subcategoria
        $params = array('recursive' => -1);
        $a_categorias = $this->Categoria->find('all',$params);
        foreach ($a_categorias as $i => $categorias){
            $a_categorias[$i]['Categoria']['seo_url'] = AppHelper::generateSeoURL($categorias['Categoria']['nombre']);
        }
        if (!empty($a_categorias)){
            pr($a_categorias);
            $this->Categoria->saveAll($a_categorias);
        }
        
        $params = array('recursive' => -1);
        $a_Subcategoria = $this->Subcategoria->find('all',$params);
        foreach ($a_Subcategoria as $i => $subcategorias){
            $a_Subcategoria[$i]['Subcategoria']['seo_url'] = AppHelper::generateSeoURL($subcategorias['Subcategoria']['nombre']);
        }
        if (!empty($a_Subcategoria)){
            pr($a_Subcategoria);
            $this->Subcategoria->saveAll($a_Subcategoria);
        }
        
        $params = array('recursive' => -1);
        $a_Marca = $this->Marca->find('all',$params);
        foreach ($a_Marca as $i => $marca){
            $a_Marca[$i]['Marca']['seo_url'] = AppHelper::generateSeoURL($marca['Marca']['nombre']);
        }
        if (!empty($a_Marca)){
            pr($a_Marca);
            $this->Marca->saveAll($a_Marca);
        }
        pr('ok');
        exit;
    }
    
    //public function buscador($categoria_id = null,$subcategoria_id = null){
    public function buscador($slug = null, $slug2 = null, $slug3 = null){
        //pr($this->request->query);
        $this->loadModel('Categoria');
        $this->loadModel('Subcategoria');
        $this->loadModel('Marca');
        $categoria_id = null;
        $subcategoria_id = null;
        $marca_id = null;
        
        //pr($slug);pr($slug2);pr($slug3);
        
        //Buscamos si slug es una categoria o marca
        $params = array('conditions' => array('Categoria.seo_url'=>$slug),'recursive' => -1);
        $a_categoria = $this->Categoria->find('first',$params);
        if(!empty($a_categoria)){
            //pr($a_categoria);
            $categoria_id = $a_categoria['Categoria']['id'];
        }else{
            //Buscamos en la marca
            $params = array('conditions' => array('Marca.seo_url'=>$slug),'recursive' => -1);
            $a_Marca = $this->Marca->find('first',$params);
            if(!empty($a_Marca)){
                //pr($a_Marca);
                $marca_id = $a_Marca['Marca']['id'];
            }else{
                //Desconocido
                $slug = null;
            }
        }
        
        //Buscamos si slug2 es una subcategoria o marca
        if(!empty($slug2)){
            $params = array('conditions' => array('Subcategoria.seo_url'=>$slug2),'recursive' => -1);
            $a_Subcategoria = $this->Subcategoria->find('first',$params);
            if(!empty($a_Subcategoria)){
                //pr($a_Subcategoria);
                $subcategoria_id = $a_Subcategoria['Subcategoria']['id'];
            }else{
                //Buscamos en la marca
                $params = array('conditions' => array('Marca.seo_url'=>$slug2),'recursive' => -1);
                $a_Marca = $this->Marca->find('first',$params);
                if(!empty($a_Marca)){
                    //pr($a_Marca);
                    $marca_id = $a_Marca['Marca']['id'];
                }else{
                    //Desconocido
                    $slug2 = null;
                }
            }
        }
        
        //Buscamos si slug3 es marca
        if(!empty($slug3)){
            $params = array('conditions' => array('Marca.seo_url'=>$slug3),'recursive' => -1);
            $a_Marca = $this->Marca->find('first',$params);
            if(!empty($a_Marca)){
                //pr($a_Marca);
                $marca_id = $a_Marca['Marca']['id'];
            }else{
                //Desconocido
                $slug2 = null;
            }
        }
        $this->set(compact('categoria_id', 'subcategoria_id', 'marca_id'));
        //pr($categoria_id);pr($subcategoria_id);pr($marca_id);exit;
        
        //$this->Producto->recursive = 1;
        $conditions = array(//'order'                 => 'Producto.precio asc',
                            'limit'                 => 18,
                            'conditions'            => array('Producto.precio >='    => '10',
                                                            'Producto.stock >='     => '1',
                                                            'Producto.modificado >='=> date('Y-m-d H:i:s', strtotime("-2 month")) ),
                            'recursive' => 1
                            );
        $this->Paginator->settings = $conditions;
        
        //Si se busca campo displayField del modelo
        $campo = !empty($this->Producto->displayField)?$this->Producto->displayField:'id';
        $this->set('campo',$campo);
        if (!empty($this->request->query[$campo])){
            $nombre = $this->request->query[$campo];
            $this->request->data['Producto'][$campo] = $nombre ;
            $conditions = array('OR' => array(array('Producto.'.$campo.' LIKE' => '%'.$nombre.'%'),
                array('Producto.descripcion LIKE' => '%'.$nombre.'%')
            )
            );
            $this->Paginator->settings['conditions'] = array_merge($this->Paginator->settings['conditions'],$conditions);
        }
        
        $titulo_categoria = null;
        $titulo_subcategoria = null;
        $titulo_marca = null;
        //Productos de la categoria
        if (!empty($categoria_id)){
            
            //Productos de la subcategoria
            $params = array('conditions' => array('Categoria.id'=>$categoria_id),'recursive' => -1);
            $titulo_categoria = $this->Categoria->find('first',$params);
            //pr($titulo_categoria);
            if (empty($subcategoria_id)){
                $params = array('fields'=>array('id'), 'conditions' => array('Subcategoria.categoria_id'=>$categoria_id),'recursive' => -1);
                $a_subcategorias = $this->Subcategoria->find('all',$params);
                $subcategoria_id = Set::classicExtract($a_subcategorias, '{n}.Subcategoria.id');
            }else{
                $params = array('conditions' => array('Subcategoria.id'=>$subcategoria_id),'recursive' => -1);
                $titulo_subcategoria = $this->Subcategoria->find('first',$params);
                //pr($titulo_subcategoria);
            }
            
            if (!empty($marca_id)){
                $params = array('conditions' => array('Marca.id'=>$marca_id),'recursive' => -1);
                $titulo_marca = $this->Marca->find('first',$params);
                $conditions = array('Producto.marca_id' => $marca_id);
                $this->Paginator->settings['conditions'] = array_merge($this->Paginator->settings['conditions'],$conditions);
            }
            
            $conditions = array('Producto.subcategoria_id' => $subcategoria_id);
            $this->Paginator->settings['conditions'] = array_merge($this->Paginator->settings['conditions'],$conditions);
        }
        elseif (!empty($marca_id)){
            $params = array('conditions' => array('Marca.id'=>$marca_id),'recursive' => -1);
            $titulo_marca = $this->Marca->find('first',$params);
            $conditions = array('Producto.marca_id' => $marca_id);
            $this->Paginator->settings['conditions'] = array_merge($this->Paginator->settings['conditions'],$conditions);
        }
        else{
            //pagina de inicio Promociones
            //pr(date("Y-m-d H:i:s"));
            $params = array('order'     => 'Promocion.creado desc',
                            'fields'    => array('id','nombre','producto_id', 'creado', 'fecha_fin'),
                            'conditions'=> array('Promocion.fecha_fin >='        => date("Y-m-d H:i:s"),
                                                'Promocion.creado >='            => date('Y-m-d H:i:s', strtotime("-2 month")),
                                                'Promocion.estado'               => 'A',
                                                'Promocion.descripcion NOT LIKE' => '%cliente%'),
                            'recursive' => -1
                            );
            $a_promociones = $this->Producto->Promocion->find('all',$params);
            //pr($a_promociones);
            $producto_productos_id = Set::classicExtract($a_promociones, '{n}.Promocion.producto_id');
            $conditions = array('Producto.id' => $producto_productos_id);
            if (!isset($this->request->query[$campo])){
                $this->Paginator->settings['conditions'] = array_merge($this->Paginator->settings['conditions'],$conditions);
            }
            //$this->Paginator->settings['conditions'] = array_merge($this->Paginator->settings['conditions'],$conditions);
        }
        
        //pr($this->Paginator->settings);
        $productos = $this->Paginator->paginate();
        //pr($productos);
        foreach($productos as $id => $producto){
            $params = array('conditions'    => array('Promocion.producto_id'            => $producto['Producto']['id'],
                                                     'Promocion.descripcion NOT LIKE'   =>'%cliente%'),
                            'recursive' => -1);
            $Promocion = $this->Producto->Promocion->find('first',$params);
            //pr($Promocion);
            if(isset($Promocion['Promocion'])){
                $productos[$id]['Promocion'] = $Promocion['Promocion'];
            }
        }
        
        
        $this->set('productos', $productos);
        $this->set(compact('productos', 'titulo_categoria', 'titulo_subcategoria', 'titulo_marca'));
    }
    
    public function detalle($id = null) {
        if (!$this->Producto->exists($id)) {
            throw new NotFoundException(__('Invalido(a) producto'));
        }
        $options = array('conditions' => array('Producto.' . $this->Producto->primaryKey => $id),'recursive' => 0);
        $producto = $this->Producto->find('first', $options);
        //pr($producto);
        $params = array('conditions' => array('Categoria.id' => $producto['Subcategoria']['categoria_id']),'recursive' => 0);
        $subcategoria = $this->Categoria->find('first',$params);
        
        $params = array('order'=>'Promocion.fecha_fin desc', 'conditions' => array('Promocion.producto_id' => $producto['Producto']['id'],'Promocion.descripcion NOT LIKE'=>'%cliente%'),'recursive' => -1);
        $Promocion = $this->Producto->Promocion->find('first',$params);
        //pr($Promocion);
        $producto['Categoria']                 = $subcategoria['Categoria'];
        $producto['Promocion']                 = (isset($Promocion['Promocion']))?$Promocion['Promocion']:null;
        $producto['Producto']['image']         = ($producto['Producto']['imagen'] == 3) ? 'elmundotec_producto.png' : strtolower($producto['Producto']['codigo']).'.jpg';
        
        $valor =  $producto['Producto']['precio'];
        switch($valor){
            case $valor < 100: $incremento=1.10; break;
            case $valor < 200: $incremento=1.08; break;
            case $valor < 300: $incremento=1.06; break;
            default          : $incremento=1.05;
        }
        
        $producto['Producto']['warningavailability']   = ($producto['Producto']['stock'] > 0)?"InStock":"OutOfStock";
        
        $precio                                        = $producto['Producto']['precio']*1.18*$incremento;
        $producto['Producto']['precio']                = number_format(ceil($precio), 2, '.', ',');
        $producto['Producto']['search_precio']         = number_format(ceil($precio), 2, '.', '');
        $producto['Producto']['face_precio']           = ' || Precio: S/. '.$producto['Producto']['precio'];
        $producto['Producto']['fecha_fin']             = date('Y-m-d', strtotime("+1 months", strtotime($producto['Producto']['modificado'])));
        if (isset($producto['Promocion']) && date("Y-m-d H:i:s") <= $producto['Promocion']['fecha_fin']){
            $precio_promocion                          = $producto['Promocion']['precio']*1.18*1.08;
            $producto['Promocion']['precio']           = number_format(ceil($precio_promocion), 2, '.', ',');
            $producto['Promocion']['search_precio']    = number_format(ceil($precio_promocion), 2, '.', '');
            $producto['Producto']['face_precio']       = ' || Promoción: S/. '.$producto['Promocion']['precio'];
            $producto['Promocion']['fecha_fin']        = date('Y-m-d',strtotime($producto['Promocion']['fecha_fin']));
        }
        //pr($producto);
        $this->set('producto', $producto);
    }
    
    public function detalle2($codigo = null) {
        //pr($codigo);
        $options = array('conditions' => array('Producto.codigo' => $codigo),'recursive' => 0);
        $producto = $this->Producto->find('first', $options);
        //pr($producto); exit;
        $params = array('conditions' => array('Categoria.id' => $producto['Subcategoria']['categoria_id']),'recursive' => 0);
        $subcategoria = $this->Categoria->find('first',$params);
        
        $params = array('order'=>'Promocion.fecha_fin desc', 'conditions' => array('Promocion.producto_id' => $producto['Producto']['id'],'Promocion.descripcion NOT LIKE'=>'%cliente%'),'recursive' => -1);
        $Promocion = $this->Producto->Promocion->find('first',$params);
        //pr($Promocion);
        $producto['Categoria']                 = $subcategoria['Categoria'];
        $producto['Promocion']                 = (isset($Promocion['Promocion']))?$Promocion['Promocion']:null;
        $producto['Producto']['image']         = ($producto['Producto']['imagen'] == 3) ? 'elmundotec_producto.png' : strtolower($producto['Producto']['codigo']).'.jpg';
        
        $valor =  $producto['Producto']['precio'];
        switch($valor){
            case $valor < 100: $incremento=1.10; break;
            case $valor < 200: $incremento=1.08; break;
            case $valor < 300: $incremento=1.06; break;
            default          : $incremento=1.05;
        }
        
        $producto['Producto']['warningavailability']   = ($producto['Producto']['stock'] > 0)?"InStock":"OutOfStock";
        
        $precio                                        = $producto['Producto']['precio']*1.18*$incremento;
        $producto['Producto']['precio']                = number_format(ceil($precio), 2, '.', ',');
        $producto['Producto']['search_precio']         = number_format(ceil($precio), 2, '.', '');
        $producto['Producto']['face_precio']           = ' || Precio: S/. '.$producto['Producto']['precio'];
        $producto['Producto']['fecha_fin']             = date('Y-m-d', strtotime("+1 months", strtotime($producto['Producto']['modificado'])));
        if (isset($producto['Promocion']) && date("Y-m-d H:i:s") <= $producto['Promocion']['fecha_fin']){
            $precio_promocion                          = $producto['Promocion']['precio']*1.18*1.08;
            $producto['Promocion']['precio']           = number_format(ceil($precio_promocion), 2, '.', ',');
            $producto['Promocion']['search_precio']    = number_format(ceil($precio_promocion), 2, '.', '');
            $producto['Producto']['face_precio']       = ' || Promoción: S/. '.$producto['Promocion']['precio'];
            $producto['Promocion']['fecha_fin']        = date('Y-m-d',strtotime($producto['Promocion']['fecha_fin']));
        }
        //pr($producto);
        $this->set('producto', $producto);
    }
    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Producto->exists($id)) {
            throw new NotFoundException(__('Invalido(a) producto'));
        }
        $options = array('conditions' => array('Producto.' . $this->Producto->primaryKey => $id));
        $this->set('producto', $this->Producto->find('first', $options));
    }
    
    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Producto->create();
            if ($this->Producto->save($this->request->data)) {
                $this->Flash->success(__('El/la producto se ha guardado.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('No se pudo guardar el/la producto. Por favor, int�ntelo de nuevo.'));
            }
        }
        $subcategorias = $this->Producto->Subcategoria->find('list');
        $marcas = $this->Producto->Marca->find('list');
        $this->set(compact('subcategorias', 'marcas'));
    }
    
    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Producto->exists($id)) {
            throw new NotFoundException(__('Invalido(a) producto'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Producto->save($this->request->data)) {
                $this->Flash->success(__('El/la producto se ha guardado.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('No se pudo guardar el/la producto. Por favor, int�ntelo de nuevo.'));
            }
        } else {
            $options = array('conditions' => array('Producto.' . $this->Producto->primaryKey => $id));
            $this->request->data = $this->Producto->find('first', $options);
        }
        $subcategorias = $this->Producto->Subcategoria->find('list');
        $marcas = $this->Producto->Marca->find('list');
        $this->set(compact('subcategorias', 'marcas'));
    }
    
    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Producto->id = $id;
        if (!$this->Producto->exists()) {
            throw new NotFoundException(__('Invalid producto'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Producto->delete()) {
            $this->Flash->success(__('El/la producto ha sido borrado.'));
        } else {
            $this->Flash->error(__('El/la producto no ha sido borrado. Por favor int�ntelo de nuevo.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
}
