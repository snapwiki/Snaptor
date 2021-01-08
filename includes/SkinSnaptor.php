<?php
/**
 * Vector - Modern version of MonoBook with fresh look and many usability
 * improvements.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

use MediaWiki\MediaWikiServices;
use Snaptor\Constants;
use Wikimedia\WrappedString;

/**
 * Skin subclass for Vector
 * @ingroup Skins
 * @final skins extending SkinVector are not supported
 * @unstable
 */
class SkinSnaptor extends SkinTemplate {
	public $skinname = Constants::SKIN_NAME;
	public $stylename = 'Snaptor';
	public $template = 'SnaptorTemplate';

	/**
	 * @inheritDoc
	 * @return array
	 */
	public function getDefaultModules() {
		$modules = parent::getDefaultModules();

		if ( $this->isLegacy() ) {
			$modules['styles']['skin'][] = 'skins.snaptor.styles.legacy';
			$modules[Constants::SKIN_NAME] = 'skins.snaptor.legacy.js';
		} else {
			$modules['styles'] = array_merge(
				$modules['styles'],
				[ 'skins.snaptor.styles', 'mediawiki.ui.icon', 'skins.snaptor.icons' ]
			);
			$modules[Constants::SKIN_NAME][] = 'skins.snaptor.js';
		}

		return $modules;
	}

	/**
	 * Set up the SnaptorTemplate. Overrides the default behaviour of SkinTemplate allowing
	 * the safe calling of constructor with additional arguments. If dropping this method
	 * please ensure that SnaptorTemplate constructor arguments match those in SkinTemplate.
	 *
	 * @internal
	 * @param string $classname
	 * @return SnaptorTemplate
	 */
	protected function setupTemplate( $classname ) {
		$tp = new TemplateParser( __DIR__ . '/templates' );
		return new SnaptorTemplate( $this->getConfig(), $tp, $this->isLegacy() );
	}

	/**
	 * Whether or not the legacy version of the skin is being used.
	 *
	 * @return bool
	 */
	private function isLegacy() : bool {
		$isLatestSkinFeatureEnabled = MediaWikiServices::getInstance()
			->getService( Constants::SERVICE_FEATURE_MANAGER )
			->isFeatureEnabled( Constants::FEATURE_LATEST_SKIN );

		return !$isLatestSkinFeatureEnabled;
	}

	/**
	 * @internal only for use inside VectorTemplate
	 * @return array of data for a Mustache template
	 */
	public function getTemplateData() {
		$out = $this->getOutput();
		$title = $out->getTitle();

		$indicators = [];
		foreach ( $out->getIndicators() as $id => $content ) {
			$indicators[] = [
				'id' => Sanitizer::escapeIdForAttribute( "mw-indicator-$id" ),
				'class' => 'mw-indicator',
				'html' => $content,
			];
		}

		$printFooter = Html::rawElement(
			'div',
			[ 'class' => 'printfooter' ],
			$this->printSource()
		);

		return [
			// Data objects:
			'array-indicators' => $indicators,
			// HTML strings:
			'html-printtail' => WrappedString::join( "\n", [
				MWDebug::getHTMLDebugLog(),
				MWDebug::getDebugHTML( $this->getContext() ),
				$this->bottomScripts(),
				wfReportTime( $out->getCSP()->getNonce() )
			] ) . '</body></html>',
			'html-site-notice' => $this->getSiteNotice(),
			'html-userlangattributes' => $this->prepareUserLanguageAttributes(),
			'html-subtitle' => $this->prepareSubtitle(),
			// Always returns string, cast to null if empty.
			'html-undelete-link' => $this->prepareUndeleteLink() ?: null,
			// Result of OutputPage::addHTML calls
			'html-body-content' => $this->wrapHTML( $title, $out->mBodytext )
				. $printFooter,
			'html-after-content' => $this->afterContentHook(),
		];
	}

	/**
	 * @internal only for use inside SnaptorTemplate
	 * @return array
	 */
	public function getMenuProps() {
		return $this->buildContentNavigationUrls();
	}
}
