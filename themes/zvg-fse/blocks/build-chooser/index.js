(function (blocks, element, blockEditor, components, serverSideRender, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl, TextareaControl } = components;
	const ServerSideRender = serverSideRender;
	const { __, sprintf } = i18n;

	const BUILDS = ['fse', 'elementor', 'acf'];
	const DEFAULTS = window.zvgFseChooserDefaults || {};

	registerBlockType('zvg-fse/build-chooser', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const steps = attributes.steps.length ? attributes.steps : DEFAULTS.steps || [];
			const verdicts = Object.keys(attributes.verdicts).length
				? attributes.verdicts
				: DEFAULTS.verdicts || {};
			const definitions = attributes.definitions.length
				? attributes.definitions
				: DEFAULTS.definitions || [];

			const buildLabel = (build) => (verdicts[build] && verdicts[build].title) || build;

			const setStep = (index, key, value) =>
				setAttributes({
					steps: steps.map((step, i) =>
						i === index ? Object.assign({}, step, { [key]: value }) : step
					),
				});

			const setChoice = (stepIndex, choiceIndex, key, value) =>
				setStep(
					stepIndex,
					'choices',
					steps[stepIndex].choices.map((choice, i) =>
						i === choiceIndex ? Object.assign({}, choice, { [key]: value }) : choice
					)
				);

			const setVerdict = (build, key, value) =>
				setAttributes({
					verdicts: Object.assign({}, verdicts, {
						[build]: Object.assign({}, verdicts[build], { [key]: value }),
					}),
				});

			const setDefinition = (index, key, value) =>
				setAttributes({
					definitions: definitions.map((item, i) =>
						i === index ? Object.assign({}, item, { [key]: value }) : item
					),
				});

			const questionPanels = steps.map((step, stepIndex) =>
				el(
					PanelBody,
					{
						key: 'step-' + stepIndex,
						/* translators: %d: position of the question in the questionnaire. */
						title: sprintf(__('Question %d', 'zvg-fse'), stepIndex + 1),
						initialOpen: false,
					},
					el(TextControl, {
						/* translators: %d: position of the question in the questionnaire. */
						label: sprintf(__('Question %d: text', 'zvg-fse'), stepIndex + 1),
						value: step.question || '',
						onChange: (value) => setStep(stepIndex, 'question', value),
					}),
					(step.choices || []).map((choice, choiceIndex) =>
						el(
							'div',
							{ key: 'choice-' + choiceIndex },
							el(TextControl, {
								label: sprintf(
									/* translators: 1: question number, 2: answer number. */
									__('Question %1$d · answer %2$d: label', 'zvg-fse'),
									stepIndex + 1,
									choiceIndex + 1
								),
								value: choice.label || '',
								onChange: (value) => setChoice(stepIndex, choiceIndex, 'label', value),
							}),
							BUILDS.map((build) =>
								el(TextControl, {
									key: build,
									type: 'number',
									label: sprintf(
										/* translators: 1: answer number, 2: build name. */
										__('Answer %1$d: points for %2$s', 'zvg-fse'),
										choiceIndex + 1,
										buildLabel(build)
									),
									value: typeof choice[build] === 'number' ? String(choice[build]) : '0',
									onChange: (value) =>
										setChoice(stepIndex, choiceIndex, build, parseInt(value, 10) || 0),
								})
							)
						)
					)
				)
			);

			const verdictPanel = el(
				PanelBody,
				{ title: __('Verdicts', 'zvg-fse'), initialOpen: false },
				BUILDS.map((build) =>
					el(
						'div',
						{ key: build },
						el(TextControl, {
							/* translators: %s: build name. */
							label: sprintf(__('%s: title', 'zvg-fse'), buildLabel(build)),
							value: (verdicts[build] && verdicts[build].title) || '',
							onChange: (value) => setVerdict(build, 'title', value),
						}),
						el(TextareaControl, {
							/* translators: %s: build name. */
							label: sprintf(__('%s: verdict', 'zvg-fse'), buildLabel(build)),
							value: (verdicts[build] && verdicts[build].text) || '',
							onChange: (value) => setVerdict(build, 'text', value),
						})
					)
				)
			);

			const definitionPanel = el(
				PanelBody,
				{ title: __('The three options', 'zvg-fse'), initialOpen: false },
				definitions.map((item, index) =>
					el(
						'div',
						{ key: 'definition-' + index },
						el(TextControl, {
							/* translators: %d: position of the option in the list. */
							label: sprintf(__('Option %d: name', 'zvg-fse'), index + 1),
							value: item.term || '',
							onChange: (value) => setDefinition(index, 'term', value),
						}),
						el(TextareaControl, {
							/* translators: %d: position of the option in the list. */
							label: sprintf(__('Option %d: description', 'zvg-fse'), index + 1),
							value: item.description || '',
							onChange: (value) => setDefinition(index, 'description', value),
						})
					)
				)
			);

			return el(
				Fragment,
				{},
				el(InspectorControls, {}, questionPanels, verdictPanel, definitionPanel),
				el(
					'div',
					useBlockProps(),
					el(ServerSideRender, {
						block: 'zvg-fse/build-chooser',
						attributes: attributes,
					})
				)
			);
		},

		save: function () {
			return null;
		},
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender,
	window.wp.i18n
);
