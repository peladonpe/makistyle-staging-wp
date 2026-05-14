import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import save from './save';
import metadata from './block.json';
import { ReactComponent as Logo } from '../makistyle-logo.svg';

registerBlockType( metadata.name, {
	icon: { src: Logo },
	edit: Edit,
	save,
} );
