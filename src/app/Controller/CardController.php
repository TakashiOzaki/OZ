<?php
App::uses('AppController', 'Controller');
/**
 * 
 * 
 * Users Controller
 *
 * @prope   rty User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CardController extends AppController {

    
    
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session','Auth');
        public function beforeFilter() {
            parent::beforeFilter();
        }
        
            public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}
 
        public function mcard() {
            
        }

 }