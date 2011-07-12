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
		);
		
		foreach ( $tests as $test ) {
			$c = new CriterionHasLength( $test[1], $test[2] );
			$p = new Parameter( 'test' );
			$p->setUserValue( 'test', $test[3] );
			$this->assertEquals( $test[0], $c->validate( $p, array() )->isValid() );
		}
	}

}
