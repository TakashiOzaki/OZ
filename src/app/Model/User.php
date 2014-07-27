<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
/**
 * User Model  dummy
 *
 */
class User extends AppModel {
    
    
public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty','email'),
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),

    );
    
    public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
		$passwordHasher = new SimplePasswordHasher();
		$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
	    }
	    return true;
    }
}
