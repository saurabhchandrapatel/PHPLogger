<?php

// Scoial hook
class PHPLogger implements \SplSubject {

	/**
	 * [$name description]
	 * @var [type]
	 */
	private $filename;
	/**
	 * [$observers description]
	 * @var array
	 */
	private $observers = array();
	/**
	 * [$content description]
	 * @var [type]
	 */
	private $content;

	/**
	 * [$path description]
	 * @var [type]
	 */
	private $path = '/var/log/';

	/**
	 * [__construct description]
	 * @param [type] $name [description]
	 */
	public function __construct($path) {
		$this->filename = date('y-m-d') . '.log';
		$this->path = $path;
	}

	public function __get($pro) {
		return $this->$pro;
	}

	/**
	 * [__set description]
	 * @param [type] $pro   [description]
	 * @param [type] $value [description]
	 */
	public function __set($pro, $value) {
		return $this->$pro = $value;
	}

	//add observer
	/**
	 * [attach description]
	 * @param  \SplObserver $observer [description]
	 * @return [type]                 [description]
	 */
	public function attach(\SplObserver $observer) {
		$this->observers[] = $observer;
	}

	//remove observer
	/**
	 * [detach description]
	 * @param  \SplObserver $observer [description]
	 * @return [type]                 [description]
	 */
	public function detach(\SplObserver $observer) {

		$key = array_search($observer, $this->observers, true);
		if ($key) {
			unset($this->observers[$key]);
		}
	}

	public function getContent() {
		return $this->content . " ({$this->filename})";
	}

	//notify observers(or some of them)
	/**
	 * [notify description]
	 * @return [type] [description]
	 */
	public function notify() {
		foreach ($this->observers as $value) {
			$value->update($this);
		}
	}

	public function writeLog() {
		$this->notify();
	}

}

class Reportlog implements SplObserver {

	private $action;
	/**
	 * [__construct description]
	 * @param iSocialShare $socialmedia [description]
	 */
	public function __construct($action) {
		$this->action = $action;
	}
	/**
	 * [update description]
	 * @param  \SplSubject $subject [description]
	 * @return [type]               [description]
	 */
	public function update(\SplSubject $subject) {

		$msg = date('y-m-d H:i:s') . "\t" . $this->action . "\n";
		$path = $subject->__get('path') . '' . $subject->__get('filename');
		error_log($msg, 3, $path);
	}
}

?>