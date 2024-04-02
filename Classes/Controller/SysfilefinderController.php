<?php

namespace WorldDirect\Sysfilefinder\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use WorldDirect\Sysfilefinder\Utility\SysfilefinderUtility;

class SysfilefinderController extends ActionController
{
    /** @var string */
    // This is necessary in order to have a Backend Module with all the CSS and JS
    // necessary for the be viewhelpers to work!!!
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Action to show the search form as well as the result of the search.
     * 
     * @param array $searchData The searchData array from the view
     * 
     * @return void 
     */
    public function indexAction(array $searchData = null): void
    {
        if ($searchData) {
            if (isset($searchData['link'])) {

                /** @var SysfilefinderUtility $utility */
                $utility = GeneralUtility::makeInstance(SysfilefinderUtility::class);

                // path
                $path = $utility->getSysFilePathFromLink($searchData['link']);

                // Only show when the row is an array (this means that there is a result)
                if ($path != '') {
                    // Create flash message for result display
                    $utility->createFlashMessage($path);

                    // Assign the search data link to the view to display in input element
                    $this->view->assignMultiple([
                        'link' => $searchData['link'],
                    ]);
                }
            }
        }
    }
}
