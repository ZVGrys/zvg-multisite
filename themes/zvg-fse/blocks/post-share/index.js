(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl, ToggleControl } = components;
	const { __ } = i18n;

	const NETWORKS = window.zvgFseShareNetworks || [];

	registerBlockType('zvg-fse/post-share', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const blockProps = useBlockProps();
			const chosen = NETWORKS.filter((network) => attributes[network.key]);

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Share links', 'zvg-fse'), initialOpen: true },
						el(TextControl, {
							label: __('Label', 'zvg-fse'),
							value: attributes.label,
							placeholder: __('Share this post', 'zvg-fse'),
							onChange: (value) => setAttributes({ label: value }),
						}),
						NETWORKS.map((network) =>
							el(ToggleControl, {
								key: network.key,
								label: network.name,
								checked: !!attributes[network.key],
								onChange: (value) => setAttributes({ [network.key]: value }),
							})
						)
					)
				),
				el(
					'div',
					blockProps,
					el(
						'p',
						{ className: 'wp-block-zvg-fse-post-share__label' },
						attributes.label || __('Share this post', 'zvg-fse')
					),
					el(
						'ul',
						{ className: 'wp-block-zvg-fse-post-share__list' },
						chosen.map((network) =>
							el(
								'li',
								{ key: network.key, className: 'wp-block-zvg-fse-post-share__item' },
								el(
									'span',
									{ className: 'wp-block-zvg-fse-post-share__link' },
									el(
										'svg',
										{
											className:
												'wp-block-zvg-fse-post-share__icon' +
												(network.stroke ? ' is-stroked' : ''),
											viewBox: '0 0 24 24',
											'aria-hidden': 'true',
											focusable: 'false',
										},
										el('path', { d: network.icon })
									),
									network.name
								)
							)
						)
					)
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
	window.wp.i18n
);
