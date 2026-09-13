import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';

const BASE = 'wp-block-zvg-fse-token-flow';

/**
 * Editor form of the token diagram: source and outputs in the sidebar, the diagram on the canvas.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Diagram preview and its settings panels.
 */
export default function Edit( { attributes, setAttributes } ) {
	const source = attributes.source || {};
	const outputs = attributes.outputs || [];

	const updateOutput = ( index, key, value ) => {
		setAttributes( {
			outputs: outputs.map( ( item, i ) =>
				i === index ? { ...item, [ key ]: value } : item
			),
		} );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Source', 'zvg-fse' ) } initialOpen>
					<TextControl
						label={ __( 'Source: name', 'zvg-fse' ) }
						value={ source.name || '' }
						onChange={ ( value ) =>
							setAttributes( {
								source: { ...source, name: value },
							} )
						}
					/>
					<TextControl
						label={ __( 'Source: detail', 'zvg-fse' ) }
						value={ source.meta || '' }
						onChange={ ( value ) =>
							setAttributes( {
								source: { ...source, meta: value },
							} )
						}
					/>
				</PanelBody>
				<PanelBody title={ __( 'Outputs', 'zvg-fse' ) } initialOpen>
					{ outputs.map( ( output, index ) => (
						<div key={ index }>
							<TextControl
								label={ sprintf(
									/* translators: %d: position of the output in the diagram. */
									__( 'Output %d: name', 'zvg-fse' ),
									index + 1
								) }
								value={ output.name || '' }
								onChange={ ( value ) =>
									updateOutput( index, 'name', value )
								}
							/>
							<TextControl
								label={ sprintf(
									/* translators: %d: position of the output in the diagram. */
									__( 'Output %d: detail', 'zvg-fse' ),
									index + 1
								) }
								value={ output.meta || '' }
								onChange={ ( value ) =>
									updateOutput( index, 'meta', value )
								}
							/>
						</div>
					) ) }
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<p className={ BASE + '__source' }>
					<span className={ BASE + '__name' }>{ source.name }</span>
					<span className={ BASE + '__meta' }>{ source.meta }</span>
				</p>
				<div className={ BASE + '__trunk' } />
				<div className={ BASE + '__elbows' }>
					<div
						className={ BASE + '__elbow ' + BASE + '__elbow--left' }
					/>
					<div
						className={
							BASE + '__elbow ' + BASE + '__elbow--right'
						}
					/>
					<div className={ BASE + '__mid' } />
				</div>
				<ul className={ BASE + '__outputs' }>
					{ outputs.map( ( output, index ) => (
						<li key={ index } className={ BASE + '__output' }>
							<span className={ BASE + '__name' }>
								{ output.name }
							</span>
							<span className={ BASE + '__meta' }>
								{ output.meta }
							</span>
						</li>
					) ) }
				</ul>
			</div>
		</>
	);
}
