<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StockUsedFor
 */
class StockUsedFor
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var decimal $quantity
     */
    private $quantity;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var ItemPairsLogic
     */
    private $itemPairLogic;

    /**
     * @var StockDetail
     */
    private $stockDetail;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var TransactionTypes
     */
    private $transactionType;


    /**
     * Get pkId
     *
     * @return integer 
     */
    public function getPkId()
    {
        return $this->pkId;
    }

    /**
     * Set quantity
     *
     * @param decimal $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return decimal 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set createdDate
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return datetime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set modifiedDate
     *
     * @param datetime $modifiedDate
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * Get modifiedDate
     *
     * @return datetime 
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Set itemPairLogic
     *
     * @param ItemPairsLogic $itemPairLogic
     */
    public function setItemPairLogic(\ItemPairsLogic $itemPairLogic)
    {
        $this->itemPairLogic = $itemPairLogic;
    }

    /**
     * Get itemPairLogic
     *
     * @return ItemPairsLogic 
     */
    public function getItemPairLogic()
    {
        return $this->itemPairLogic;
    }

    /**
     * Set stockDetail
     *
     * @param StockDetail $stockDetail
     */
    public function setStockDetail(\StockDetail $stockDetail)
    {
        $this->stockDetail = $stockDetail;
    }

    /**
     * Get stockDetail
     *
     * @return StockDetail 
     */
    public function getStockDetail()
    {
        return $this->stockDetail;
    }

    /**
     * Set createdBy
     *
     * @param Users $createdBy
     */
    public function setCreatedBy(\Users $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get createdBy
     *
     * @return Users 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set modifiedBy
     *
     * @param Users $modifiedBy
     */
    public function setModifiedBy(\Users $modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return Users 
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set transactionType
     *
     * @param TransactionTypes $transactionType
     */
    public function setTransactionType(\TransactionTypes $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * Get transactionType
     *
     * @return TransactionTypes 
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }
}