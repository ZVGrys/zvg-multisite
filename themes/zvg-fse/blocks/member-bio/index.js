(function (blocks, element, blockEditor, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el } = element;
	const { useBlockProps } = blockEditor;
	const { _x } = i18n;

	registerBlockType('zvg-fse/member-bio', {
		edit: function Edit() {
			return el(
				'p',
				useBlockProps(),
				_x('Member bio', 'Editor placeholder', 'zvg-fse')
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
	window.wp.i18n
);
