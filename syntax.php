<?php
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_socialmark extends DokuWiki_Syntax_Plugin {
 
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'iDo',
            'email'  => 'iDo@woow-fr.com',
            'date'   => '21/03/2006',
            'name'   => 'Social Mark Plugin',
            'desc'   => 'Add some link to social bookmarking page. syntaxe : {{socialmark}}',
            'url'    => 'http://www.dokuwiki.org/plugin:socialmark',
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
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern("{{socialmark}}",$mode,'plugin_socialmark');
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
            $renderer->doc .= $this->_socialmark();
            $renderer->doc .= '</div>';
            return true;
        }
        return false;
    }
 
	function _socialmark() {
		global $conf;
		$r='<ul id="socialmark">';
		$g=@file(DOKU_PLUGIN.'socialmark/list.txt');
		foreach ($g as $v) {
			if ($v{0}=='#') continue; /*comments line starts by #*/
			$v=explode('|',$v,2);
			if (@$v[1])
				$h=$v[1];
			else {
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
 
//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
