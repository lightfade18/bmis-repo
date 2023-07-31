<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\ListResource;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\LocalList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\MachineToMachineList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\MobileList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\NationalList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\SharedCostList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\TollFreeList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\VoipList;
use Twilio\Values;
use Twilio\Version;

/**
 * @property LocalList $local
 * @property TollFreeList $tollFree
 * @property MobileList $mobile
 * @property NationalList $national
 * @property VoipList $voip
 * @property SharedCostList $sharedCost
 * @property MachineToMachineList $machineToMachine
 */
class AvailablePhoneNumberCountryContext extends InstanceContext {
    protected $_local;
    protected $_tollFree;
    protected $_mobile;
    protected $_national;
    protected $_voip;
    protected $_sharedCost;
    protected $_machineToMachine;

    /**
     * Initialize the AvailablePhoneNumberCountryContext
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid The SID of the Account requesting the available
     *                           phone number Country resource
     * @param string $countryCode The ISO country code of the country to fetch
     *                            available phone number information about
     */
    public function __construct(Version $version, $accountSid, $countryCode) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['accountSid' => $accountSid, 'countryCode' => $countryCode, ];

        $this->uri = '/Accounts/' . \rawurlencode($accountSid) . '/AvailablePhoneNumbers/' . \rawurlencode($countryCode) . '.json';
    }

    /**
     * Fetch the AvailablePhoneNumberCountryInstance
     *
     * @return AvailablePhoneNumberCountryInstance Fetched
     *                                             AvailablePhoneNumberCountryInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): AvailablePhoneNumberCountryInstance {
        $payload = $this->version->fetch('GET', $this->uri);

        return new AvailablePhoneNumberCountryInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['countryCode']
        );
    }

    /**
     * Access the local
     */
    protected function getLocal(): LocalList {
        if (!$this->_local) {
            $this->_local = new LocalList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_local;
    }

    /**
     * Access the tollFree
     */
    protected function getTollFree(): TollFreeList {
        if (!$this->_tollFree) {
            $this->_tollFree = new TollFreeList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_tollFree;
    }

    /**
     * Access the mobile
     */
    protected function getMobile(): MobileList {
        if (!$this->_mobile) {
            $this->_mobile = new MobileList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_mobile;
    }

    /**
     * Access the national
     */
    protected function getNational(): NationalList {
        if (!$this->_national) {
            $this->_national = new NationalList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_national;
    }

    /**
     * Access the voip
     */
    protected function getVoip(): VoipList {
        if (!$this->_voip) {
            $this->_voip = new VoipList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_voip;
    }

    /**
     * Access the sharedCost
     */
    protected function getSharedCost(): SharedCostList {
        if (!$this->_sharedCost) {
            $this->_sharedCost = new SharedCostList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_sharedCost;
    }

    /**
     * Access the machineToMachine
     */
    protected function getMachineToMachine(): MachineToMachineList {
        if (!$this->_machineToMachine) {
            $this->_machineToMachine = new MachineToMachineList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }

        return $this->_machineToMachine;
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get(string $name): ListResource {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments): InstanceContext {
        $property = $this->$name;
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Api.V2010.AvailablePhoneNumberCountryContext ' . \implode(' ', $context) . ']';
    }
}