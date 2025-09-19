<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class TimeKeeperException extends Exception
{
    protected $context = [];
    protected $logLevel = 'error';
    
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        array $context = [],
        string $logLevel = 'error'
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->logLevel = $logLevel;
        
        // Auto log exception ketika dibuat
        $this->logException();
    }
    
    /**
     * Log exception dengan context
     */
    protected function logException(): void
    {
        $logData = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'context' => $this->context,
            'trace' => $this->getTraceAsString()
        ];
        
        Log::log($this->logLevel, 'TimeKeeper Exception: ' . $this->getMessage(), $logData);
    }
    
    /**
     * Set additional context
     */
    public function setContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
    
    /**
     * Get context
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Set log level
     */
    public function setLogLevel(string $level): self
    {
        $this->logLevel = $level;
        return $this;
    }
}