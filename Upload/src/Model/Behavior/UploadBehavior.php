<?php
namespace Upload\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Intervention\Image\ImageManagerStatic;

class UploadBehavior extends Behavior
{
	

	protected $defaultOptions = [
	    'fields' =>  array()
	];

	private $options = array();
	private $nameModel = '';
	public function __construct ($model, $config)
	{	
		$this->options[$config['model']] = array_merge($this->defaultOptions, $config);
		$this->nameModel = $config['model'];
	}

	/*public function initialize(array $config)
	{
		debug($this->_table->alias());
		debug($config); die;
	}*/



    public function beforeSave(Event $event, $data) {

    	foreach ($this->options[$this->nameModel]['fields'] as $field => $path) {
    		$field_name = $field.'_file';
    		$field_file = $data->$field_name;
    		if (file_exists($field_file['tmp_name'])){
	            if ($data[$field]) {
	                unlink(WWW_ROOT . $path. $data->$field);
	            }
	            $path = WWW_ROOT . $path ;

	            if (!file_exists($path)) {
	            	mkdir($path, 0007, true);
	            }
	            $ext = pathinfo($field_file['name'], PATHINFO_EXTENSION);
	            $namefile = md5(uniqid(60)).'.'.$ext;
	            ImageManagerStatic::make($field_file['tmp_name'])->resize(500,300)->save($path.$namefile);
	            chmod($path.$namefile, 0777);
	            $data->$field = $namefile;
    		}
    	}
        
        return true;
    }


    public function beforeDelete (Event $event, $data) {
    	foreach ($this->options[$this->nameModel]['fields'] as $field => $path) {
    		if (file_exists(WWW_ROOT. $path . $data->$field)){
    			unlink(WWW_ROOT. $path . $data->$field);
    		}
    	}
    	return true;
    }

}