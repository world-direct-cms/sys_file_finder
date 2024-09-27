<?php

namespace WorldDirect\Sysfilefinder\Controller;

use Exception;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use WorldDirect\Sysfilefinder\Utility\SysfilefinderUtility;
use WorldDirect\Sysfilefinder\Utility\EmptyQueryPartException;
use WorldDirect\Sysfilefinder\Utility\MissingQueryParametersException;
use WorldDirect\Sysfilefinder\Utility\ResourceFileNotFoundException;

#[AsController]
class SysfilefinderController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    /**
     * Action to show the search form as well as the result of the search.
     * 
     * @param array $searchData The searchData array from the view
     * 
     * @return ResponseInterface
     */
    public function indexAction(array $searchData = null): ResponseInterface
    {
        $error = '';
        $path = '';
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        // The forms searchData object is filled
        if ($searchData) {
            // There is a link in the searchData
            if (isset($searchData['link'])) {

                /** @var SysfilefinderUtility $utility */
                $utility = GeneralUtility::makeInstance(SysfilefinderUtility::class);

                // Try to get a result path from the given link
                // Multiple errors/exception may occur
                try {
                    $path = $utility->getSysFilePathFromLink($searchData['link']);
                } catch (Exception $e) {
                    if ($e instanceof EmptyQueryPartException OR $e instanceof MissingQueryParametersException OR $e instanceof ResourceFileNotFoundException) {
                        $error = $e->getMessage();
                    } else {
                        throw $e;
                    }
                }
                
                $moduleTemplate->assignMultiple([
                    'navigationComponentId' => '', // Remove pagetree
                    'link' =>$searchData['link'],
                    'error' => $error,
                    'path' => $path
                ]);
                return $moduleTemplate->renderResponse('Index');
            }
        } else {
            // Start view, no link entered
            $moduleTemplate->assignMultiple([
                'navigationComponentId' => '', // Remove pagetree
            ]);
        }

        return $moduleTemplate->renderResponse('Index');
    }
}
