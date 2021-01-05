import mustache from 'mustache';
import '../resources/skins.snaptor.styles/MenuDropdown.less';
import '../.storybook/common.less';
import { vectorMenuTemplate, moreData, variantsData } from './MenuDropdown.stories.data';

export default {
	title: 'MenuDropdown'
};

export const more = () => mustache.render( vectorMenuTemplate, moreData );

export const variants = () => mustache.render( vectorMenuTemplate, variantsData );
