import mustache from 'mustache';
import { FOOTER_TEMPLATE_DATA, FOOTER_TEMPLATE_PARTIALS,
	footerTemplate } from './Footer.stories.data';
import '../resources/skins.snaptor.styles/Footer.less';
import '../.storybook/common.less';

export default {
	title: 'Footer'
};

export const footer = () => mustache.render(
	footerTemplate,
	FOOTER_TEMPLATE_DATA,
	FOOTER_TEMPLATE_PARTIALS
);
