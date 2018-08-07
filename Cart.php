<?php

/**
 * A basic shopping cart handler class.
 * Uses the singleton pattern to ensure that we only ever have one
 * instance of the class.
 * Uses the built in php session to make sure that we carry that state
 * over from one request to the next.
 * 
 * @category SimpleCart
 * @package  SimpleCart
 * 
 * @author   Quintin Venter
 * @since    07 August 2018
 */
class Cart
{
    
    /**
     * An instance of the current cart class.
     * 
     * @var Cart
     */
    protected static $instance = null;
    
    /**
     * If the php session has been started
     * 
     * @var bool
     */
    protected $session = false;
    
    /**
     * Private constructor for the singleton pattern.
     * 
     * @return void
     */
    private function __construct()
    {
    }
    
    /**
     * The singleton constructor.
     * 
     * @return Cart
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        self::$instance->createSession();
        return self::$instance;
    }
    
    /**
     * Checks if the session has been started.
     * If not then create the session.
     * 
     * @return bool
     */
    public function createSession()
    {
        if (!$this->session) {
            $this->session = session_start();
        }
        return $this->session;
    }
    
    /**
     * Adds a single item to the cart.
     * 
     * @param int $id - the key of the item being added
     * @param array $data - the data being added
     * 
     * @return void
     */
    public function add($id, $data)
    {
        if (!isset($_SESSION['content'])) {
            $_SESSION['content'] = array();
        }
        // check if we need to increment the count
        if (isset($_SESSION['content'][$id])) {
            ++$_SESSION['content'][$id]['count'];
        } else { // this is the first item so we need to setup the count
            $data['count'] = 1;
            $_SESSION['content'][$id] = $data;
        }
    }
    
    /**
     * Removes a single item from the cart.
     * 
     * @param int $id - the key of the item being removed
     * 
     * @return void
     */
    public function remove($id)
    {
        // make sure the requested item exists
        if (isset($_SESSION['content'][$id]) && isset($_SESSION['content'][$id]['count'])) {
            // check if we need to reduce the count
            if ($_SESSION['content'][$id]['count'] > 1) {
                --$_SESSION['content'][$id]['count'];
            } else { // we have nothing left so remove the item from the cart
                unset($_SESSION['content'][$id]);
            }
        }
    }
    
    /**
     * Gets the content of the cart.
     * 
     * @return array
     */
    public function getContent()
    {
        if (!isset($_SESSION['content'])) {
            $_SESSION['content'] = array();
        }
        return $_SESSION['content'];
    }
    
    /**
     * Gets the final total for all items in the cart.
     * 
     * @return float
     */
    public function getTotal()
    {
        $total = 0.0;
        $content = $this->getContent();
        // go over the content and calculate their totals
        foreach ($content as $item) {
            $total += $item['count'] * $item['price'];
        }
        return $total;
    }
    
}