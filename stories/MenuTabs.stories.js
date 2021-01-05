import mustache from 'mustache';
import { menuTemplate as vectorTabsTemplate } from './Menu.stories.data';
import { namespaceTabsData, pageActionsData } from './MenuTabs.stories.data';
import '../resources/skins.snaptor.styles/MenuTabs.less';
import '../resources/skins.snaptor.styles/TabWatchstarLink.less';
import '../.storybook/common.less';

export default {
	title: 'MenuTabs'
};

export const pageActionTabs = () => mustache.render( vectorTabsTemplate, pageActionsData );

export const namespaceTabs = () => mustache.render( vectorTabsTemplate, namespaceTabsData );
