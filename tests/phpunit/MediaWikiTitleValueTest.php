<?php

namespace ParamProcessor\Tests;

use DataValues\Tests\DataValueTest;
use ParamProcessor\MediaWikiTitleValue;

/**
 * @covers ParamProcessor\MediaWikiTitleValue
 *
 * @file
 * @since 0.1
 *
 * @ingroup DataValue
 *
 * @group ParamProcessor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiTitleValueTest extends DataValueTest {

	/**
	 * @see DataValueTest::getClass
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getClass() {
		return 'ParamProcessor\MediaWikiTitleValue';
	}

	/**
	 * @see DataValueTest::constructorProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function constructorProvider() {
		$argLists = array();

		$argLists[] = array( false );
		$argLists[] = array( false, 42 );
		$argLists[] = array( false, array() );
		$argLists[] = array( false, false );
		$argLists[] = array( false, true );
		$argLists[] = array( false, null );
		$argLists[] = array( false, 'foo' );
		$argLists[] = array( false, '' );
		$argLists[] = array( false, ' foo bar baz foo bar baz foo bar baz foo bar baz foo bar baz foo bar baz ' );

		$argLists[] = array( true, \Title::newMainPage() );
		$argLists[] = array( true, \Title::newFromText( 'Foobar' ) );

		return $argLists;
	}

	/**
	 * @dataProvider instanceProvider
	 * @param MediaWikiTitleValue $titleValue
	 * @param array $arguments
	 */
	public function testGetValue( MediaWikiTitleValue $titleValue, array $arguments ) {
		$this->assertEquals( $arguments[0]->getFullText(), $titleValue->getValue()->getFullText() );
	}

}