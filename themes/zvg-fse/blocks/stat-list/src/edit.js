import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

const PLACEHOLDER = '—';
const WITH_UNIT = /^([0-9][0-9\s.,]*)\s*([A-Za-z%]{1,4})$/;

/**
 * Render a value the way the front end does, with a trailing unit in smaller type.
 *
 * @param {string} value Value as typed in the panel.
 *
 * @return {string|Array} Value, or the number followed by its unit.
 */
function renderValue( value ) {
	const parts = WITH_UNIT.exec( value );

	if ( ! parts ) {
		return value;
	}

	return [
		parts[ 1 ].trim(),
		<span key="unit" className="wp-block-zvg-fse-stat-list__unit">
			{ parts[ 2 ] }
		</span>,
	];
}

/**
 * Editor form of the stat list: stats in the sidebar, the definition list on the canvas.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Stat list preview and its settings panel.
 */
export default function Edit( { attributes, setAttributes } ) {
	const items = attributes.items || [];

	const update = ( index, key, value ) => {
		const next = items.map( ( item, i ) =>
			i === index ? { ...item, [ key ]: value } : item
		);
		setAttributes( { items: next } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Stats', 'zvg-fse' ) } initialOpen>
					{ items.map( ( item, index ) => (
						<div key={ index }>
							<TextControl
								label={ sprintf(
									/* translators: %d: position of the stat in the list. */
									__( 'Stat %d: label', 'zvg-fse' ),
									index + 1
								) }
								value={ item.label || '' }
								onChange={ ( value ) =>
									update( index, 'label', value )
								}
							/>
							<TextControl
								label={ sprintf(
									/* translators: %d: position of the stat in the list. */
									__( 'Stat %d: value', 'zvg-fse' ),
									index + 1
								) }
								value={ item.value || '' }
								placeholder={ PLACEHOLDER }
								onChange={ ( value ) =>
									update( index, 'value', value )
								}
							/>
							<Button
								variant="link"
								isDestructive
								onClick={ () =>
									setAttributes( {
										items: items.filter(
											( _, i ) => i !== index
										),
									} )
								}
							>
								{ sprintf(
									/* translators: %d: position of the stat in the list. */
									__( 'Remove stat %d', 'zvg-fse' ),
									index + 1
								) }
							</Button>
						</div>
					) ) }
					<Button
						variant="secondary"
						onClick={ () =>
							setAttributes( {
								items: [ ...items, { label: '', value: '' } ],
							} )
						}
					>
						{ __( 'Add stat', 'zvg-fse' ) }
					</Button>
				</PanelBody>
			</InspectorControls>
			<dl { ...useBlockProps() }>
				{ items.map( ( item, index ) => (
					<div
						key={ index }
						className="wp-block-zvg-fse-stat-list__item"
					>
						<dt className="wp-block-zvg-fse-stat-list__label">
							{ item.label || __( 'Label', 'zvg-fse' ) }
						</dt>
						<dd className="wp-block-zvg-fse-stat-list__value">
							{ item.value
								? renderValue( item.value )
								: PLACEHOLDER }
						</dd>
					</div>
				) ) }
			</dl>
		</>
	);
}
