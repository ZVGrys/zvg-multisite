import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const LABELS = window.zvgFseBuildLabels || [];

/**
 * Editor preview of the build switcher, dimmed while it is hidden on the front end.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Switcher preview and its settings panel.
 */
export default function Edit( { attributes, setAttributes } ) {
	const shown = attributes.showSwitcher;

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Build switcher', 'zvg-fse' ) }
					initialOpen
				>
					<ToggleControl
						label={ __( 'Show the build switcher', 'zvg-fse' ) }
						help={
							shown
								? __(
										'The links to the other two builds sit at the end of the menu.',
										'zvg-fse'
								  )
								: __(
										'Hidden on the front end. The block stays in the menu, so it can be switched back on here.',
										'zvg-fse'
								  )
						}
						checked={ !! shown }
						onChange={ ( value ) =>
							setAttributes( { showSwitcher: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div
				{ ...useBlockProps( {
					role: 'group',
					style: shown ? undefined : { opacity: 0.4 },
				} ) }
			>
				{ LABELS.map( ( label, index ) => (
					<span
						key={ label }
						className="wp-block-zvg-fse-build-switcher__link"
						aria-current={ index === 0 ? 'page' : undefined }
					>
						{ label }
					</span>
				) ) }
			</div>
		</>
	);
}
