<?php

/**
 * Class for the 'describe' parser hooks.
 * 
 * @since 0.4.3
 * 
 * @file Validator_Describe.php
 * @ingroup Validator
 * 
 * @licence GNU GPL v3 or later
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorDescribe extends ParserHook {
	
	/**
	 * No LST in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */
	public static function staticMagic( array &$magicWords, $langCode ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->magic( $magicWords, $langCode );
	}
	
	/**
	 * No LST in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */	
	public static function staticInit( Parser &$wgParser ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->init( $wgParser );
	}

	/**
	 * Gets the name of the parser hook.
	 * @see ParserHook::getName
	 * 
	 * @since 0.4.3
	 * 
	 * @return string
	 */
	protected function getName() {
		return 'describe';
	}	
	
	/**
	 * Returns an array containing the parameter info.
	 * @see ParserHook::getParameterInfo
	 * 
	 * @since 0.4.3
	 * 
	 * @return array of Parameter
	 */
	protected function getParameterInfo( $type ) {
		$params = array();

		$params['hooks'] = new ListParameter( 'hooks' );
		$params['hooks']->setDefault( array_keys( ParserHook::getRegisteredParserHooks() ) );
		$params['hooks']->setDescription( wfMsg( 'validator-describe-par-hooks' ) );
		
		$params['pre'] = new Parameter( 'pre', Parameter::TYPE_BOOLEAN );
		$params['pre']->setDefault( 'off' );
		$params['pre']->setDescription( wfMsg( 'validator-describe-par-pre' ) );
		
 		return $params;
	}	
	
	/**
	 * Returns the list of default parameters.
	 * @see ParserHook::getDefaultParameters
	 * 
	 * @since 0.4.3
	 * 
	 * @return array
	 */
	protected function getDefaultParameters( $type ) {
		return array( 'hooks' );
	}
	
	/**
	 * Renders and returns the output.
	 * @see ParserHook::render
	 * 
	 * @since 0.4.3
	 * 
	 * @param array $parameters
	 * 
	 * @return string
	 */
	public function render( array $parameters ) {
		$parts = array();
		
		// Loop over the hooks for which the docs should be added.
		foreach ( $parameters['hooks'] as $hookName ) {
			$parserHook = $this->getParserHookInstance( $hookName );
			
			if ( $parserHook === false ) {
				$parts[] = wfMsgExt( 'validator-describe-notfound', 'parsemag', $hookName );
			}
			else {
				$parts[] = $this->getParserHookDescription( $hookName, $parameters, $parserHook );
			}
		}
		
		// Parse the wikitext to HTML.
		$output = $this->parser->parse(
			implode( "\n\n", $parts ),
			$this->parser->mTitle,
			$this->parser->mOptions,
			true,
			false
		);
		
		// This str_replace is a hack to allow for placing <pre>s into <pre>s, without breaking the outer ones.
		return str_replace( 'pre!&gt;', 'pre&gt;', $output->getText() );
	}
	
	/**
	 * Returns the wikitext description for a single parser hook.
	 * 
	 * @since 0.4.3
	 * 
	 * @param string $hookName
	 * @param array $parameters
	 * @param ParserHook $parserHook
	 * 
	 * @return string
	 */
	protected function getParserHookDescription( $hookName, array $parameters, ParserHook $parserHook ) {
		global $wgLang;
		
		$descriptionData = $parserHook->getDescriptionData( ParserHook::TYPE_TAG ); // TODO

		$description = "<h2> {$hookName} </h2>\n\n";
		
		if ( $descriptionData['description'] !== false ) {
			$description .= wfMsgExt( 'validator-describe-descriptionmsg', 'parsemag', $descriptionData['description'] );
			$description .= "\n\n";
		}
		
		if ( count( $descriptionData['names'] ) > 1 ) {
			$aliases = array();
			
			foreach ( $descriptionData['names'] as $name ) {
				if ( $name != $hookName ) {
					$aliases[] = $name;
				}
			}
			
			$description .= wfMsgExt( 'validator-describe-aliases', 'parsemag', $wgLang->listToText( $aliases ), count( $aliases ) );
			$description .= "\n\n";
		}
		
		if ( $parserHook->forTagExtensions || $parserHook->forParserFunctions ) {
			if ( $parserHook->forTagExtensions && $parserHook->forParserFunctions ) {
				$description .= wfMsg( 'validator-describe-bothhooks' );
			}
			else if ( $parserHook->forTagExtensions ) {
				$description .= wfMsg( 'validator-describe-tagextension' );
			}
			else { // if $parserHook->forParserFunctions
				$description .= wfMsg( 'validator-describe-parserfunction' );
			}
			
			$description .= "\n\n";
		}
		
		$description .= $this->getParameterTable( $descriptionData['parameters'], $descriptionData['defaults'] );
		
		if ( $parserHook->forTagExtensions || $parserHook->forParserFunctions ) {
			$description .= $this->getSyntaxExamples( $hookName, $descriptionData['parameters'], $parserHook );
		}
		
		if ( $parameters['pre'] ) {
			$description = '<pre>' . $description . '</pre>';
		}
		
		return $description;
	}
	
	/**
	 * Returns the wikitext for some syntax examples.
	 * 
	 * @since 0.4.3
	 * 
	 * @param string $hookName
	 * @param array $parameters
	 * @param ParserHook $parserHook
	 * 
	 * @return string
	 */	
	protected function getSyntaxExamples( $hookName, array $parameters, ParserHook $parserHook ) {
		$result = "<h3>Syntax</h3>\n\n"; // TODO: i18n
		
		$params = array();
		
		foreach ( $parameters as $parameter ) {
			$params[$parameter->getName()] = '{' . $parameter->getType() . '}';
		}
		
		if ( $parserHook->forTagExtensions ) {
			$result .= "<pre!><nowiki>\n" . Xml::element(
				$hookName,
				$params
			) . "\n</nowiki></pre!>";
		}
		
		if ( $parserHook->forParserFunctions ) {
			// TODO
		}
		
		return $result;
	}
	
	/**
	 * Returns the wikitext for a table listing the provided parameters.
	 *  
	 * @since 0.4.3
	 *  
	 * @param array $parameters
	 * @param array $defaults
	 * 
	 * @return string
	 */
	protected function getParameterTable( array $parameters, array $defaults ) {
		$tableRows = array();
		
		foreach ( $parameters as $parameter ) {
			$tableRows[] = $this->getDescriptionRow( $parameter, $defaults );
		}
		
		if ( count( $tableRows ) > 0 ) { // i18n
			$tableRows = array_merge( array( '! #
! Parameter
! Aliases
! Type
! Default
! Description' ), $tableRows );
			
		$table = implode( "\n|-\n", $tableRows );
		
		$table = <<<EOT
<h3>Parameters</h3>

{| class="wikitable sortable"
{$table}
|}
EOT;
		}
		
		return $table;
	}
	
	/**
	 * Returns the wikitext for a table row describing a single parameter.
	 * 
	 * @since 0.4.3
	 *  
	 * @param Parameter $parameter
	 * @param array $defaults
	 * 
	 * @return string
	 */
	protected function getDescriptionRow( Parameter $parameter, array $defaults ) {
		$aliases = $parameter->getAliases();
		$aliases = count( $aliases ) > 0 ? implode( ', ', $aliases ) : '-';
		
		$default = $parameter->isRequired() ? "''required''" : $parameter->getDefault();
		if ( is_array( $default ) ) $default = implode( ', ', $default );  
		if ( $default === '' ) $default = "''empty''";
		
		$description = $parameter->getDescription();
		if ( $description === false ) $description = '-'; 
		
		// TODO: some mapping to make the type names more user-friendly
		$type = $parameter->getType();
		if ( $parameter->isList() ) $type = wfMsgExt( 'validator-describe-listtype', 'parsemag', $type );
		
		$number = 0;
		$isDefault = false;
		
		foreach ( $defaults as $default ) {
			$number++;
			
			if ( $default == $parameter->getName() ) {
				$isDefault = true;
				break;
			}
		}
		
		if ( !$isDefault ) {
			$number = '-';
		}
		
		return <<<EOT
| {$number}
| {$parameter->getName()}
| {$aliases}
| {$type}
| {$default}
| {$description}
EOT;
	}
	
	/**
	 * Returns an instance of the class handling the specified parser hook,
	 * or false if there is none.
	 * 
	 * @since 0.4.3
	 * 
	 * @param string $parserHookName
	 * 
	 * @return mixed ParserHook or false
	 */
	protected function getParserHookInstance( $parserHookName ) {
		$className = ParserHook::getHookClassName( $parserHookName );
		return $className !== false && class_exists( $className ) ? new $className() : false;
	}
	
	/**
	 * @see ParserHook::getDescription()
	 */
	public function getDescription() {
		return wfMsg( 'validator-describe-description' );
	}
	
}