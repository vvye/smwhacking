<?php

	namespace JBBCode;

	require_once "CodeDefinition.php";

	/**
	 * Implements the builder pattern for the CodeDefinition class. A builder
	 * is the recommended way of constructing CodeDefinition objects.
	 *
	 * @author jbowens
	 */
	class CodeDefinitionBuilder
	{

		protected $tagName;
		protected $useOption = false;
		protected $replacementText;
		protected $parseContent = true;
		protected $nestLimit = -1;
		protected $optionValidator = [];
		protected $bodyValidator = null;


		public function __construct($tagName, $replacementText)
		{
			$this->tagName = $tagName;
			$this->replacementText = $replacementText;
		}


		public function setTagName($tagName)
		{
			$this->tagName = $tagName;

			return $this;
		}


		public function setReplacementText($replacementText)
		{
			$this->replacementText = $replacementText;

			return $this;
		}


		public function setUseOption($option)
		{
			$this->useOption = $option;

			return $this;
		}


		public function setParseContent($parseContent)
		{
			$this->parseContent = $parseContent;

			return $this;
		}


		public function setNestLimit($limit)
		{
			if (!is_int($limit) || ($limit <= 0 && -1 != $limit))
			{
				throw new \InvalidArgumentException("A nest limit must be a positive integer " .
					"or -1.");
			}
			$this->nestLimit = $limit;

			return $this;
		}


		public function setOptionValidator(\JBBCode\InputValidator $validator, $option = null)
		{
			if (empty($option))
			{
				$option = $this->tagName;
			}
			$this->optionValidator[$option] = $validator;

			return $this;
		}


		public function setBodyValidator(\JBBCode\InputValidator $validator)
		{
			$this->bodyValidator = $validator;

			return $this;
		}

		/**
		 * Removes the attached option validator if one is attached.
		 */
		public function removeOptionValidator()
		{
			$this->optionValidator = [];

			return $this;
		}

		/**
		 * Removes the attached body validator if one is attached.
		 */
		public function removeBodyValidator()
		{
			$this->bodyValidator = null;

			return $this;
		}

		public function build()
		{
			$definition = CodeDefinition::construct($this->tagName,
				$this->replacementText,
				$this->useOption,
				$this->parseContent,
				$this->nestLimit,
				$this->optionValidator,
				$this->bodyValidator);

			return $definition;
		}


	}
