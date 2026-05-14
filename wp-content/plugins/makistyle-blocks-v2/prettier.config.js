import wordpressPrettierConfig from '@wordpress/prettier-config';

/** @type {import("prettier").Config} */
const config = {
	...wordpressPrettierConfig,
	plugins: [
		...( wordpressPrettierConfig.plugins ?? [] ),
		'@prettier/plugin-php',
	],
	overrides: [
		...( wordpressPrettierConfig.overrides ?? [] ),
		{
			files: '*.php',
			options: {
				parser: 'php',
				phpVersion: '8.0',
				trailingCommaPHP: true,
			},
		},
	],
};

export default config;
