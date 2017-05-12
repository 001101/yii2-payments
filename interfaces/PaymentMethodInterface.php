<?php

namespace macklus\payments\interfaces;

interface PaymentMethodInterface {

    public function configure($mod);

    public function setParameter($key, $value);

    public function getParameter($key);

    public function setItem($item);

    public function getItem();

    public function setCurrency($currency = 'EUR');

    public function getCurrency();

    public function isValid();

    public function setAmount($amount);

    public function getAmount();

    public function getErrors();
    
    public function process();
}
