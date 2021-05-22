<?php


namespace App;


class Transaction
{
    private int $value;
    private \DateTime $confirmed;

    /**
     * Transaction constructor.
     * @param int $value
     * @param \DateTime $confirmed
     */
    public function __construct(int $value, string $confirmed)
    {
        $this->value = $value;
        $this->confirmed = new \DateTime($confirmed);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return \DateTime
     */
    public function getConfirmed(): \DateTime
    {
        return $this->confirmed;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @param \DateTime $confirmed
     */
    public function setConfirmed(\DateTime $confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @param array $transactionsArray
     * @return array
     */
    public static function getTransactionsArray(array $transactionsArray):array
    {
        $valuesArr = [];
        foreach ($transactionsArray as $transaction){
            $valuesArr[]=$transaction->getValue();
        }

        return $valuesArr;
    }

}