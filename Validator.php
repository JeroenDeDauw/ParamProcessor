<?php

/**
 * Initialization file for the Validator extension.
 * Extension documentation: http://www.mediawiki.org/wiki/Extension:Validator
 *
 * You will be validated. Resistance is futile.
 *
 * @file Validator.php
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documenation group collects source code files belonging to Validator.
 *
 * Please do not use this group name for other code.
 *
 * @defgroup Validator Validator
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.16c', '<' ) ) {
	die( '<b>Error:</b> This version of Validator requires MediaWiki 1.16 or above.' );
}

if ( defined( 'Validator_VERSION' ) ) {
	die( '<b>Error:</b> Tried to include Validator a second time. Please make sure you are including it before any extensions that make use of it.' );
}

// Include the ValueHandler extension if that hasn't been done yet, since it's required for Validator to work.
if ( !defined( 'ValueHandler_VERSION' ) ) {
	@include_once( dirname( __FILE__ ) . '/ValueHandler/ValueHandler.php' );
}

// Only initialize the extension when all dependencies are present.
if ( !defined( 'ValueHandler_VERSION' ) ) {
	die( '<b>Error:</b> You need to have <a href="http://www.mediawiki.org/wiki/Extension:ValueHandler">ValueHandler</a> installed in order to use <a href="http://www.mediawiki.org/wiki/Extension:Validator">Validator</a>.<br />' );
}

define( 'Validator_VERSION', '1.0 alpha' );

// Register the internationalization file.
$wgExtensionMessagesFiles['Validator'] = dirname( __FILE__ ) . '/Validator.i18n.php';
$wgExtensionMessagesFiles['ValidatorMagic'] = dirname( __FILE__ ) . '/Validator.i18n.magic.php';

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Validator',
	'version' => Validator_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Validator',
	'descriptionmsg' => 'validator-desc',
);

$incDir = dirname( __FILE__ ) . '/includes/';

// Autoload the classes.
$wgAutoloadClasses['ValidatorHooks']				= $incDir . '../Validator.hooks.php';
$wgAutoloadClasses['ValidatorSettings']		  		= $incDir . '../Validator.settings.php';

// includes
$wgAutoloadClasses['Param']				  			= $incDir . 'Param.php';
$wgAutoloadClasses['ParamDefinitionFactory']		= $incDir . 'ParamDefinitionFactory.php';
$wgAutoloadClasses['ParamDefinition']				= $incDir . 'ParamDefinition.php';
$wgAutoloadClasses['ParameterInput']			 	= $incDir . 'ParameterInput.php';
$wgAutoloadClasses['ParserHook']				 	= $incDir . 'ParserHook.php';
$wgAutoloadClasses['Validator']				  		= $incDir . 'Validator.php';
$wgAutoloadClasses['ValidatorOptions']				= $incDir . 'ValidatorOptions.php';
$wgAutoloadClasses['TopologicalSort']				= $incDir . 'TopologicalSort.php';
$wgAutoloadClasses['ValidationError']				= $incDir . 'ValidationError.php';
$wgAutoloadClasses['ValidationErrorHandler']	 	= $incDir . 'ValidationErrorHandler.php';
$wgAutoloadClasses['IParam']				  		= $incDir . 'IParam.php';
$wgAutoloadClasses['IParamDefinition']				= $incDir . 'IParamDefinition.php';

// includes/definitions
$wgAutoloadClasses['DimensionParam']		 		= $incDir . 'definitions/DimensionParam.php';
$wgAutoloadClasses['ParamDefinition']		 		= $incDir . 'definitions/ParamDefinition.php';
$wgAutoloadClasses['StringParam']		 			= $incDir . 'definitions/StringParam.php';


// tests
$wgAutoloadClasses['Validator\Test\NumericParamTest']		= dirname( __FILE__ ) . '/tests/definitions/NumericParamTest.php';
$wgAutoloadClasses['Validator\Test\ParamDefinitionTest']	= dirname( __FILE__ ) . '/tests/definitions/ParamDefinitionTest.php';

// parser hooks
$wgAutoloadClasses['ValidatorDescribe']		  		= $incDir . 'parserHooks/Validator_Describe.php';
$wgAutoloadClasses['ValidatorListErrors']			= $incDir . 'parserHooks/Validator_ListErrors.php';

# Registration of the listerrors parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorListErrors::staticInit';

# Registration of the describe parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorDescribe::staticInit';

// Since 0.4.8
$wgHooks['UnitTestsList'][] = 'ValidatorHooks::registerUnitTests';



$egValidatorSettings = array();

$egParamDefinitions = array(
	'boolean' => array( // Parameter::TYPE_BOOLEAN
		'string-parser' => 'BoolParser',
		'validation-callback' => 'is_bool',
	),
	'float' => array( // Parameter::TYPE_FLOAT
		'string-parser' => 'FloatParser',
		'validation-callback' => 'is_float',
		'validator' => 'RangeValidator',
	),
	'integer' => array( // Parameter::TYPE_INTEGER
		'string-parser' => 'IntParser',
		'validation-callback' => 'is_int',
		'validator' => 'RangeValidator',
	),
	'string' => array( // Parameter::TYPE_STRING
		'validator' => 'StringValidator',
	),
	'title' => array( // Parameter::TYPE_TITLE
		'string-parser' => 'TitleParser',
		'validator' => 'TitleValidator',
	),
	'dimension' => array(
		'definition' => 'DimensionParam',
		'validator' => 'DimensionValidator',
	),
);





// Deprecated!
$wgAutoloadClasses['CriterionValidationResult']  	= $incDir . 'CriterionValidationResult.php';
$wgAutoloadClasses['ItemParameterCriterion']	 	= $incDir . 'ItemParameterCriterion.php';
$wgAutoloadClasses['ItemParameterManipulation']  	= $incDir . 'ItemParameterManipulation.php';
$wgAutoloadClasses['ListParameter']			  		= $incDir . 'ListParameter.php';
$wgAutoloadClasses['ListParameterCriterion']	 	= $incDir . 'ListParameterCriterion.php';
$wgAutoloadClasses['ListParameterManipulation']  	= $incDir . 'ListParameterManipulation.php';
$wgAutoloadClasses['Parameter']				  		= $incDir . 'Parameter.php';
$wgAutoloadClasses['ParameterCriterion']		 	= $incDir . 'ParameterCriterion.php';
$wgAutoloadClasses['ParameterManipulation']	  		= $incDir . 'ParameterManipulation.php';

// Deprecated!
$wgAutoloadClasses['CriterionHasLength']		 	= $incDir . 'criteria/CriterionHasLength.php';
$wgAutoloadClasses['CriterionInArray']		   		= $incDir . 'criteria/CriterionInArray.php';
$wgAutoloadClasses['CriterionInRange']		   		= $incDir . 'criteria/CriterionInRange.php';
$wgAutoloadClasses['CriterionIsFloat']		   		= $incDir . 'criteria/CriterionIsFloat.php';
$wgAutoloadClasses['CriterionIsInteger']		 	= $incDir . 'criteria/CriterionIsInteger.php';
$wgAutoloadClasses['CriterionIsNumeric']		 	= $incDir . 'criteria/CriterionIsNumeric.php';
$wgAutoloadClasses['CriterionIsTitle']		   		= $incDir . 'criteria/CriterionIsTitle.php';
$wgAutoloadClasses['CriterionItemCount']		 	= $incDir . 'criteria/CriterionItemCount.php';
$wgAutoloadClasses['CriterionMatchesRegex']	  		= $incDir . 'criteria/CriterionMatchesRegex.php';
$wgAutoloadClasses['CriterionNotEmpty']		  		= $incDir . 'criteria/CriterionNotEmpty.php';
$wgAutoloadClasses['CriterionTrue']			  		= $incDir . 'criteria/CriterionTrue.php';
$wgAutoloadClasses['CriterionUniqueItems']	   		= $incDir . 'criteria/CriterionUniqueItems.php';

// Deprecated!
$wgAutoloadClasses['ParamManipulationBoolean']   	= $incDir . 'manipulations/ParamManipulationBoolean.php';
$wgAutoloadClasses['ParamManipulationFloat']	 	= $incDir . 'manipulations/ParamManipulationFloat.php';
$wgAutoloadClasses['ParamManipulationFunctions'] 	= $incDir . 'manipulations/ParamManipulationFunctions.php';
$wgAutoloadClasses['ParamManipulationImplode']   	= $incDir . 'manipulations/ParamManipulationImplode.php';
$wgAutoloadClasses['ParamManipulationInteger']   	= $incDir . 'manipulations/ParamManipulationInteger.php';
$wgAutoloadClasses['ParamManipulationString']		= $incDir . 'manipulations/ParamManipulationString.php';
$wgAutoloadClasses['ParamManipulationTitle']	 	= $incDir . 'manipulations/ParamManipulationTitle.php';



unset( $incDir );