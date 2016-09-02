<?php

/**
 * Created by PhpStorm.
 * User: milos.pejanovic
 * Date: 6/10/2016
 * Time: 10:16 AM
 */
namespace Common\Models;

use Common\Util\Validation;

class DocBlock {

	/**
	 * @var array
	 */
	private $annotations = [];

	/**
	 * @var array
	 */
	private $comments = [];

	/**
	 * DocBlock constructor.
	 * Copied from PHPUnit 3.7.29 Util/Test.php
	 * @param string $docBlock
	 */
	public function __construct(string $docBlock = null) {
		if(!Validation::isEmpty($docBlock)) {
			$annotations = array();
			$comments = array();

			// Strip away the docblock header and footer
			// to ease parsing of one line annotations
			$docBlock = substr($docBlock, 3, -2);

			$re = '/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m';
			if (preg_match_all($re, $docBlock, $matches)) {
				$numMatches = count($matches[0]);

				for ($i = 0; $i < $numMatches; ++$i) {
					$annotations[$matches['name'][$i]][] = $matches['value'][$i];
				}
			}

			$re = '/\*\s?([^@*]*)\r/';
			if (preg_match_all($re, $docBlock, $matches) && isset($matches[1])) {
				$numMatches = count($matches[1]);

				for ($i = 0; $i < $numMatches; ++$i) {
					$comments[] = $matches[1][$i];
				}
			}

			$this->comments = $comments;
			$this->annotations = $annotations;
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasAnnotation(string $name) {
		$result = true;
		if(!isset($this->annotations[$name][0])) {
			$result = false;
		}

		return $result;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function getFirstAnnotation(string $name) {
		if(!isset($this->annotations[$name][0])) {
			throw new \InvalidArgumentException('Annotation ' . $name . ' not found in docBlock.');
		}

		return $this->annotations[$name][0];
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function getAnnotation(string $name) {
		if(!isset($this->annotations[$name])) {
			throw new \InvalidArgumentException('Annotation ' . $name . ' not found in docBlock.');
		}

		return $this->annotations[$name];
	}

	/**
	 * @return array
	 */
	public function getAnnotations() {
		return $this->annotations;
	}

	/**
	 * @return array
	 */
	public function getComments() {
		return $this->comments;
	}
}