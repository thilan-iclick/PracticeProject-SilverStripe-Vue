<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Core\Environment;
    use SilverStripe\View\Requirements;

    class PageController extends ContentController
    {
        private static $allowed_actions = [];

        protected function init()
        {
            parent::init();

            $liveReload = Environment::getEnv('LIVE_RELOAD_URL');

            if (!empty($liveReload)) {
                Requirements::javascript($liveReload);
            }

            Requirements::css('app.css');
            Requirements::javascript('app.js');
        }

        public function appSettings()
        {
            $data = json_encode([
                'MAPBOX_TOKEN' => Environment::getEnv('MAPBOX_TOKEN')
            ], TRUE);

            return $data;
        }
    }
}
