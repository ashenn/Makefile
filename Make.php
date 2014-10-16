#!/usr/bin/php
<?php 
	const PATH = "./";
	const LIBS = "-lsfml-graphics -lsfml-window -lsfml-system";
	const NAME  = "game";
	const FLAGS = "-Werror -Wall -W";
	const DIR_EXCLUDE = "fonts;images";
	const FILE_EXCLUDE = "bufferGestion.cpp;main.cpp";

/**
* 
*/
class Make{


	function __construct($option){

		system("clear");

		$this->dirExcludes = explode(";", DIR_EXCLUDE);
		$this->fileExcludes = explode(";", FILE_EXCLUDE);

		foreach ($this->dirExcludes as &$value) {
			$value = $value;
		}

		foreach ($this->fileExcludes as &$value) {
			$value = $value;
		}

		switch ($option) {
			case 'clean':
				$this->deleteO();
				break;

			case 'lib':
				$this->createO();
				break;
			
			case 'full':
				$this->full();
				break;

			default:
				$this->all();
				break;
		}
	}

	private $dirExcludes = array();

	private $fileExcludes = array();

	private function createO(){		
		$cpp = explode(" ", $this->getFiles(".cpp"));
		unset($cpp[count($cpp)-1]);
			foreach ($cpp as $c) {
				echo "g++-4.8 -std=c++0x -c ".$c."\n";
				echo $retour = shell_exec("g++-4.8 -std=c++0x -c ".$c);
				if (strlen($retour) > 0) {
					exit;
				}
			}
		}

	private function isExclude($name){
		if (in_array($name, $this->dirExcludes) || in_array($name, $this->fileExcludes)) {
			return true;
		}
		return false;
	}

	private function deleteO(){		
		$o = $this->getFiles(".o");
		echo shell_exec("rm ".$o);
	}

	private function all(){
		$this->createO();
		$this->compile();
		$this->deleteO();
	}

	private function compile(){
		if (file_exists("./".NAME)) {
			unlink("./".NAME);
		}

		$o = $this->getFiles(".o");
		echo "g++-4.8 -std=c++0x ".$o."-o ".NAME." ".LIBS." ".FLAGS."\n";
		echo shell_exec("g++-4.8 -std=c++0x ".$o."-o ".NAME." ".LIBS." ".FLAGS);

	}

	private function getFiles($extention, $path = PATH){
		
		$files = scandir($path);
		$dirs = array();
		$list = "";
		$nbrDir = 0;

		foreach ($files as $file) {

			if ($file != '.' && $file != '..' && strpos($file, $extention) > 0) {
				if (!$this->isExclude($file)) {
					$list .= $path.$file." ";
				}else{
					echo $file.": ";
					echo "Excluded\n";
				}
			}
			elseif ($file != '.' && $file != '..' && is_dir($path.$file)) {
				if (!$this->isExclude($file)) {
					$dirs[$nbrDir] = $path.$file."/";
					$nbrDir++;
				}else{
					echo $file.": ";
					echo "Excluded\n";
				}
			}
		}
		
		if(count($dirs) > 0) {
			foreach ($dirs as $d) {
				// echo $d."\n";
				$list .= $this->getFiles($extention, $d);
			}
		}
		return $list;
	}

	private function full(){
		$this->all();
		shell_exec("./".NAME);
	}
}

$option = (isset($argv[1])) ? $argv[1] : "";

$make = new Make($option);


?>