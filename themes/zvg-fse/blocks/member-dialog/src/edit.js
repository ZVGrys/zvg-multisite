import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __, _x } from '@wordpress/i18n';

/**
 * Editor placeholder of the team dialog, with its button and link text in the sidebar.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Placeholder and its settings panel.
 */
export default function Edit( { attributes, setAttributes } ) {
	const defaultCloseLabel = _x( 'Close', 'Team dialog button', 'zvg-fse' );
	const defaultLinkText = _x( 'Get in touch', 'Team dialog link', 'zvg-fse' );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Dialog text', 'zvg-fse' ) } initialOpen>
					<TextControl
						label={ __( 'Close button', 'zvg-fse' ) }
						value={ attributes.closeLabel }
						placeholder={ defaultCloseLabel }
						onChange={ ( value ) =>
							setAttributes( { closeLabel: value } )
						}
					/>
					<TextControl
						label={ __( 'Link text', 'zvg-fse' ) }
						value={ attributes.linkText }
						placeholder={ defaultLinkText }
						onChange={ ( value ) =>
							setAttributes( { linkText: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<p
				{ ...useBlockProps( {
					className: 'zvg-fse-dialog__placeholder',
				} ) }
			>
				{ _x( 'Team member dialog', 'Editor placeholder', 'zvg-fse' ) }
			</p>
		</>
	);
}
