<?php

require __DIR__  . '/../config/database.php';

class CashRegisterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Order $order
     */
    protected $order;

    /**
     * @var \App\Services\CashRegister $cashRegister
     */
    protected $cashRegister;

    public function setUp()
    {
        $order = \App\Order::with(['products', 'transactions'])->find(1);
        $this->order = $order;

        $this->cashRegister = new \App\Services\CashRegister();
    }

    public function testGetTransactionTotal()
    {
        $total = $this->cashRegister->getTransactionTotal($this->order);

        $this->assertEquals(\Money\Money::class, get_class($total));
        $this->assertEquals('1000', $total->getAmount());
        $this->assertEquals('USD', $total->getCurrency()->getCode());
    }

    public function testGetProductTotal()
    {
        $total = $this->cashRegister->getProductTotal($this->order);

        $this->assertEquals(\Money\Money::class, get_class($total));
        $this->assertEquals('1000', $total->getAmount());
        $this->assertEquals('USD', $total->getCurrency()->getCode());
    }

    public function testProductTotalEqualsTransactionTotal()
    {
        $productTotal = $this->cashRegister->getProductTotal($this->order);
        $transTotal = $this->cashRegister->getTransactionTotal($this->order);

        $this->assertEquals($productTotal, $transTotal);
        $this->assertEquals(get_class($productTotal), get_class($transTotal));
        $this->assertEquals($productTotal->getAmount(), $transTotal->getAmount());
        $this->assertEquals($productTotal->getCurrency()->getCode(), $transTotal->getCurrency()->getCode());  
    }
}
