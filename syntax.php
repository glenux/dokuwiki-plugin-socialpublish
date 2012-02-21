<?php

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_socialpublish extends DokuWiki_Syntax_Plugin {

	/**
	 * return some info
	 */
	function getInfo(){
		return array(
			'author' => 'Glenn Y. Rolland',
			'email'  => 'glenux@glenux.net',
			'date'   => '23/12/2010',
			'name'   => 'Social Publish Plugin',
			'desc'   => 'Add some link to social sites. syntax : {{socialpublish}}',
			'url'    => 'http://www.dokuwiki.org/plugin:socialpublish',
		);
	}

	/**
	 * What kind of syntax are we?
	 */
	function getType(){
		return 'substition';
	}

	/**
	 * Where to sort in?
	 */
	function getSort(){
		return 108;
	}

	/**

	{{socialpublish>ID}} 
	ex: {{socialpublish>facebook}}
	ex: {{socialpublish>twitter}}
	ex: {{socialpublish>identi.ca}}
	etc.


	 */
	/**
	 * Connect pattern to lexer
	 */
	function connectTo($mode) {
		$this->Lexer->addSpecialPattern("{{socialpublish.*?}}",$mode,'plugin_socialpublish');
	}

	/**
	 * Handle the match
	 */
	function handle($match, $state, $pos, &$handler){

		return true;
	}

	/**
	 * Create output
	 */
	function render($mode, &$renderer, $data) {
		if($mode == 'xhtml'){       		
			$renderer->doc .= '<div>';
			$renderer->doc .= $this->_socialpublish();
			$renderer->doc .= '</div>';
			return true;
		}
		return false;
	}

	function _socialpublish() {
		global $conf;
		$r='<ul id="socialpublish">';
		/* list local templates */
		/* test if socialpublish/template-<ID>.html exists or fail */
		/* load socialpublish/template-<ID>.html && remplate @@URL@@, @@TITLE@@, @@VIA@@ */

		$g=@file(DOKU_PLUGIN.'socialpublish/list.txt');
		foreach ($g as $v) {
			if ($v{0}=='#'){ continue; } /*comments line starts by #*/
			$v=explode('|',$v,2);
			if (@$v[1]) {
				$h=$v[1];
		} else {
			$h=parse_url($v[0]);
			$h=$h['host'];
		}

			$r.='<li><a href="'.$v[0].$this->_getfullURL().'" '.(($conf['target']['extern']!='')?'target="'.$conf['target']['extern'].'"':'').'>'.$h."</a></li>\n";
		}
		return $r.'</ul>';
	}

	function _getfullURL() {
		return 'http'.(($_SERVER['HTTPS']=='on')?'s':'').'://'.$_SERVER['HTTP_HOST'].(($_SERVER['SERVER_PORT']!='80')?':'.$_SERVER['SERVER_PORT']:'').$_SERVER['REQUEST_URI'];
	}
}

?>
