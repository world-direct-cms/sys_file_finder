<?php

namespace WorldDirect\Sysfilefinder\Utility;

use InvalidArgumentException;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

class SysfilefinderUtility
{
    /**
     * This method receives a frontend link with "...index.php?eID=..."
     * 
     * @param string $link The link string containing "index.php"
     * 
     * @return array The "sys_file" row for the given uid
     */
    public function getSysFileFromLink(string $link): ?array
    {

        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        /** @var QueryBuilder $qb */
        $qb = $connectionPool->getQueryBuilderForTable('sys_file');

        $uid = $this->getSysFileUidFromLink($link);

        if ($uid) {
            $result = $qb
                ->select("*")
                ->from('sys_file')
                ->where(
                    $qb->expr()->eq('uid', $uid)
                )
                ->execute()
                ->fetchAllAssociative();
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Function parses the given link in order to get the sys_file uid.
     * 
     * @param string $link The link string containing "index.php"
     * 
     * @return int The sys_file uid
     */
    public function getSysFileUidFromLink(?string $link)
    {
        if ($link) {
            preg_match('/f=([\d]*)&token/m', $link, $matches);
            if (isset($matches[1])) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * 
     * @param array $row The sys_file row from the database
     * 
     * @return void 
     */
    public function createFlashMessage(array $row)
    {
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            'Pfad der Datei mit der "uid" ' . $row['uid'] . '"',
            $row['identifier'],
            \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
            true
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }
}
