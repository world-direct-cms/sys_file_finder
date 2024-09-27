<?php

namespace WorldDirect\Sysfilefinder\Utility;

use Exception;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class EmptyQueryPartException extends Exception{}
class MissingQueryParametersException extends Exception{}
class ResourceFileNotFoundException extends Exception{}
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
     * @throws MissingQueryParametersException
     */
    public function getSysFilePathFromLink(string $link): string
    {
        // Get the GET parameters array
        $parameters = $this->getGetParametersFromUrl($link);

        // File
        if (isset($parameters['f'])) {
            try {
                $file = $this->resourceFactory->getFileObject($parameters['f']);
            } catch(Exception $e) {
                // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($e);
                throw new ResourceFileNotFoundException('There was no file found with the given "f" parameter value.', 3456982);
            }
        }
        // Processed file
        else if (isset($parameters['p'])) {
            /** @var \TYPO3\CMS\Core\Resource\ProcessedFile $file */
            try {
                $processedFile = GeneralUtility::makeInstance(ProcessedFileRepository::class)->findByUid($parameters['p']);
                $file = $processedFile->getOriginalFile();
            } catch(Exception $e) {
                throw new ResourceFileNotFoundException('There was no processed file found with the given "p" parameter value.', 1095993);
            }
        } else {
            // If there is no "f" or "l" parameter throw new MissingQueryParameterException
            throw new MissingQueryParametersException('The given link does not have a "l" or "f" query parameter. Withouth one of these parameters TYPO3 cannot find a path to a file.', 900248);
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
     * @throws EmptyQueryPartException
     */
    private function getGetParametersFromUrl(string $url): array
    {
        $urlParts = parse_url($url);

        // Check if the urlParts have a 'query' part, if not throw an exception
        if (!isset($urlParts['query'])) {
            throw new EmptyQueryPartException('The given link does not have a query parameters attached. It must have a "f" or "p" parameter.', 486032);
        }

        // Explode the query parameters and return them
        $getParameters = explode("&", $urlParts['query']);
        $parameters = [];
        foreach ($getParameters as $parameter) {
            $parts = explode('=', $parameter);
            $parameters[$parts[0]] = $parts[1];
        }
        return $parameters;
    }
}
