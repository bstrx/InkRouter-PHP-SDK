<?php
/**
 * This file is part of InkRouter-PHP-SDK.
 *
 * Copyright (c) 2012 Opensoft (http://opensoftdev.com)
 */


/**
 * Client for sending requests to InkRouter
 *
 * @author Kirill Gusakov
 */
class InkRouter_Client_SoapClient implements InkRouter_Client_ClientInterface
{

    /**
     * @var string
     */
    private $wsdl;

    /**
     * @var SoapClient
     */
    private $soapClient;

    /**
     * Id associated with a certain print customer
     *
     * @var string
     */
    private $printCustomerId;

    /**
     * Key that is used by print customer for accessing InkRouter
     *
     * @var string
     */
    private $secretKey;

    /**
     * @var bool
     */
    private $connected;

    /**
     *
     * @param string $wsdl
     * @param string $printCustomerId
     * @param string $secretKey
     */
    public function __construct($wsdl, $printCustomerId, $secretKey)
    {
        $this->wsdl = $wsdl;
        $this->printCustomerId = $printCustomerId;
        $this->secretKey = $secretKey;
        $this->connected = false;
    }

    /**
     * @throws InkRouter_Exceptions_InkRouterNotAvailableException
     */
    public function connect()
    {
        if (!$this->connected) {
            try {
                $this->soapClient = @new SoapClient($this->wsdl);
            } catch (SoapFault $e) {
                throw InkRouter_Exceptions_SoapFaultAdapter::valueOf($e)->getException();
            }
        }

        $this->connected = true;

        return true;
    }

    /**
     * @param int $timestamp
     * @param InkRouter_Models_OrderInfo $orderInfo
     * @return mixed
     * @throws InkRouter_Exceptions_InkRouterNotAvailableException|InkRouter_Exceptions_AuthenticationException|InkRouter_Exceptions_ProcessingException|InkRouter_Exceptions_RejectionException
     */
    public function createOrder($timestamp, InkRouter_Models_OrderInfo $orderInfo)
    {
        $this->connect();

        try {
            return $this->soapClient->createOrder(array(
                'arg0' => $this->printCustomerId, 
                'arg1' => $this->secretKey, 
                'arg2' => $timestamp, 
                'arg3' => $orderInfo->pack(true)))->return; 
        } catch (SoapFault $e) {
            throw InkRouter_Exceptions_SoapFaultAdapter::valueOf($e)->getException();
        }
    }

    /**
     * @param int $orderId
     * @param int $timestamp
     * @param InkRouter_Models_OrderInfo $orderInfo
     * @return mixed
     * @throws InkRouter_Exceptions_InkRouterNotAvailableException|InkRouter_Exceptions_AuthenticationException|InkRouter_Exceptions_ProcessingException|InkRouter_Exceptions_RejectionException
     */
    public function updateOrder($orderId, $timestamp, InkRouter_Models_OrderInfo $orderInfo)
    {
        $this->connect();

        try {
            return $this->soapClient->updateOrder(array(
                'arg0' => $this->printCustomerId,
                'arg1' => $this->secretKey,
                'arg2' => $orderId,
                'arg3' => $timestamp,
                'arg4' => $orderInfo->pack()))->return;
        } catch (SoapFault $e) {
            throw InkRouter_Exceptions_SoapFaultAdapter::valueOf($e)->getException();
        }
    }

    /**
     * @param int $orderId
     * @param int $timestamp
     * @return mixed
     * @throws InkRouter_Exceptions_InkRouterNotAvailableException|InkRouter_Exceptions_AuthenticationException|InkRouter_Exceptions_ProcessingException|InkRouter_Exceptions_RejectionException
     */
    public function placeOnHold($orderId, $timestamp)
    {
        $this->connect();

        try {
            return $this->soapClient->placeOnHold(array(
                'arg0' => $this->printCustomerId,
                'arg1' => $this->secretKey,
                'arg2' => $orderId,
                'arg3' => $timestamp
            ))->return;
        } catch (SoapFault $e) {
            throw InkRouter_Exceptions_SoapFaultAdapter::valueOf($e)->getException();
        }
    }

    /**
     * @param int $orderId
     * @param int $timestamp
     * @return mixed
     * @throws InkRouter_Exceptions_InkRouterNotAvailableException|InkRouter_Exceptions_AuthenticationException|InkRouter_Exceptions_ProcessingException|InkRouter_Exceptions_RejectionException
     */
    public function removeHold($orderId, $timestamp)
    {
        $this->connect();

        try {
            return $this->soapClient->removeHold(array(
                'arg0' => $this->printCustomerId,
                'arg1' => $this->secretKey,
                'arg2' => $orderId,
                'arg3' => $timestamp
            ))->return;
        } catch (SoapFault $e) {
            throw InkRouter_Exceptions_SoapFaultAdapter::valueOf($e)->getException();
        }
    }

    /**
     * @param int $orderId
     * @param int $timestamp
     * @return mixed
     * @throws InkRouter_Exceptions_InkRouterNotAvailableException|InkRouter_Exceptions_AuthenticationException|InkRouter_Exceptions_ProcessingException|InkRouter_Exceptions_RejectionException
     */
    public function cancelOrder($orderId, $timestamp)
    {
        $this->connect();

        try {
            return $this->soapClient->cancelOrder(array(
                'arg0' => $this->printCustomerId,
                'arg1' => $this->secretKey,
                'arg2' => $orderId,
                'arg3' => $timestamp
            ))->return;
        } catch (SoapFault $e) {
            throw InkRouter_Exceptions_SoapFaultAdapter::valueOf($e)->getException();
        }
    }
}
