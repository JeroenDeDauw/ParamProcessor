<?php

namespace ParamProcessor\Tests\Definitions;

use ParamProcessor\Options;

/**
 * Unit test for the NumericParam class.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.0
 *
 * @ingroup ParamProcessor
 * @ingroup Test
 *
 * @group ParamProcessor
 * @group ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class NumericParamTest extends ParamDefinitionTest {

	public function lowerBoundProvider() {
		return array(
			array( 42, 42, true ),
			array( 42, 41, false ),
			array( 42, 43, true ),
			array( false, 43, true ),
			array( false, 0, true ),
			array( false, -100, true ),
			array( -100, -100, true ),
			array( -99, -100, false ),
			array( -101, -100, true ),
		);
	}

	/**
	 * @dataProvider lowerBoundProvider
	 */
	public function testSetLowerBound( $bound, $testValue, $validity ) {
		$definition = $this->getEmptyInstance();
		$definition->setArrayValues( array( 'lowerbound' => $bound ) );

		$this->validate( $definition, (string)$testValue, $validity );

		$options = new Options();
		$options->setRawStringInputs( false );
		$this->validate( $definition, $testValue, $validity, $options );
	}

	public function upperBoundProvider() {
		return array(
			array( 42, 42, true ),
			array( 42, 41, true ),
			array( 42, 43, false ),
			array( false, 43, true ),
			array( false, 0, true ),
			array( false, -100, true ),
			array( -100, -100, true ),
			array( -99, -100, true ),
			array( -101, -100, false ),
		);
	}

	/**
	 * @dataProvider upperBoundProvider
	 */
	public function testSetUpperBound( $bound, $testValue, $validity ) {
		$definition = $this->getEmptyInstance();
		$definition->setArrayValues( array( 'upperbound' => $bound ) );

		$this->validate( $definition, (string)$testValue, $validity );

		$options = new Options();
		$options->setRawStringInputs( false );
		$this->validate( $definition, $testValue, $validity, $options );
	}

}
