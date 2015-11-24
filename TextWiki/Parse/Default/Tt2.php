<?php

class Text_Wiki_Parse_Default_Tt2 extends Text_Wiki_Parse {

	var $regex = "/{{({*?.*}*?)}}/U";

	function process(&$matches)
	{
		$start = $this->wiki->addToken(
			$this->rule, array('type' => 'start')
		);

		$end = $this->wiki->addToken(
			$this->rule, array('type' => 'end')
		);

		return $start . $matches[1] . $end;
	}
}
