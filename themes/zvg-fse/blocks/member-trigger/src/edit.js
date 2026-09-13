import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __, _x } from '@wordpress/i18n';

/**
 * Editor preview of the card button that opens the member profile.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Disabled button and its settings panel.
 */
export default function Edit( { attributes, setAttributes } ) {
	const defaultLabel = _x( 'Read profile', 'Team member button', 'zvg-fse' );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Card button', 'zvg-fse' ) } initialOpen>
					<TextControl
						label={ __( 'Button text', 'zvg-fse' ) }
						value={ attributes.toggleLabel }
						placeholder={ defaultLabel }
						onChange={ ( value ) =>
							setAttributes( { toggleLabel: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<button
					className="zvg-fse-member__toggle"
					type="button"
					disabled
				>
					{ attributes.toggleLabel || defaultLabel }
				</button>
			</div>
		</>
	);
}
