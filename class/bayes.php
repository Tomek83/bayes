#!/usr/bin/php
<?php

interface bayes_methods {
	public function examine (); # metoda badania tekstu zwróci info do którego zbioru podany tekst pasuje
	public function decision ($yes); # metoda przyjmuje decyzje usera czym jest badany tekst Spam lub nie Spam
}

interface bayes_methods_debug extends bayes_methods {
	public function return_L (); # w celach testów ponieważ Zbiory $L i $P są private
	public function return_P (); # w celach testów ponieważ Zbiory $L i $P są private
}

class bayes implements bayes_methods_debug {
	private $TEXT;
	private $L;
	private $P;
	public function bayes ($text) {
		if (mb_strlen($text)<TEXT_LENGTH) throw new Exception('Text jest za krótki!');
		$this->TEXT=array_filter(array_unique(preg_split('/\s+/', mb_ereg_replace('[[:punct:]]', '', mb_strtolower($text)))), function($val){return (mb_strlen($val)>WORD_LENGTH) ? TRUE : FALSE;});
		$this->L=array();
		$this->P=array();
		$this->coll_read();
	}
	public function examine () {
		$pr_L=0.0; $pr_P=0.0;
		foreach ($this->TEXT as $word) {
			foreach ($this->L as $txt) {
				if ($word==$txt[0]) $pr_L+=$txt[1];
			}
			foreach ($this->P as $txt) {
				if ($word==$txt[0]) $pr_P+=$txt[1];
			}
		}
		return ($pr_L>$pr_P) ? TRUE : FALSE;
	}
	public function decision ($yes) {
		$found=FALSE;
		foreach ($this->TEXT as $word) {
			foreach ($this->L as &$txt) {
				if ($word==$txt[0]) {
					($yes) ? $txt[1]+=SCALE : $txt[1]-=SCALE;
					$found=TRUE;
					break;
				}
			}
			foreach ($this->P as &$txt) {
				if ($word==$txt[0]) {
					($yes) ? $txt[1]-=SCALE : $txt[1]+=SCALE;
					$found=TRUE;
					break;
				}
			}
			if (!$found) {
				if ($yes) {
					array_push($this->L, array($word, SCALE));
				}
				else {
					array_push($this->P, array($word, SCALE));
				}
			}
			$found=FALSE;
		}
		$this->coll_move();
	}
	private function coll_read () {
		if (($L_file=file_get_contents(L))===FALSE) throw new Exception('Nie można odczytać zbioru L!');
		if (($P_file=file_get_contents(P))===FALSE) throw new Exception('Nie można odczytać zbioru P!');
		foreach (explode("\n", trim($L_file)) as $__l) {
			if (!empty($__l) && $__r=explode(';;', trim($__l))) {
				array_push($this->L, array($__r[0], (float)str_replace(',', '.', $__r[1])));
			}
		}
		foreach (explode("\n", trim($P_file)) as $__l) {
			if (!empty($__l) && $__r=explode(';;', trim($__l))) {
				array_push($this->P, array($__r[0], (float)str_replace(',', '.', $__r[1])));
			}
		}
	}
	private function coll_move () {
		$arr_L=array();
		$arr_P=array();
		foreach ($this->L as $key=>$val) {
			if ($val[1]<=0) {
				array_push($arr_L, array($val[0], SCALE));
				unset($this->L[$key]);
			}
		}
		foreach ($this->P as $key=>$val) {
			if ($val[1]<=0) {
				array_push($arr_P, array($val[0], SCALE));
				unset($this->P[$key]);
			}
		}
		if (!empty($arr_P)) $this->L=array_merge($this->L, $arr_P);
		if (!empty($arr_L)) $this->P=array_merge($this->P, $arr_L);
		$this->coll_save();
	}
	private function coll_save () {
		$arr_L=array();
		$arr_P=array();
		foreach ($this->L as $val) {
			array_push($arr_L, trim(implode(';;', $val)));
		}
		foreach ($this->P as $val) {
			array_push($arr_P, trim(implode(';;', $val)));
		}
		if ((file_put_contents(L, implode("\n", $arr_L)))===FALSE) throw new Exception('Nie można zapisać zbioru L!');
		if ((file_put_contents(P, implode("\n", $arr_P)))===FALSE) throw new Exception('Nie można zapisać zbioru P!');
	}
	public function return_L () {
		return $this->L;
	}
	public function return_P () {
		return $this->P;
	}
	public function __destruct () {
		unset($this->L);
		unset($this->P);
	}
}

?>
