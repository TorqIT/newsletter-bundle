<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Document\Newsletter\AddressSourceAdapter;

use Pimcore\Document\Newsletter\IAddressSourceAdapter;
use Pimcore\Document\Newsletter\SendingParamContainer;

class CsvList implements IAddressSourceAdapter
{

    /**
     * @var string[]
     */
    protected $emailAddresses;

    /**
     * IAddressSourceAdapter constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->emailAddresses = explode(",", $params['csvList']);
    }

    /**
     * returns array of email addresses for batch sending
     *
     * @return SendingParamContainer[]
     */
    public function getMailAddressesForBatchSending()
    {
        $containers = [];
        foreach ($this->emailAddresses as $address) {
            $containers[] = new SendingParamContainer($address, null);
        }

        return $containers;
    }

    /**
     * returns params to be set on mail for test sending
     *
     * @param string $emailAddress
     * @return SendingParamContainer
     */
    public function getParamsForTestSending($emailAddress)
    {
        return new SendingParamContainer($emailAddress, [
            'emailAddress' => current($this->emailAddresses)
        ]);
    }

    /**
     * returns total number of newsletter recipients
     *
     * @return int
     */
    public function getTotalRecordCount()
    {
        return count($this->emailAddresses);
    }

    /**
     * returns array of params to be set on mail for single sending
     *
     * @param $limit
     * @param $offset
     * @return array
     */
    public function getParamsForSingleSending($limit, $offset)
    {
        $addresses = array_slice($this->emailAddresses, $offset, $limit);
        $containers = [];
        foreach ($addresses as $address) {
            $containers[] = new SendingParamContainer($address, null);
        }

        return $containers;
    }
}
