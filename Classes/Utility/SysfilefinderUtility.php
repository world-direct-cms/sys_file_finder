<?php

namespace WorldDirect\Sysfilefinder\Utility;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Resource\ProcessedFileRepository;

class SysfilefinderUtility
{
    /**
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * @param ResourceFactory $resourceFactory
     */
    public function __construct(ResourceFactory $resourceFactory = null)
    {
        $this->resourceFactory = $resourceFactory ?? GeneralUtility::makeInstance(ResourceFactory::class);
    }

    /**
     * This method receives a frontend link with "...index.php?eID=..."
     * It handles "f" and "p" parameters (file VS processedFile)
     * 
     * @param string $link The link string containing "index.php"
     * 
     * @return string The file path of the file
     */
    public function getSysFilePathFromLink(string $link): string
    {
        // Get the GET parameters array
        $parameters = $this->getGetParametersFromUrl($link);

        // File
        if (isset($parameters['f'])) {
            $file = $this->resourceFactory->getFileObject($parameters['f']);
        }
        // Processed file
        else if (isset($parameters['p'])) {
            /** @var \TYPO3\CMS\Core\Resource\ProcessedFile $file */
            $processedFile = GeneralUtility::makeInstance(ProcessedFileRepository::class)->findByUid($parameters['p']);
            $file = $processedFile->getOriginalFile();
        }

        if (isset($file)) {
            return $file->getIdentifier();
        }

        return '';
    }

    /**
     * Method gets a full URL and return an array with all GET parameters
     * where the key is the name of the parameter and the value is the
     * value of the GET parameter.
     * 
     * @param string $url The full url
     * 
     * @return array Holding all GET parameters
     */
    private function getGetParametersFromUrl(string $url): array
    {
        $urlParts = parse_url($url);
        $getParameters = explode("&", $urlParts['query']);
        $parameters = [];
        foreach ($getParameters as $parameter) {
            $parts = explode('=', $parameter);
            $parameters[$parts[0]] = $parts[1];
        }
        return $parameters;
    }

    /**
     * 
     * @param array $row The sys_file row from the database
     * 
     * @return void 
     */
    public function createFlashMessage(string $path)
    {
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $path,
            'Pfad der Datei lautet:',
            \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
            true
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }
}
