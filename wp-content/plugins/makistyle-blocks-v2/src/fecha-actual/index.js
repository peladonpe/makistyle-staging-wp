import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as Logo } from '../makistyle-logo.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	icon: { src: Logo },
	edit: Edit,
} );
