import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return <span { ...useBlockProps() }>{ new Date().getFullYear() }</span>;
}
