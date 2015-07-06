<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class base_item {
    
    protected $data = array();
    protected static $CI = false;
    
    public function __construct($newdata = array()) {
        foreach ($newdata as $key => $value)
            if (isset($this->data[$key]))
                $this->data[$key] = $value;
        if (!self::$CI)
            self::$CI = &get_instance();
    }
    
    public function __get($key) {
        if (isset($this->data[$key]))
            return $this->data[$key];
        else
            show_error('Key "' . $key . '" not found.');
    }
    
    public function asArray() {
        return $this->data;
    }
    
}

class collection implements Iterator, Countable {

    protected $raw = array();
    protected $total = 0;
    protected $pointer = 0;
    protected $objects = array();
    protected $model = ''; // override in the child 
    
    function __construct(array $raw = null){
        if (!is_null($raw)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }
    }

    public function getRow($num) {
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->model->factory($this->raw[$num]);
            return $this->objects[$num];
        }
    }
    
    public function slice($offset, $length) { // same as array_slice but for collections
        $class = get_class($this);
        return new $class(array_slice($this->raw, $offset, $length));
    }

    public function asArray(){
        return $this->raw;
    }

/** Start Interface: Iterator */
    public function rewind() {
        $this->pointer = 0;
    }
    
    public function current() {
        return $this->getRow($this->pointer);
    }
    
    public function key() {
        return $this->pointer;
    }
    
    public function next() {
        $row = $this->getRow($this->pointer);
        if ($row) { $this->pointer++; }
        return $row;
    }
    
    public function valid() {
        return (!is_null($this->current()));
    }
/** End Interface: Iterator */

/** Start Interface: Countable */    
    public function count() {
        return $this->total;
    }
/** End Interface: Countable */
    
}