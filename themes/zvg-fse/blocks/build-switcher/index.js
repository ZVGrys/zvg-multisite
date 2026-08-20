(function (blocks, element, blockEditor) {
	const { registerBlockType } = blocks;
	const { createElement: el } = element;
	const { useBlockProps } = blockEditor;

	const LABELS = ['FSE', 'Elementor', 'ACF'];

	registerBlockType('zvg-fse/build-switcher', {
		edit: function Edit() {
			return el(
				'div',
				useBlockProps({ role: 'group' }),
				LABELS.map((label, index) =>
					el(
						'span',
						{
							key: label,
							className: 'wp-block-zvg-fse-build-switcher__link',
							'aria-current': index === 0 ? 'page' : undefined,
						},
						label
					)
				)
			);
		},

		save: function () {
			return null;
		},
	});
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
