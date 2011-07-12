<?php

/**
 * Unit tests for Validators criteria.
 * 
 * @ingroup Validator
 * @since 0.4.8
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorCriteriaTests extends MediaWikiTestCase {
	
	/**
	 * Tests CriterionHasLength.
	 */
	public function testCriterionHasLength() {
		$tests = array(
			array( true, 0, 5, 'foo' ),
			array( false, 0, 5, 'foobar' ),
			array( false, 3, null, 'a' ),
			array( true, 3, null, 'aw<dfxdfwdxgtdfgdfhfdgsfdxgtffds' ),
			array( true, null, null, 'aw<dfxdfwdxgtdfgdfhfdgsfdxgtffds' ),
			array( true, null, null, '' ),
			array( false, 2, 3, '' ),
			array( true, 3, false, 'foo' ),
			array( false, 3, false, 'foobar' ),
		);
		
		foreach ( $tests as $test ) {
			$c = new CriterionHasLength( $test[1], $test[2] );
			$p = new Parameter( 'test' );
			$p->setUserValue( 'test', $test[3] );
			$this->assertEquals(
				$test[0],
				$c->validate( $p, array() )->isValid(),
				'Lenght of value "'. $test[3] . '" should ' . ( $test[0] ? '' : 'not ' ) . "be between $test[1] and $test[2] ."
			);
		}
	}

}
