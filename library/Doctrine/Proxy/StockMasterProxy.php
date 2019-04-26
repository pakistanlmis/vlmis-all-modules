<?php

namespace Doctrine\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class StockMasterProxy extends \StockMaster implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function getPkId()
    {
        $this->__load();
        return parent::getPkId();
    }

    public function setTransactionDate($transactionDate)
    {
        $this->__load();
        return parent::setTransactionDate($transactionDate);
    }

    public function getTransactionDate()
    {
        $this->__load();
        return parent::getTransactionDate();
    }

    public function setTransactionNumber($transactionNumber)
    {
        $this->__load();
        return parent::setTransactionNumber($transactionNumber);
    }

    public function getTransactionNumber()
    {
        $this->__load();
        return parent::getTransactionNumber();
    }

    public function setTransactionCounter($transactionCounter)
    {
        $this->__load();
        return parent::setTransactionCounter($transactionCounter);
    }

    public function getTransactionCounter()
    {
        $this->__load();
        return parent::getTransactionCounter();
    }

    public function setTransactionReference($transactionReference)
    {
        $this->__load();
        return parent::setTransactionReference($transactionReference);
    }

    public function getTransactionReference()
    {
        $this->__load();
        return parent::getTransactionReference();
    }

    public function setDispatchBy($dispatchBy)
    {
        $this->__load();
        return parent::setDispatchBy($dispatchBy);
    }

    public function getDispatchBy()
    {
        $this->__load();
        return parent::getDispatchBy();
    }

    public function setDraft($draft)
    {
        $this->__load();
        return parent::setDraft($draft);
    }

    public function getDraft()
    {
        $this->__load();
        return parent::getDraft();
    }

    public function setComments($comments)
    {
        $this->__load();
        return parent::setComments($comments);
    }

    public function getComments()
    {
        $this->__load();
        return parent::getComments();
    }

    public function setParentId($parentId)
    {
        $this->__load();
        return parent::setParentId($parentId);
    }

    public function getParentId()
    {
        $this->__load();
        return parent::getParentId();
    }

    public function setCampaignId($campaignId)
    {
        $this->__load();
        return parent::setCampaignId($campaignId);
    }

    public function getCampaignId()
    {
        $this->__load();
        return parent::getCampaignId();
    }

    public function setCreatedDate($createdDate)
    {
        $this->__load();
        return parent::setCreatedDate($createdDate);
    }

    public function getCreatedDate()
    {
        $this->__load();
        return parent::getCreatedDate();
    }

    public function setIssueFrom($issueFrom)
    {
        $this->__load();
        return parent::setIssueFrom($issueFrom);
    }

    public function getIssueFrom()
    {
        $this->__load();
        return parent::getIssueFrom();
    }

    public function setIssueTo($issueTo)
    {
        $this->__load();
        return parent::setIssueTo($issueTo);
    }

    public function getIssueTo()
    {
        $this->__load();
        return parent::getIssueTo();
    }

    public function setModifiedDate($modifiedDate)
    {
        $this->__load();
        return parent::setModifiedDate($modifiedDate);
    }

    public function getModifiedDate()
    {
        $this->__load();
        return parent::getModifiedDate();
    }

    public function setModifiedBy(\Users $modifiedBy)
    {
        $this->__load();
        return parent::setModifiedBy($modifiedBy);
    }

    public function getModifiedBy()
    {
        $this->__load();
        return parent::getModifiedBy();
    }

    public function setTransactionType(\TransactionTypes $transactionType)
    {
        $this->__load();
        return parent::setTransactionType($transactionType);
    }

    public function getTransactionType()
    {
        $this->__load();
        return parent::getTransactionType();
    }

    public function setFromWarehouse(\Warehouses $fromWarehouse)
    {
        $this->__load();
        return parent::setFromWarehouse($fromWarehouse);
    }

    public function getFromWarehouse()
    {
        $this->__load();
        return parent::getFromWarehouse();
    }

    public function setShipment(\Shipments $shipment)
    {
        $this->__load();
        return parent::setShipment($shipment);
    }

    public function getShipment()
    {
        $this->__load();
        return parent::getShipment();
    }

    public function setToWarehouse(\Warehouses $toWarehouse)
    {
        $this->__load();
        return parent::setToWarehouse($toWarehouse);
    }

    public function getToWarehouse()
    {
        $this->__load();
        return parent::getToWarehouse();
    }

    public function setStakeholderActivity(\StakeholderActivities $stakeholderActivity)
    {
        $this->__load();
        return parent::setStakeholderActivity($stakeholderActivity);
    }

    public function getStakeholderActivity()
    {
        $this->__load();
        return parent::getStakeholderActivity();
    }

    public function setCreatedBy(\Users $createdBy)
    {
        $this->__load();
        return parent::setCreatedBy($createdBy);
    }

    public function getCreatedBy()
    {
        $this->__load();
        return parent::getCreatedBy();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'pkId', 'transactionDate', 'transactionNumber', 'transactionCounter', 'transactionReference', 'dispatchBy', 'draft', 'comments', 'parentId', 'campaignId', 'createdDate', 'issueFrom', 'issueTo', 'modifiedDate', 'modifiedBy', 'transactionType', 'fromWarehouse', 'shipment', 'toWarehouse', 'stakeholderActivity', 'createdBy');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}