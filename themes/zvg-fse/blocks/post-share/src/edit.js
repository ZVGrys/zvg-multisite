import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const NETWORKS = window.zvgFseShareNetworks || [];

/**
 * Editor preview of the share links, one toggle per network.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Share list preview and its settings panel.
 */
export default function Edit( { attributes, setAttributes } ) {
	const chosen = NETWORKS.filter( ( network ) => attributes[ network.key ] );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Share links', 'zvg-fse' ) } initialOpen>
					<TextControl
						label={ __( 'Label', 'zvg-fse' ) }
						value={ attributes.label }
						placeholder={ __( 'Share this post', 'zvg-fse' ) }
						onChange={ ( value ) =>
							setAttributes( { label: value } )
						}
					/>
					{ NETWORKS.map( ( network ) => (
						<ToggleControl
							key={ network.key }
							label={ network.name }
							checked={ !! attributes[ network.key ] }
							onChange={ ( value ) =>
								setAttributes( { [ network.key ]: value } )
							}
						/>
					) ) }
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<p className="wp-block-zvg-fse-post-share__label">
					{ attributes.label || __( 'Share this post', 'zvg-fse' ) }
				</p>
				<ul className="wp-block-zvg-fse-post-share__list">
					{ chosen.map( ( network ) => (
						<li
							key={ network.key }
							className="wp-block-zvg-fse-post-share__item"
						>
							<span className="wp-block-zvg-fse-post-share__link">
								<svg
									className={
										'wp-block-zvg-fse-post-share__icon' +
										( network.stroke ? ' is-stroked' : '' )
									}
									viewBox="0 0 24 24"
									aria-hidden="true"
									focusable="false"
								>
									<path d={ network.icon } />
								</svg>
								{ network.name }
							</span>
						</li>
					) ) }
				</ul>
			</div>
		</>
	);
}
