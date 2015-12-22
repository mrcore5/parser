<?php namespace Mrcore\Parser;

use URL;
use Layout;
use Config;
use Session;
use Text_Wiki_Default;
use Mrcore\Wiki\Models\Post;
use Mrcore\Wiki\Support\Crypt;

Class Mrcore extends Parser
{
	public $userID;
	public $postID;
	public $postCreator;
	public $isAuthenticated;
	public $isAdmin;
	public $disabledRules;
	private $tokens;

	public function __construct()
	{
		$this->userID = 0;
		$this->postID = 0;
		$this->postCreator = 0;
		$this->isAuthenticated = false;
		$this->isAdmin = false;
		$this->disabledRules = [];
		$this->tokens = [];
	}

	/**
	 * Parse this text_wiki $data into HTML
	 * @return string
	 */
	public function parse($data)
	{
		//Tokens is a multi-dimensional array of items pulled out of the raw wiki usually before parsing, then put back in later
		//Tokens[0][] is all <php></php> items pulled out, each one is replaced with a [php x] where x is the occurance integer
		$this->tokens = [];

		#$benchmarkStart = microtime(true);

		$data = $this->preParse($data);
		$data = $this->wikiParse($data);
		$data = $this->postParse($data);

		#$benchmarkMs = round((microtime(true) - $benchmarkStart) * 1000);
		#dd($benchmarkMs);

		return "<div><div><div><div><div><div>".$data."</div></div></div></div></div></div>";
	}

	/**
	 * Parse $data as Text_Wiki mrcore variant
	 * @param  string $data wiki markup
	 * @return string parsed xmtl
	 */
	protected function wikiParse($data)
	{
		// Define mrcore rules
		// ORDER IS CRITICAL IN THIS ARRAY!
		// Overrides must have a different name (ie: 2 at the end)
		$rules = [
			'Prefilter2',	//mrcore specific customization to existing Text_Wiki rule
			'Delimiter',	//unchanged original Text_Wiki
			'Code2',		//mrcore specific customization to existing Text_Wiki rule
			#'Textbox',		//mrcore only rule - disabled
			#'Function',	//disabled in mrcore
			'Html2',		//mrcore specific customization to existing Text_Wiki rule
			'Raw',			//unchanged original Text_Wiki
			#'Include',		//disabled in mrcore
			#'Embed'		//disabled in mrcore
			'Anchor',		//unchanged original Text_Wiki
			'Heading2',		//mrcore specific customization to existing Text_Wiki rule
			'Toc2',			//mrcore specific customization to existing Text_Wiki rule
			'Horiz',		//unchanged original Text_Wiki
			'Break',		//unchanged original Text_Wiki
			'Blockquote',	//unchanged original Text_Wiki
			'List2',		//mrcore specific customization to existing Text_Wiki rule
			'Deflist',		//unchanged original Text_Wiki
			'Table2',		//mrcore specific customization to existing Text_Wiki rule
			'Image2',		//mrcore specific customization to existing Text_Wiki rule
			#'Phplookup',	//disabled in mrcore
			'Center2',		//mrcore specific customization to existing Text_Wiki rule
			'Newline',		//unchanged original Text_Wiki
			'Paragraph',	//unchanged original Text_Wiki...touhgh I have a commented out regex
			'Url2',			//mrcore specific customization to existing Text_Wiki rule
			'Freelink2',	//mrcore specific customization to existing Text_Wiki rule
			#'Interwiki',	//disabled in mrcore
			#'Wikilink2',	//disabled in mrcore
			'Colortext',	//unchanged original Text_Wiki
			'Strong',		//unchanged original Text_Wiki
			'Bold',			//unchanged original Text_Wiki
			'Emphasis',		//unchanged original Text_Wiki
			'Italic',		//unchanged original Text_Wiki
			'Underline',	//unchanged original Text_Wiki
			'Tt2',			//mrcore specific customization to existing Text_Wiki rule
			'Superscript',	//unchanged original Text_Wiki
			'Subscript',	//unchanged original Text_Wiki
			'Revise',		//unchanged original Text_Wiki
			'Tighten',		//unchanged original Text_Wiki
			'Smiley2',		//mrcore only rule
		];

		/*
		NOTE

		Prefilter
			In legacy, I comment out the multi line \ part because in <code> or <cmd>
			I do a lot of multi line \, and didn't want it combining them..could fix if
			<code> would jsut ignore \



		 */

		// Initialize Text_Wiki_Default
		$wiki = new Text_Wiki_Default($rules);
		$wiki->renderingType = 'preg'; //faster, better

		// Add mrcore override paths, must be called Default like parent
		$wiki->addPath('parse', $wiki->fixPath(dirname(__FILE__)) . 'TextWiki/Parse/Default/');
		$wiki->addPath('render', $wiki->fixPath(dirname(__FILE__)) . 'TextWiki/Render/');

		// Disable rules in raw mode
		if (Layout::modeIs('raw')) {
			$this->disabledRules[] = 'Paragraph';
			$this->disabledRules[] = 'Newline';
		}

		// Disable rules after $wiki instantiation
		// Other inherited classes can append to $this->disabledRules
		foreach ($this->disabledRules as $disabledRule) {
			$wiki->deleteRule($disabledRule);
		}

		$baseURL = "/";
		$posts = Post::allTitles();

		// configure wikilink
		// when rendering XHTML, make sure wiki links point to a specific base URL
		#$wiki->setRenderConf('xhtml', 'wikilink2', 'view_url', $baseURL);
		#$wiki->setRenderConf('xhtml', 'wikilink2', 'new_url', $baseURL);
		#$wiki->setRenderConf('xhtml', 'wikilink2', 'pages', $topic_ids);     # for PascalCased article names
		#$wiki->setRenderConf('xhtml', 'wikilink2', 'css', 'wiki_link');
		#$wiki->setRenderConf('xhtml', 'wikilink2', 'css_new', 'wiki_link_new');
		#$wiki->setRenderConf('xhtml', 'wikilink2', 'new_text', '');

		// configure freelink
		//FreeLink (like ((1)) or ((Some Article)) or ((1|display)) or ((Some Article|display))
		$wiki->setRenderConf('xhtml', 'freelink2', 'pages', $posts);     # for spaces in article names
		$wiki->setRenderConf('xhtml', 'freelink2', 'css', 'free_link');
		$wiki->setRenderConf('xhtml', 'freelink2', 'css_new', 'free_link_new');
		$wiki->setRenderConf('xhtml', 'freelink2', 'new_text', '');
		$wiki->setRenderConf('xhtml', 'freelink2', 'new_url', '/edit/newtopic/');
		# the url when viewing an article
		$wiki->setRenderConf('xhtml', 'freelink2', 'view_url', $baseURL.'%s');
		# the url when a link is cliked to make a new article %s is the article name
		$wiki->setRenderConf('xhtml', 'freelink2', 'new_url', '/post/create?title=%s');

		// configure embed
		#This allows embeding of PHP documents without parsing them
		#syntax: [[embed path/to/file.php]]
		#NOTE: includes cannot have / at the beginning, so make the 'base', '') for root
		#$wiki->setParseConf('embed', 'base', '');
		#$wiki->setParseConf('include', 'base', '/');

		// configure toc
		$wiki->setRenderConf('xhtml', 'toc2', 'div_id', 'toc');
		$wiki->setRenderConf('xhtml', 'toc2', 'css_list', 'toc');
		$wiki->setRenderConf('xhtml', 'toc2', 'css_item', 'toc_items');
		$wiki->setRenderConf('xhtml', 'toc2', 'title', "<div class='panel-heading'><h3 class='panel-title'>Post Content</h3></div>");
		$wiki->setRenderConf('xhtml', 'toc2', 'collapse', false);

		// configure heading
		$wiki->setRenderConf('xhtml', 'heading2', 'css_h1', 'heading1');
		$wiki->setRenderConf('xhtml', 'heading2', 'css_h2', 'heading2');
		$wiki->setRenderConf('xhtml', 'heading2', 'css_h3', 'heading3');
		$wiki->setRenderConf('xhtml', 'heading2', 'css_h4', 'heading4');
		$wiki->setRenderConf('xhtml', 'heading2', 'css_h5', 'heading5');
		$wiki->setRenderConf('xhtml', 'heading2', 'css_h6', 'heading6');

		// configure blockquote
		#$wiki->setRenderConf('xhtml', 'blockquote', 'css', 'blockQuote');

		// configure code
		$wiki->setRenderConf('xhtml', 'code2', 'css', 'abc'); //css class for <pre> around <code>
		$wiki->setRenderConf('xhtml', 'code2', 'css_code', 'abc'); //css class for <code> inside <pre>
		$wiki->setRenderConf('xhtml', 'code2', 'css_title', 'abc'); //css or title <div>

		// configure textbox (mReschke custom rule)
		$wiki->setRenderConf('xhtml', 'textbox', 'css', 'textbox');
		$wiki->setRenderConf('xhtml', 'textbox', 'css_outer', 'textbox_outer');
		$wiki->setRenderConf('xhtml', 'textbox', 'css_header', 'textbox_header');

		// configure image
		$wiki->setRenderConf('xhtml', 'image2', 'base', URL::route('file').'/');
		$wiki->setRenderConf('xhtml', 'image2', 'css', 'image');
		$wiki->setRenderConf('xhtml', 'image2', 'css_link', 'image_link');

		// configure table
		$wiki->setRenderConf('xhtml', 'table2', 'css_table', 'table table-condensed table-bordered table-striped table-hover dataTable');
		$wiki->setRenderConf('xhtml', 'table2', 'css_table_simple', 'table table-condensed table-bordered table-striped table-hover simpletable');
		$wiki->setRenderConf('xhtml', 'table2', 'css_tr', 'table_tr');
		$wiki->setRenderConf('xhtml', 'table2', 'css_th', 'table_th');
		$wiki->setRenderConf('xhtml', 'table2', 'css_td', 'table_td');

		#Table2
		#$wiki->setRenderConf('xhtml', 'table2', 'css_table', 'table_table');
		#$wiki->setRenderConf('xhtml', 'table2', 'css_tr', 'table_tr');
		#$wiki->setRenderConf('xhtml', 'table2', 'css_th', 'table_th');
		#$wiki->setRenderConf('xhtml', 'table2', 'css_td', 'table_td');

		// configure url
		$wiki->setRenderConf('xhtml', 'url2', 'images', false);  #display link, not actual image
		$wiki->setRenderConf('xhtml', 'url2', 'target', '_blank');
		$wiki->setRenderConf('xhtml', 'url2', 'css_inline', 'url_link');  #url link CSS when useing just http://...
		$wiki->setRenderConf('xhtml', 'url2', 'css_descr', 'url_link'); #url link CSS when using [http://... name]
		#$wiki->setRenderConf('xhtml', 'url2', 'css_img', 'urlLink');  #image link CSS
		#$wiki->setRenderConf('xhtml', 'url2', 'css_footnote', 'urlLink');

		// tt
		$wiki->setRenderConf('xhtml', 'tt2', 'use_code', false); //use <code> instead of <tt>
		$wiki->setRenderConf('xhtml', 'tt2', 'css', 'text-success');

		// configure smiley (defaults are good here and listed below)
		#$wiki->setRenderConf('xhtml', 'smiley2', 'prefix', 'images/smileys/icon_');
		#$wiki->setRenderConf('xhtml', 'smiley2', 'extension', '.png');
		#$wiki->setRenderConf('xhtml', 'smiley2', 'css', null);

		// Transform
		return $wiki->transform($data, 'Xhtml');

		// Render
		#$data = $wiki->transform($data, 'Xhtml');
		$data = $wiki->render('Xhtml');
		$text = $wiki->render('Plain');

		// Testing
		#$data .= "<pre>$text</pre>";

		#$wiki->transform($text);
		#$render = $wiki->render('Xhtml');

		return $data;
	}

	/**
	 * Pre-wikiParse parsing functions
	 */
	private function preParse($data)
	{
		//Must do <phpw> first because it could evan to a bunch of <include> statements which we would want to execute
		//Notice also in pre_parse_include we also recursively eval all <phpw> also
		//We also parse all phpw, includes and files first because all other pre_parse rules with work on their output
		//Files MUST come before <php> because its output is actually <php> which then need evaled

		//Pre Parse <phpw></phpw>
		$data = $this->preParsePhpW($data);

		//Pre Parse <include>
		$data = preg_replace_callback('"<include (.*?)>"i', function($matches) {
			return $this->preParseInclude($matches[1]);
		}, $data);
		$data = preg_replace('"<exclude>|</exclude>"', '', $data);
		$data = preg_replace('"<section(.*)>|</section>"', '', $data);

		//Pre Parse <files> (must be before the <php> tag because <files> returns php data which needs evaluated)
		$data = preg_replace_callback('"<files>"i', function($matches) {
			return $this->preParseFiles('');
		}, $data);
		$data = preg_replace_callback('"<files (.*?)>"i', function($matches) {
			return $this->preParseFiles($matches[1]);
		}, $data);

		//Pre Parse <search> (must be before the <php> tag because <search> returns php data which needs evaluated)
		###$data = preg_replace('"<search>"ie', "Parser::pre_parse_search(\"\\1\")", $data);
		###$data = preg_replace('"<search (.*?)>"ie', "Parser::pre_parse_search(\"\\1\")", $data);

		//Pre Parse <php></php> (must be AFTER <files> because <files> produces <php> tags)
		$data = $this->preParsePhp($data);

		//Pre Parse <cmd>
		$data = $this->preParseCmd($data);

		//Pre Parse <embed xxx yyy>
		$data = preg_replace_callback('"<embed (.*?)>"i', function($matches) {
			return $this->preParseEmbed($matches[1]);
		}, $data);

		//Pre Parse <textarea></textarea>
		$data = preg_replace('"</!textarea>"', "</textarea>", $data);

		//Pre Parse <codepress type height title></codepress>
		//CODE PRESS IS OBSOLETE, use <code> with Geshi types!
		//$data = preg_replace('"<codebox (.*?) (.*?) (.*?)>"', "<html>\n<div class='codebox_outer'><span class='codebox_header'>$3</span><textarea class='codepress $1' id='id1' style='width:100%;height:$2px;'>\n", $data);
		//$data = preg_replace('"<codebox (.*?) (.*?)>"', "<html>\n<div class='codebox_outer'><span class='codebox_header'>Script Snippet</span><textarea class='codepress $1' id='id1' style='width:100%;height:$2px;'>\n", $data);
		//$data = preg_replace('"<codebox (.*?)>"', "<html>\n<div class='codebox_outer'><span class='codebox_header'>Script Snippet</span><textarea class='codepress $1' id='id1' style='width:100%;height:400px;'>\n", $data);
		//$data = preg_replace('"<codebox>"', "<html>\n<div class='codebox_outer'><span class='codebox_header'>Script Snippet</span><textarea class='codepress text' id='id1' style='width:100%;height:400px;'>\n", $data);
		//$data = preg_replace('"</codebox>"', "</textarea></div>\n</html>\n", $data);

		//Pre Parse, remove my <teaser> and </teaser>
		$data = preg_replace('"<teaser>|</teaser>"', "", $data);

		//Pre Parse, comment tags
		$data = preg_replace('"<#(.*?)>"', "", $data);
		$data = preg_replace('"<!--(.*?)-->"', "", $data);

		//Pre Parse Remove <auth> if not allowed (pre parse so headers and TOC are removed)
		if (!$this->isAuthenticated) {
			$data = preg_replace('"<auth>.*?</auth>"sim', '', $data);
		}

		//Pre Parse Remove <private> if not allowed (pre parse so headers and TOC are removed)
		if ($this->isAuthenticated && ($this->isAdmin || $this->postCreator == $this->userID)) {
		} else {
			$data = preg_replace('"<priv>.*?</priv>"sim', '', $data);
		}

		//Pre Parse ++++++ (headers)
		$data = preg_replace('"</\+\+\+\+\+\+>"', '++++++<endheader>', $data);
		$data = preg_replace('"</\+\+\+\+\+>"', '+++++<endheader>', $data);
		$data = preg_replace('"</\+\+\+\+>"', '++++<endheader>', $data);
		$data = preg_replace('"</\+\+\+>"', '+++<endheader>', $data);
		$data = preg_replace('"</\+\+>"', '++<endheader>', $data);
		$data = preg_replace('"</\+>"', '+<endheader>', $data);

		return $data;
	}


	/**
	 * Pre-wikiParse out all <php> tags and replace with tokens
	 * Tokens will be replaced postParse
	 */
	private function preParsePhp($data)
	{
		//This removes all <php>...</php> content into a &$tokens[0] variable to be re-injected later on post parse (basically don't parse php)
		$i = 0;
		return preg_replace_callback('"<php>.*?</php>"sim',
			function($matches) use (&$i) {
				$php = $matches[0];
				$php = preg_replace('"<\?php"i', '', $php);
				$php = preg_replace('"<\?"i', '', $php);
				$this->tokens[0][] = preg_replace('"<php>|</php>"', '', $php);
				$return = "[php_token $i]";
				$i++;
				return $return;
			}, $data);
	}


	/**
	 * Pre-wikiParse all <phpw> tags and eval content
	 * We do not replace with tokens because we want the eval parsed by the parser
	 */
	private function preParsePhpW($data)
	{
		//Since we want this php return parsed we don't "pull" them out with tokens like we would <php>, we just eval them and let the wiki parse the output
		return preg_replace_callback('"<phpw>.*?</phpw>"sim',
			function($matches) {
				ob_start();
				$phpw = $matches[0];
				$phpw = preg_replace('"<\?php"i', '', $phpw);
				$phpw = preg_replace('"<\?"i', '', $phpw);
				eval(preg_replace('"<phpw>|</phpw>"', '', $phpw));
				$return = ob_get_contents();
				ob_end_clean();
				return $return;
			}, $data);
	}


	/**
	 * Pre-wikiParse all <cmd> tags and eval content
	 */
	private function preParseCmd($data)
	{
		#$data = preg_replace('"<cmd (.*?)>"ie', "Parser::pre_parse_cmd(\"\\1\")", $data);
		return preg_replace_callback('"<cmd (.*?)>"sim',
			function ($matches) {
				$cmd = preg_replace('"<cmd |>"', '', $matches[0]);
				if ($cmd != '') {
					$cmd = preg_replace('"\#"', Config::get('mrcore.wiki.files').'/index/'.$this->postID, $cmd); //Wildcards
					exec($cmd, $return);
					$return = implode("\r\n", $return);
				}
				return $return;
			}, $data);
	}


	/**
	 * Pre-wikiParse all <files> tags
	 */
	private function preParseFiles($params)
	{
		// Standarize Path fucntion
		// Makes # or #/test.txt or test.txt or /index/1/test.txt or /index/#/test.txt...
		// into a standardized path that always works!
		$standardizePath = function($path)  {
			if ($path) {
				if (substr($path, 0, 1) != '/') {
					$path = preg_replace('"#/"', '', $path);
					$path = preg_replace('"'.$this->postID.'/"', '', $path);
					$path = "#/".$path;
				}
				$path = preg_replace('"#"', $this->postID, $path);
			} else {
				$path = $this->postID;
			}
			return $path;
		};

		// U regex modifier means first occurance of ;
		if ($params && substr($params, -1) != ';') $params .= ';';
		if (preg_match('"file=(.*);"Ui', $params, $matches)) {
			// Single file <files file=xyz>
			$path = $matches[1];
			$path = $standardizePath($path);
			$title = $path;
			$title = preg_replace('"^'.$this->postID.'/"', '', $title);
			$view = '';

			if (preg_match('"title=(.*);"Ui', $params, $matches)) $title = $matches[1];
			if (preg_match('"view=(.*);"Ui', $params, $matches)) $view = strtolower($matches[1]);
			if (preg_match('"%f"', $title)) {
				$pos = strrpos($path, '/');
				$title = substr($path, $pos + 1);
			}

			#echo "Path: $path, Title: $title, View: $view";
			if ($view == 'inline') {
				if (substr($path, 0, 1) != '/') $path = 'index/'.$path;
				$file = Config::get('mrcore.wiki.files').'/'.$path;
				if (file_exists($file)) {
					return file_get_contents($file);
				} else {
					return "File $path not found";
				}
			} else {
				return "[local:/file/".preg_replace('" "', '+', $path)." $title]";
			}


		} else {
			// Using just <files> or <files xxx> as file manager view not single file
			$path = '';
			if (preg_match('"path=(.*);"Ui', $params, $matches)) $path = $matches[1];
			$path = $standardizePath($path);
			$params = trim(preg_replace('"path=(.*);"Ui', '', $params)); # remove path
			$params = "embed;".$params;
			return '
			<html>
				<div class="fm" data-path="'.$path.'" data-params="'.$params.'">
					<i class="icon-spinner icon-spin blue"></i>
					Loading File Manager ...
				</div>
			</html>
			';
		}

	}

	private function preParseEmbed($param)
	{
		// Explode parameters
		$params = explode(" ", $param);
		$type = (isset($params[0]) ? $params[0] : null);
		$location = (isset($params[1]) ? $params[1] : null);

		$return = null;
		if (isset($type) && isset($location)) {
            if ($type == 'url') {
                $rand = rand(1,999);
                $id = "embed_url_$rand";
                $return = "<html>\r\n<iframe id='$id' onload=\"resize_embed_url('$id')\" src='$location' style='width:100%'></iframe>\r\n</html>\r\n";
                #$return = "<html><object id='$id' type='text/html' onload=\"resize_embed_url('$id')\" data='$location' style='width:100%;' /></html>";
            }
		}
		return $return;
	}

	/**
	 * Pre-wikiParse include function
	 * Expands any phpw tags and performs include recusion
	 */
	private function preParseInclude($param)
	{
		// Explode parameters
		$params = explode(" ", $param);
		$id = (isset($params[0]) ? $params[0] : 0);
		$section = (isset($params[1]) ? $params[1] : null);

		if (!is_numeric($id)) return null;
		if ($id == $this->postID) return null;

		$post = Post::find($id);
		if (!isset($post)) return null;
		if (!$post->hasPermission('read')) return null;

		// Include Post
		$post->content = Crypt::decrypt($post->content);

		// Remove any <exclude> content
		$post->content = preg_replace('"<exclude>.*?</exclude>"sim', '', $post->content);

		//Recursively process more <phpw> and <includes>
		//Parse for <phpw> here too because included text may recursively contain phpw which we want to expand
		//We do phpw here because what if a <phpw> item builds a bunch of <includes>, we want to eval the <phpw>
		//first, then parse its <include> output, ah
		$post->content = $this->preParsePhpW($post->content);

		$data = '';
		if (isset($section)) {
			// Include only the text inside the matching <section id>
			if (preg_match_all('"<section '.$section.'>.*?</section>"sim', $post->content, $matches)) {
				$data = implode($matches[0]);
			}
		} else {
			// Include entire included posts content
			$data = $post->content;
		}

		// Recurse <includes>
		$data = preg_replace_callback('"<include (.*?)>"i', function($matches) {
			return $this->preParseInclude($matches[1]);
		}, $data);

		// Return unparsed included data
		return $data;
	}

	/**
	 * Post-wikiParse parsing functions
	 */
	private function postParse($data)
	{
		//Post Parse <php></php> (replace my php_tokens with the unparsed PHP in the $tokens[0] array)
		$data = preg_replace_callback('"\[php_token .*?]"sim',
			function($matches) {
				$i = preg_replace('"\[php_token |\]"','', $matches[0]);
				ob_start();
				eval($this->tokens[0][$i]);
				$return = ob_get_contents();
				$return = preg_replace('"</!textarea>"', "</textarea>", $return);
				ob_end_clean();
				return $return;
			}, $data);

		//Post Parse <box></box>
		$data = preg_replace('"&lt;box&gt;<br />"', '<div class="box panel panel-default"><table><tr><td><div>', $data);
		$data = preg_replace('"&lt;box&gt;"', '<div class="box panel panel-default"><table><tr><td><div>', $data);
		$data = preg_replace('"&lt;box (.*?) &gt;<br />"', '<div class="box panel panel-default"><table><tr><td><div class="box_left">$1</div></td><td><div class="box_right">', $data);
		$data = preg_replace('"&lt;box (.*?) &gt;"', '<div class="box panel panel-default"><table><tr><td><div class="box_left">$1</div></td><td><div class="box_right">', $data);
		$data = preg_replace('"&lt;box (.*?)&gt;<br />"', '<div class="box panel panel-default"><table><tr><td><div class="box_left">$1</div></td><td><div class="box_right">', $data);
		$data = preg_replace('"&lt;box (.*?)&gt;"', '<div class="box panel panel-default"><table><tr><td><div class="box_left">$1</div></td><td><div class="box_right">', $data);
		$data = preg_replace('"&lt;/box&gt;"', '</div></td></table></div>', $data);


		//Post parse <highlight></highlight>
		$data = preg_replace_callback('"&lt;highlight(.*?)&gt;(.*?)&lt;/highlight&gt;"i', function($matches) {
			return $this->postParseHighlight(true, $matches[1], $matches[2]);
		}, $data); //Own Line
		$data = preg_replace_callback('"&lt;highlight(.*?)&gt;(<br />|</p>)"i', function($matches) {
			return $this->postParseHighlight(false, $matches[1]);
		}, $data); //Own Line
		$data = preg_replace('"&lt;/highlight&gt;"', '</div>', $data);


		//Post parse ending header tags
		//The </+> has been moved to pre_parse, and changed to replace with +<endheader>, to maintain accurate divs in Text_Wiki/Render/Heading.php
		#$data = preg_replace('"&lt;/\+\+\+\+\+\+&gt;"', '</div>', $data);
		#$data = preg_replace('"&lt;/\+\+\+\+\+&gt;"', '</div></div>', $data);
		#$data = preg_replace('"&lt;/\+\+\+\+&gt;"', '</div></div></div>', $data);
		#$data = preg_replace('"&lt;/\+\+\+&gt;"', '</div></div></div></div>', $data);
		#$data = preg_replace('"&lt;/\+\+&gt;"', '</div></div></div></div></div>', $data);
		#$data = preg_replace('"&lt;/\+&gt;"', '</div></div></div></div></div></div>', $data);
		$data = preg_replace('"&lt;endheader&gt;"', '',$data);

		//Post parse remove <expand> tag, I just remove it here, its used in my Text_Wiki headers additions
		//I reversed the logic, I don't want <expand> anymore, but still exists in some topics.  I want all expanded by default, and use the <-> tag
		$data = preg_replace('" &lt;expand&gt; "', '', $data);
		$data = preg_replace('" &lt;expand&gt;"', '', $data);
		$data = preg_replace('"&lt;expand&gt;"', '', $data);

		$data = preg_replace('" &lt;-&gt; "', '', $data);
		$data = preg_replace('" &lt;-&gt;"', '', $data);
		$data = preg_replace('"&lt;-&gt;"', '', $data);

		$data = preg_replace('" &lt;notoc&gt; "', '', $data);
		$data = preg_replace('" &lt;notoc&gt;"', '', $data);
		$data = preg_replace('"&lt;notoc&gt;"', '', $data);

		//Post Parse <pagebreak>, I just remove it here, its used in my Text_Wiki headers additions
		$data = preg_replace('" &lt;pagebreak&gt; "', '', $data);
		$data = preg_replace('" &lt;pagebreak&gt;"', '', $data);
		$data = preg_replace('"&lt;pagebreak&gt;"', '', $data);
		#$data = preg_replace('"&lt;pagebreak&gt;"', '<span style="page-break-before: always;"></span>', $data);

		//Post Parse <noprint>
		$data = preg_replace('"&lt;noprint&gt;<br />"', '<div class="noprint">', $data);
		$data = preg_replace('"&lt;noprint&gt;"', '<div class="noprint">', $data);
		$data = preg_replace('"&lt;/noprint&gt;"', '</div>', $data);

		//Post parse <info>...</info> box
		$data = preg_replace('"&lt;info&gt;"', '<div class="info clearfix">', $data);
		$data = preg_replace('"&lt;/info&gt;"', '</div>', $data);

		//Post parse <infol>...</infol> box
		$data = preg_replace('"&lt;infol&gt;"', '<div class="infol">', $data);
		$data = preg_replace('"&lt;/infol&gt;"', '</div>', $data);

		//Post Parse, fix <auth>...</auth> tags
		//I do this post parse because for pre parse I have to do <html><div>... which is fine
		//But text_wiki wants <html> on its own line, so will not work the inline <auth->
		//I autodetect inline or own line by /n, so I can use div or span, because div/span make things look soo much different
		if ($this->isAuthenticated) {
			//The first inline regex will not catch multi line <auth> but the second line will, so this works great!
			$data = preg_replace('"&lt;auth&gt;(.*?)&lt;/auth&gt;"', '<span class="auth_inline">$1</span>', $data); //Own Line
			$data = preg_replace('"&lt;auth&gt;(<br />|</p>)"', '<div class="auth">', $data); //Own Line
			$data = preg_replace('"&lt;auth&gt;"', '<div class="auth">', $data); //Own Line
			$data = preg_replace('"&lt;/auth&gt;"', '</div>', $data);
		}

		//Post Parse, fix <private>...</private> tags
		if ($this->isAuthenticated && ($this->isAdmin || $this->postCreator == $this->userID)) {
			//The first inline regex will not catch multi line <priv> but the second line will, so this works great!
			$data = preg_replace('"&lt;priv&gt;(.*?)&lt;/priv&gt;"', '<span class="private_inline">$1</span>', $data); //Own Line
			$data = preg_replace('"&lt;priv&gt;(<br />|</p>)"', '<div class="private">', $data); //Own Line
			$data = preg_replace('"&lt;priv&gt;"', '<div class="private">', $data); //Own Line
			$data = preg_replace('"&lt;/priv&gt;"', '</div>', $data);
		}

		//Post Parse [\n] (new line, like <br />)
		$data = preg_replace('"\[\\\n\]"', '<br />', $data);

		//Post Parse <link url> tag
		#$data = preg_replace('"&lt;link (.*?)\|(.*?)&gt;"', "<a href='$1'>$2</a>", $data);

		return $data;
	}


	/**
	 * Replace <highlight xxx>yyy</highlight> with embed content
	*/
	private function postParseHighlight($inline, $color, $data='')
	{
		$color = trim(strtolower($color));


		// Bootstrap ace colors
		/*$default        = 'rgba(255,255,0,0.6)'; #highlighter yellow
		#$default        = 'rgba(251,238,213,1)';
		$rgb['red']     = 'rgba(242,222,222,1)';
		$rgb['green']   = 'rgba(223,240,216,1)';
		$rgb['blue']    = 'rgba(217,237,247,1)';
		$rgb['yellow']  = 'rgba(252,248,227,1)';
		#$rgb['cyan']    = 'rgba(0,255,255,0.5)';
		#$rgb['magenta'] = 'rgba(255,0,255,0.5)';*/
		/*$default = '#DDDD72';
		$rgb['red'] = '#f2dede';
		$rgb['green'] = '#dff0d8';
		$rgb['yellow'] = '#fcf8e3';
		$rgb['blue'] = '#d9edf7';*/


		$default        = 'rgba(255,255,0,0.6)';
		$rgb['red']     = 'rgba(255,0,0,0.5)';
		$rgb['green']   = 'rgba(0,255,0,0.5)';
		$rgb['blue']    = 'rgba(0,0,255,0.5)';
		$rgb['yellow']  = 'rgba(255,255,0,0.6)';
		$rgb['cyan']    = 'rgba(0,255,255,0.5)';
		$rgb['magenta'] = 'rgba(255,0,255,0.5)';

		if ($color <> '') {
			if (isset($rgb[$color])) {
				$color = $rgb[$color];
			} elseif (preg_match('"#"', $color)) {
				$color = $color;
			}
		}
		if ($color == '') $color = $default;
		if ($inline) {
			return "<span class='highlight' style='background-color:$color'>$data</span>";
		} else {
			return "<div class='highlight' style='background-color:$color'>";
		}
	}

}
