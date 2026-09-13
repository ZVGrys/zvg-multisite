import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

const BUILDS = [ 'fse', 'elementor', 'acf' ];
const DEFAULTS = window.zvgFseChooserDefaults || {};

/**
 * Editor form of the build chooser: questions, verdicts and definitions in the sidebar,
 * the server render on the canvas.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Server-rendered preview and its settings panels.
 */
export default function Edit( { attributes, setAttributes } ) {
	const steps = attributes.steps.length
		? attributes.steps
		: DEFAULTS.steps || [];
	const verdicts = Object.keys( attributes.verdicts ).length
		? attributes.verdicts
		: DEFAULTS.verdicts || {};
	const definitions = attributes.definitions.length
		? attributes.definitions
		: DEFAULTS.definitions || [];

	const buildLabel = ( build ) =>
		( verdicts[ build ] && verdicts[ build ].title ) || build;

	const setStep = ( index, key, value ) =>
		setAttributes( {
			steps: steps.map( ( step, i ) =>
				i === index ? { ...step, [ key ]: value } : step
			),
		} );

	const setChoice = ( stepIndex, choiceIndex, key, value ) =>
		setStep(
			stepIndex,
			'choices',
			steps[ stepIndex ].choices.map( ( choice, i ) =>
				i === choiceIndex ? { ...choice, [ key ]: value } : choice
			)
		);

	const setVerdict = ( build, key, value ) =>
		setAttributes( {
			verdicts: {
				...verdicts,
				[ build ]: { ...verdicts[ build ], [ key ]: value },
			},
		} );

	const setDefinition = ( index, key, value ) =>
		setAttributes( {
			definitions: definitions.map( ( item, i ) =>
				i === index ? { ...item, [ key ]: value } : item
			),
		} );

	return (
		<>
			<InspectorControls>
				{ steps.map( ( step, stepIndex ) => (
					<PanelBody
						key={ 'step-' + stepIndex }
						title={ sprintf(
							/* translators: %d: position of the question in the questionnaire. */
							__( 'Question %d', 'zvg-fse' ),
							stepIndex + 1
						) }
						initialOpen={ false }
					>
						<TextControl
							label={ sprintf(
								/* translators: %d: position of the question in the questionnaire. */
								__( 'Question %d: text', 'zvg-fse' ),
								stepIndex + 1
							) }
							value={ step.question || '' }
							onChange={ ( value ) =>
								setStep( stepIndex, 'question', value )
							}
						/>
						{ ( step.choices || [] ).map(
							( choice, choiceIndex ) => (
								<div key={ 'choice-' + choiceIndex }>
									<TextControl
										label={ sprintf(
											/* translators: 1: question number, 2: answer number. */
											__(
												'Question %1$d · answer %2$d: label',
												'zvg-fse'
											),
											stepIndex + 1,
											choiceIndex + 1
										) }
										value={ choice.label || '' }
										onChange={ ( value ) =>
											setChoice(
												stepIndex,
												choiceIndex,
												'label',
												value
											)
										}
									/>
									{ BUILDS.map( ( build ) => (
										<TextControl
											key={ build }
											type="number"
											label={ sprintf(
												/* translators: 1: answer number, 2: build name. */
												__(
													'Answer %1$d: points for %2$s',
													'zvg-fse'
												),
												choiceIndex + 1,
												buildLabel( build )
											) }
											value={
												typeof choice[ build ] ===
												'number'
													? String( choice[ build ] )
													: '0'
											}
											onChange={ ( value ) =>
												setChoice(
													stepIndex,
													choiceIndex,
													build,
													parseInt( value, 10 ) || 0
												)
											}
										/>
									) ) }
								</div>
							)
						) }
					</PanelBody>
				) ) }
				<PanelBody
					title={ __( 'Verdicts', 'zvg-fse' ) }
					initialOpen={ false }
				>
					{ BUILDS.map( ( build ) => (
						<div key={ build }>
							<TextControl
								label={ sprintf(
									/* translators: %s: build name. */
									__( '%s: title', 'zvg-fse' ),
									buildLabel( build )
								) }
								value={
									( verdicts[ build ] &&
										verdicts[ build ].title ) ||
									''
								}
								onChange={ ( value ) =>
									setVerdict( build, 'title', value )
								}
							/>
							<TextareaControl
								label={ sprintf(
									/* translators: %s: build name. */
									__( '%s: verdict', 'zvg-fse' ),
									buildLabel( build )
								) }
								value={
									( verdicts[ build ] &&
										verdicts[ build ].text ) ||
									''
								}
								onChange={ ( value ) =>
									setVerdict( build, 'text', value )
								}
							/>
						</div>
					) ) }
				</PanelBody>
				<PanelBody
					title={ __( 'The three options', 'zvg-fse' ) }
					initialOpen={ false }
				>
					{ definitions.map( ( item, index ) => (
						<div key={ 'definition-' + index }>
							<TextControl
								label={ sprintf(
									/* translators: %d: position of the option in the list. */
									__( 'Option %d: name', 'zvg-fse' ),
									index + 1
								) }
								value={ item.term || '' }
								onChange={ ( value ) =>
									setDefinition( index, 'term', value )
								}
							/>
							<TextareaControl
								label={ sprintf(
									/* translators: %d: position of the option in the list. */
									__( 'Option %d: description', 'zvg-fse' ),
									index + 1
								) }
								value={ item.description || '' }
								onChange={ ( value ) =>
									setDefinition( index, 'description', value )
								}
							/>
						</div>
					) ) }
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<ServerSideRender
					block="zvg-fse/build-chooser"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
