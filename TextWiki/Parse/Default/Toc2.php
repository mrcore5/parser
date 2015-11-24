<?php

class Text_Wiki_Parse_Default_Toc2 extends Text_Wiki_Parse {

	var $regex = "/\n\[\[toc( .*)?\]\]\n/m";

	function process(&$matches)
	{
		$count = 0;

		if (isset($matches[1])) {
			$attr = $this->getAttrs(trim($matches[1]));
		} else {
			$attr = array();
		}

		$output = $this->wiki->addToken(
			$this->rule,
			array(
				'type' => 'list_start',
				'level' => 0,
				'attr' => $attr
			)
		);

		foreach ($this->wiki->getTokens('Heading2') as $key => $val) {
			#mReschke 2013-07-15 <notoc>
			if (!preg_match("'<notoc>'", @$val[1]['text'])) {

				if ($val[1]['type'] != 'start') {
					continue;
				}

				$options = array(
					'type'  => 'item_start',
					'id'	=> $val[1]['id'],
					'level' => $val[1]['level'],
					'count' => $count ++
				);

				$output .= $this->wiki->addToken($this->rule, $options);

				$output .= trim($val[1]['text']);

				$output .= $this->wiki->addToken(
					$this->rule,
					array(
						'type' => 'item_end',
						'level' => $val[1]['level']
					)
				);
			}
		}

		$output .= $this->wiki->addToken(
			$this->rule, array(
				'type' => 'list_end',
				'level' => 0
			)
		);

		return "\n$output\n";
	}
}
