<?php

/**
 * Simple class to get a HTML input for the parameter.
 * Usable for when creating a GUI from a parameter list.
 * 
 * Based on 'addOptionInput' from Special:Ask in SMW 1.5.6.
 * 
 * TODO: support lists (now only done when values are restricted to an array)
 * TODO: nicify HTML
 * 
 * @since 0.4.6
 * 
 * @file ParameterInput.php
 * @ingroup Validator
 * 
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw
 */
class ParameterInput {
	
	/**
	 * The parameter to print an input for.
	 * 
	 * @since O.4.6
	 * 
	 * @var Parameter
	 */
	protected $param;
	
	/**
	 * The current value for the parameter. When provided,
	 * it'll be used as value for the input, otherwise the
	 * parameters default value will be used.
	 * 
	 * @since 0.4.6
	 * 
	 * @var mixed: string or false
	 */
	protected $currentValue;
	
	/**
	 * Name for the input.
	 * 
	 * @since 0.6.4
	 * 
	 * @var string
	 */
	protected $inputName;
	
	/**
	 * COnstructor.
	 * 
	 * @since 0.4.6
	 * 
	 * @param Parameter $param
	 * @param mixed $currentValue
	 */
	public function __construct( Parameter $param, $currentValue = false ) {
		$this->param = $param;
		$this->currentValue = $currentValue;
		$this->inputName = $param->getName();
	}
	
	/**
	 * Sets the name for the input; defaults to the name of the parameter.
	 * 
	 * @since 0.6.4
	 * 
	 * @param string $name
	 */
	public function setInputName( $name ) {
		$this->inputName = name;
	}
	
	/**
	 * Returns the HTML for the parameter input.
	 * 
	 * @since 0.4.6
	 * 
	 * @return string
	 */
	public function getHtml() {
		$html = '';
		$valueList = array();
		
        foreach ( $this->param->getCriteria() as $criterion ) {
    		if ( $criterion instanceof CriterionInArray ) {
    			$valueList[] = $criterion->getAllowedValues();
    		}	
        }

        if ( count( $valueList ) > 0 ) {
        	$valueList = call_user_func_array( 'array_intersect', $valueList );
        	$html = $this->param->isList() ? $this->getChckboxListInput( $valueList ) : $this->getSelectInput( $valueList );
        }
        else {
			switch ( $this->param->getType() ) {
				case Paramater::TYPE_CHAR:
				case Parameter::TYPE_FLOAT:
				case Parameter::TYPE_INTEGER:
				case Parameter::TYPE_NUMBER:
					$html = $this->getNumberInput();
					break;
				case Parameter::TYPE_BOOLEAN:
					$html = $this->getBooleanInput();
				case Paramater::TYPE_STRING:
				default:
					$html = $this->getIntInput();
					break;					
			}
        }
        
        return $html;
	}
	
	/**
	 * Returns the value to initially display with the input.
	 * 
	 * @since 0.4.6
	 * 
	 * @return mixed
	 */
	protected function getValueToUse() {
		return $this->currentValue === false ? $this->param->getDefault() : $this->currentValue; 
	}
	
	/**
	 * Gets a short text input suitable for numbers.
	 * 
	 * @since 0.4.6
	 * 
	 * @return string
	 */		
	protected function getNumberInput() {
		return Html::input(
			$this->inputName,
			$this->currentValue,
			'text',
			array(
				'size' => 6
			)
		);
	}
	
	/**
	 * Gets a text input for a string.
	 * 
	 * @since 0.4.6
	 * 
	 * @return string
	 */		
	protected function getStrInput() {
		return Html::input(
			$this->inputName,
			$this->currentValue,
			'text',
			array(
				'size' => 32
			)
		);
	}
	
	/**
	 * Gets a checkbox.
	 * 
	 * @since 0.4.6
	 * 
	 * @return string
	 */	
	protected function getBooleanInput() {
		return Xml::check(
			$this->inputName,
			$this->currentValue
		);
	}	
	
	/**
	 * Gets a select menue for the provided values.
	 * 
	 * @since 0.4.6
	 * 
	 * @param array $valueList
	 * 
	 * @return string
	 */	
	protected function getSelectInput( array $valueList ) {
		$options = array();
		$options[] = '<option value=""></option>';
		
		foreach ( $valueList as $value ) {
			$options[] =
				'<option value="' . htmlspecialchars( $value ) . '"' .
					( in_array( $value, $this->currentValue ) ? ' selected' : '' ) . '>' . htmlspecialchars( $value ) .
				'</option>'; // TODO
		}

		return Html::element(
			'select',
			array(
				'name' => $this->inputName
			),
			implode( "\n", $options )
		);
	}
	
	/**
	 * Gets a list of input boxes for the provided values.
	 * 
	 * @since 0.4.6
	 * 
	 * @param array $valueList
	 * 
	 * @return string
	 */
	protected function getCheckboxListInput( array $valueList ) {
		$boxes = array();

		foreach ( $valueList as $value ) {
			$boxes[] = Html::rawElement(
				'span',
				array(
					'style' => 'white-space: nowrap; padding-right: 5px;'
				),
				Xml::check(
					$this->inputName . '[' . htmlspecialchars( $value ). ']',
					in_array( $value, $this->currentValue )
				) .
				Html::element( 'tt', $value )
			);
		}
		
		return implode( "\n", $boxes );
	}
	
}
